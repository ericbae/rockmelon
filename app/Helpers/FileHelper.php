<?php

namespace App\Helpers;
use App\Helpers\Helper,
    Illuminate\Http\Request;
use DB, Carbon\Carbon, File, Log, Exception;
use App\Models\Asset,
    App\Models\Broker,
    App\Models\Client,
    App\Models\PersonEmployer;
use Illuminate\Support\Facades\Storage; // to use Flysystem PHP package (to handle file storage)

class FileHelper {

  public static function upload($file, $clientId = null, $brokerId = null) {

    if(!empty($file)) {
      $s3   = Storage::disk('s3');
      $user = Helper::getUser();

      // set the user_id as the uploader ID of the file
      $toStore['user_id'] = $user['id'];
      $s3access = 'private';

      // S3 directory will have 2 parent folder i.e 'local' 'or' 'production' (that is the value specific in .env file APP_ENV value)
      $s3directory = env('APP_ENV') . '/';
      if(Helper::isAdmin($user) && empty(Broker::where('user_id', $user['id'])->first()->id)) {

        // if broker ID is passed  in, admin is uploading the file for that broker
        if($brokerId) {
          $toStore['broker_id'] = $brokerId;
          $s3directory          .= "{$brokerId}/";
          $toStore['private']   = false; // for broker related files, they are NOT private
          $s3access             = 'public';
        }

        else {
          // otherwise, admin is uploading the file i.e sample file type for sub categories, put the uploaded file in root 'admin' folder
          $s3directory        .= "admin/{$user['id']}/";
          $s3access           = 'public';
          $toStore['private'] = false;
        }

      }

      else if(Helper::isBroker($user['role'])) {
        // get broker ID
        $brokerId = Broker::where('user_id', $user['id'])->first()->id;

        // if client ID is passed in, broker is uploading the file for that client
        if($clientId) {
          $toStore['client_id'] = $clientId;
          $s3directory .= "{$brokerId}/{$clientId}/";
        }

        else {
          // otherwise, broker is uploading the file for himself,
          $toStore['broker_id'] = $brokerId;
          $s3directory .= "{$brokerId}/";

          // for broker related files, they are NOT private
          $toStore['private'] = false;
          $s3access = 'public';
        }

      }

      // if uploader is client - // client is uploading the file himself, get client ID
      else {
        $client               = DB::table('clients')->select('id', 'broker_id')->where('user_id', $user['id'])->first();
        $toStore['client_id'] = $client->id;
        $s3directory          .= "{$client->broker_id}/{$client->id}/";
      }

      // get size, original filename, extension and create a new file name for s3 of the file
      $size = $file->getSize();

      // check the size of the file
      if ($size > config('brokerpad.settings.fileSizeLimit')) {

        // throw error
        return [
          'message' => 'File size limit exceeds..',
          'code'    => 403
        ];
      }

      $filename     = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $ext          = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
      $mime         = $file->getClientMimeType();
      $newFileName  = $filename . '_' . time() . '.' . $ext;
      $s3UploadPath = $s3directory . $newFileName;

      // upload file to S3 bucket, first param: exact path+filename  to store to S3, third param: public or private
      if(!$s3->put($s3UploadPath, file_get_contents($file), $s3access)) {

        // something went wrong while uploading file to s3
        return [
          'message' => 'Unable to upload file.',
          'code' => 500
        ];
      }

      // GET s3 url
      $s3Url = $s3->url($s3UploadPath);

      // prepare data to store to db
      $fileData = [
        'original_file_name' => $filename . '.' . $ext,
        'new_file_name'      => $newFileName,
        'extension'          => $ext,
        'size'               => $size,
        'url'                => $s3Url,
        'directory'          => $s3UploadPath,
        'mime'               => $mime,
        'created_at'         => Carbon::now(),
        'updated_at'         => Carbon::now()
      ];

      $toStore = array_merge($toStore, $fileData);

      // insert the file record into Database
      $id = DB::table('files_new')->insertGetId($toStore);

      return [
        'message' => 'Successfully uploaded',
        'code'    => 200,
        'fileId'  => $id
      ];

    }// end of most outer IF

  }// end of upload method


  public static function download($fileId, $downloadAs) {
    $authorized = false;

    // get the file from 'file_news' table
    $file = DB::table('files_new')->select('*')->where('id', $fileId)->first();
    if(empty($file)) {
      throw new Exception("Invalid file");
    }

    $user = Helper::getUser();

    // if user is the one who uploaded the file , he can download the files.
    if($user->id == $file->user_id) {
      $authorized = true;
    }

    else if(Helper::isBroker($user->role)) { // IF user is broker
      // GET broker ID
      $brokerId = Broker::where('user_id', $user['id'])->first()->id;

      // Check if admin uploaded the file on behalf of broker i.e broker_id => broker ID of the requester , if yes grant access
      if(!empty($file->broker_id) && $file->broker_id == $brokerId) {
          $authorized = true;
      }

      else if(!empty($file->client_id)) {

        // Check if 'client_id' exist, if so check if that client belongs to the broker who is requesting to download the file, if yes grant access
        $client = DB::table('clients')->select('id', 'broker_id')->where('id', $file->client_id)->first();

        if($brokerId == $client->broker_id) {
          $authorized = true;
        }
      }
    }

    // if User is client
    else {

      // GET client ID
      $clientId = Client::where('user_id', $user['id'])->first()->id;

      // Check if broker uploaded the file on behalf of his. client i.e client_id => client ID of the requester , if yes grant access
      if(!empty($file->client_id) && $file->client_id == $clientId) {
        $authorized = true;
      }
    }

    // if File exist in files_new table and user is authrorized,then download or return url for the user.
    if($file && $authorized) {
      $s3 = Storage::disk('s3');

      // get contents of the file from s3 bucket..
      $fileContents = $s3->get($file->directory);

      // if we are downloading file as content, return now
      // otherwise, we save it to a public location and download
      if($downloadAs == 'content') {
        return $fileContents;
      }

      else {

        // make sure to create the public folder to store the temporary file on server side(will be delete after sending file to client)
        if(!File::exists(public_path() . "/uploads")) {
          File::makeDirectory(public_path() . "/uploads");
        }

        // set temporary file nae before sending file to the client.
        $tmpFileName = $user->id . '-' . time() . "." . $file->extension;
        $filePath    = public_path() . '/uploads';
        $fullPath    = $filePath . '/' . $tmpFileName;

        // create file from S3 as tmp file in 'public' folder
        File::put($fullPath, $fileContents);

        // return file downaload and then delete the file from the server.
        return response()->download($fullPath)->deleteFileAfterSend(true);
      }
    }

    else {
      // If get here, user is unauthorised to access the file
      throw new Exception("Not authorized to access the file.");
    }
  }

  // Function to download the ALL the files that have been uploaded for client as zip file.
  public static function downloadFilesAsZip($uploadedFiles, $client) {
    // both uploaded files and client data are required
    if(empty($uploadedFiles) || empty($client)) {
      return response()->json([
        'message' => 'Internal error.'
      ], 500);
    }

    $s3 = Storage::disk('s3');

    $downloadName = $client->getFullName().'.zip';
    $zipPath = tempnam(sys_get_temp_dir(), $downloadName);
    $zip = new \ZipArchive;
    $zipOpen = $zip->open($zipPath) === true;
    $files = $filenames = [];

    if($zipOpen) {
        foreach ($uploadedFiles as $uploadedFile) {
            // get contents of the file from s3 bucket..
            $fileContents = $s3->get($uploadedFile['directory']);

            $file = tempnam(sys_get_temp_dir(), $uploadedFile['id']);
            file_put_contents($file, $fileContents);

            $ext = $uploadedFile['extension'];
            $name = $uploadedFile['category_prefix'] . ' - ' . str_replace('/', '-', $uploadedFile['file_name']) . ' - ' . $uploadedFile['firstname'] . ' ' . $uploadedFile['lastname'];

            if (!empty($uploadedFile['description'])) {
                $name .= ' - ' . $uploadedFile['description'];
            }
            $name .= '.' . $uploadedFile['extension'];

            if(in_array($name.'.'.$ext, $filenames)) {
                $name .= '_';
                $i = 1;
                while (in_array($name.strval($i).'.'.$ext, $filenames)) {
                    $i += 1;
                }
                $name .= strval($i);
            }
            $zip->addFile($file, $name);
            $files[] = $file;
            $filenames[] = $name;
        }
        $zip->close();
    }
    foreach ($files as $file) {
        unlink($file);
    }
    if($zipOpen) {
        return response()->download($zipPath, $downloadName)->deleteFileAfterSend(true);
    }
  }

  // Function to delete the file from s3 bucket as well from the files table
  public static function delete($fileId) {

    // get file record from file_news table
    $file = DB::table('files_new')->where('id', '=', $fileId)->first();

    // File must be found
    if(empty($file)) {
      throw new Exception("File ID does not exist");
    }

    $user       = Helper::getUser();
    $authorized = false;

    // if user is admin he is allowed to delete any file from s3 bucket.
    if(Helper::isAdmin($user)) {
      $authorized = true;
    }

    else if(Helper::isBroker($user->role)) {

      // IF user is Broker, get broker ID
      $brokerId = Broker::where('user_id', $user['id'])->first()->id;

      // if this user upload this file, allow to delete
      if($file->user_id == $user->id) {
        $authorized = true;
      }

      else if(!empty($file->broker_id) && $brokerId == $file->broker_id) {
        // the file which is being removed is associated to the user via client-broker relationship.
        $authorized = true;
      }

      else if(!empty($file->client_id)) {
        // if client ID exist in file record, check if that client is belong the user via client-broke relationship.
        $client = DB::table('clients')->select('id', 'broker_id')->where('id', $file->client_id)->first();

        if($client->broker_id == $brokerId) {
          $authorized = true;
        }
      }
    }

    else {
      // if user is Client, get client ID
      $client = DB::table('clients')->select('id', 'broker_id')->where('user_id', $user['id'])->first();

      if($file->user_id == $user->id) {
        $authorized = true;
      }

      else if(!empty($file->client_id) && $file->client_id == $client->id) {
        // if user uploaded the file as the client
        $authorized = true;
      }

      else if(!empty($file->broker_id) && $file->broker_id == $client->broker_id) {
        // if broker who is assoiciated to the user via broker-client relationship, upload the file, allow to delete
        $authorized = true;
      }
    }

    // if user is authorized, remove file from s3 bucket and files_new table
    if($authorized) {
      $s3 = Storage::disk('s3');
      $s3->delete($file->directory);
      DB::table('files_new')->where('id', '=', $fileId)->delete();
    }
  }

    // This method can handle the request to return the S3 URL of the file i.e public files uploaded on s3 bucket for the broker i.e banner logo etc.
    // IF files are uploaded and stores as private, s3 url will NOT be accessible.
    public static function getFileUrl(Request $req) {
        $authorized = false;
        $fileId = $req->files_new_id;
        // get the file from 'file_news' table
        $file = DB::table('files_new')->select('url')->where('id', $fileId)->first();
        if(empty($file)) {
            return [
                'message' => 'Invalid file',
                'code' => 400
            ];
        }
        // get user data of requester
        $user = Helper::getUser();

        // if file being requested for it's url is not public , then throw error
        if($file->private == true) {
            return [
                'message' => 'Unable to access private file',
                'code' => 403
            ];
        }
        // should everyone get access to the public file?, if so can just return here.
        return [
            'data' => $file->url,
            'code' => 200
        ];
    }

    /*
        :: TESTING PURPOSE ::

        This method is only for testing purposes i.e to get 1 file
     */
    public static function get() {
        $user = Helper::getUser();
        // User must be admin
        if(!Helper::isAdmin($user)) {
            return [
                'message' => 'User is not authorized',
                'code' => 403
            ];
             return response()->json(["error" => 'User is not authorized.']);
        }

        $files = DB::table('files_new')->select('id','original_file_name')->first();

        return [
            'data' => $files,
            'code' => 200
        ];
    }


  // Below method is for downloading a file without authentication
  // MUST USE WITH CAUTION - WHY ARE YOU calling this?
  // Currently this is called from MAIL - since queue doesn't require JWT
  // BUt otherwise, you should be using the other download
  public static function downloadWithoutAuthentication($fileId, $downloadAs, $userId) {
    $authorized = false;

    // get the file from 'file_news' table
    $file = DB::table('files_new')->where('id', $fileId)->where('user_id', $userId)->first();
    if(empty($file)) {
      throw new Exception("Invalid file");
    }

    // if File exist in files_new table and user is authrorized,then download or return url for the user.
    if($file) {
      $s3 = Storage::disk('s3');

      // get contents of the file from s3 bucket..
      $fileContents = $s3->get($file->directory);

      // if we are downloading file as content, return now
      // otherwise, we save it to a public location and download
      if($downloadAs == 'content') {
        return $fileContents;
      }


      // NOT SUPPORTING DOWNLOAD

      // else {

      //   // make sure to create the public folder to store the temporary file on server side(will be delete after sending file to client)
      //   if(!File::exists(public_path() . "/uploads")) {
      //     File::makeDirectory(public_path() . "/uploads");
      //   }

      //   // set temporary file nae before sending file to the client.
      //   $tmpFileName = $userId . '-' . time() . "." . $file->extension;
      //   $filePath    = public_path() . '/uploads';
      //   $fullPath    = $filePath . '/' . $tmpFileName;

      //   // create file from S3 as tmp file in 'public' folder
      //   File::put($fullPath, $fileContents);

      //   // return file downaload and then delete the file from the server.
      //   return response()->download($fullPath)->deleteFileAfterSend(true);
      // }
    }
  }

}
