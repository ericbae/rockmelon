<?php 

namespace App\Services;
use Log, File, Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService {

  public function uploadToS3($path, $content, $accessType = 'public') {
		$s3 = Storage::disk('s3');

		if($s3->put($path, $content, $accessType)) {
			return $s3->url($path);
		}
  }

  public function getTemporaryUrl($path, $availability) {
    $s3 = Storage::disk('s3');
    return $s3->temporaryUrl($path, $availability);
  }

  public function getFileSize($path) {
		$s3 = Storage::disk('s3');
		return $s3->size($path);
  }


  // Function to delete the file from s3 bucket as well from the files table
  // https://hellocard.s3.ap-southeast-2.amazonaws.com/local/3/14/tarot-1560905394.png
  public function delete($params) {
  	if($params['storage'] == 's3') {
			$s3   = Storage::disk('s3');
			$path = str_replace('https://' . env('AWS_S3_BUCKET') . '.s3.ap-southeast-2.amazonaws.com/', '', env('APP_ENV') . '/' . $params['url']);
	    $s3->delete($path);
	  }

	  else {
			Storage::disk('local')->delete('img/projects/' . $params['url']);
	  }
  }

  public function optimizeImage($file, $size, $quality) {
  	return Image::make($file)->resize(null, $size, function ($constraint) {
      $constraint->aspectRatio();
      $constraint->upsize();
    })->encode('jpg', $quality);
  }

  public function encodeImageToJpg($file) {
  	return Image::make($file)->encode('jpg', 100);
  }


  public function uploadTest() {
  	$s3 = Storage::disk('s3');
  	$s3->put('ajax-loader.gif', File::get(public_path('img/ajax-loader.gif')), 'private');
  	echo $s3->url('ajax-loader.gif');
  }

  public function getFilePath($url) {
    $arr = explode('?', $url);
    return str_replace('https://' . env('AWS_S3_BUCKET') . '.s3.ap-southeast-2.amazonaws.com/', '', $arr[0]);
  }

}