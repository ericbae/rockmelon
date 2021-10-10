<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinkGroupController;
use App\Http\Controllers\UserController;

// Route::get('/', [LinkGroupController::class, 'getHome']);

Route::get('/', function() {
  if(auth()->check()) {
    return redirect('home');
  }

  else {
    return view('home');
  }
});

Route::post('contact-us',                                     [CommonController::class, 'postContactUs']);
Route::get('signup/{email}',                                  [HomeController::class, 'signup']);
Route::get('try',                                             [LinkGroupController::class, 'getTry']);
Route::post('try',                                            [LinkGroupController::class, 'postTry']);
Route::get('rl/{identifier}',                                 [LinkGroupController::class, 'getLinkGroup']);
Route::view('api',                                            'api');
Route::get('product-hunt',                                    [LinkGroupController::class, 'getProductHunt']);

Route::group([
  'prefix' => 'api',
], function() {
  Route::post('link-group/create',                            [LinkGroupController::class, 'postCreateViaApi']);
});

Route::group(['prefix' => 'auth'], function() {
  Route::view('login',                                        'auth/login');
  Route::view('forgot-password',                              'auth/forgot-password');
  Route::post('login',                                        [AuthController::class, 'postLogin']);
  Route::get('logout',                                        [AuthController::class, 'getLogout']);
  Route::post('forgot-password',                              [AuthController::class, 'postForgotPassword']);
  Route::view('register',                                     'auth/register');
  Route::post('register',                                     [AuthController::class, 'postRegister']);
  Route::get('registered',                                    [AuthController::class, 'getRegistered']);
  Route::get('reset-password/{key}',                          [AuthController::class, 'getResetPassword']);
  Route::post('reset-password',                               [AuthController::class, 'postResetPassword']);
  Route::post('redirect-url',                                 [AuthController::class, 'postRedirectUrl']);
  Route::get('confirm-registration',                          [AuthController::class, 'getConfirmRegistration']);
  Route::get('confirm-email/{key}',                           [AuthController::class, 'getConfirmEmail']);
});


Route::group([
  'middleware' => ['is-logged-in']
], function() {  
  
  Route::get('home',                                  [UserController::class, 'getHome']);
  
  Route::group([
    'prefix' => 'user',
  ], function() {
      Route::get('account',                           [UserController::class, 'getAccount']);
      Route::post('account',                          [UserController::class, 'postAccount']);
      Route::post('settings',                         [UserController::class, 'postSettings']);
  });

  Route::group([
    'prefix' => 'auth',
  ], function() {
    Route::post('feedback',                           [AuthController::class, 'postFeedback']);
    Route::get('logout',                              [AuthController::class, 'getLogout']);
  });

  Route::group([
    'prefix' => 'link-group',
  ], function() {
    Route::get('create',                              [LinkGroupController::class, 'getCreate']);
    Route::get('data',                                [LinkGroupController::class, 'getData']);
  });

});


Route::get('mailable', function () {
  return new \App\Mail\InviteMail(
   \App\Models\User::first(),
   \App\Models\Member::first(),
   \App\Models\Group::first()
  );
});


// Route::get('mailable', function () {
//  $user    = \App\Models\User::inRandomOrder()->first();
//  $domains = \App\Models\Domain::where('user_id', $user->id)->get();
//   return new \App\Mail\Newsletter($user, $domains);
// });

if(env('APP_ENV') == 'local') {
  DB::listen(
    function ($sql) {
      // $sql is an object with the properties:
      //  sql: The query
      //  bindings: the sql query variables
      //  time: The execution time for the query
      //  connectionName: The name of the connection

      // To save the executed queries to file:
      // Process the sql and the bindings:
      foreach ($sql->bindings as $i => $binding) {
          if ($binding instanceof \DateTime) {
              $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
          } else {
              if (is_string($binding)) {
                  $sql->bindings[$i] = "'$binding'";
              }
          }
      }

      // Insert bindings into query
      $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);

      $query = vsprintf($query, $sql->bindings);

      // Save the query to file
      $logFile = fopen(
          storage_path('logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '_query.log'),
          'a+'
      );
      fwrite($logFile, date('Y-m-d H:i:s') . ': ' . $query . PHP_EOL);
      fclose($logFile);
    }
  );
}