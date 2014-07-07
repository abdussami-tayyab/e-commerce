<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::controller('users', 'UsersController');
Route::controller('categories', 'CategoriesController');
Route::controller('products', 'ProductsController');

Route::get('/registration/verify/{actCode}', function($actCode)
{
	$user = DB::table('users')->where('actCode', $actCode)->first();
	if($user == true) {						// Activated!
		$user2 = User::find($user->id)->where('actCode', '=', $actCode)->first();
		$user2->actCode = '1';
		$user2->save();

        return Redirect::to('users/login')->with('message', 'Activated! Welcome =)');
	} else {
		return Redirect::to('users/login')->with('message', 'Activation Code is wrong');
	}
});


/*
|---------------------------------------------------------------------------
| Facebook Shit
|---------------------------------------------------------------------------
|
*/

Route::get('/loginFacebook', function()
{
    $data = array();
 
    if (Auth::check()) {
        $data = Auth::user();
    }
    return Redirect::to('login/fb');
//    return View::make('user', array('data'=>$data));
});
 
Route::get('logout', function() {
    Auth::logout();
    return Redirect::to('/users/register');
});

Route::get('login/fb', function() {
    $facebook = new Facebook(Config::get('facebook'));
    $params = array(
        'redirect_uri' => url('/login/fb/callback'),
        'scope' => 'user_birthday,email,user_photos',
    );
    return Redirect::to($facebook->getLoginUrl($params));
});

Route::get('login/fb/callback', function() {
    $code = Input::get('code');
    if (strlen($code) == 0) return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');
 
    $facebook = new Facebook(Config::get('facebook'));
    $uid = $facebook->getUser();
 
    if ($uid == 0) return Redirect::to('/')->with('message', 'There was an error');
 
    $me = $facebook->api('/me');
 
    $profile = Profile::whereUid($uid)->first();
    if (empty($profile)) {

        $user = new User;
        $user->firstname = $me['first_name'];
        $user->lastname = $me['last_name'];
        $user->email = $me['email'];
        $user->password = "";
        $user->actCode = 1;
        $user->picture = 'https://graph.facebook.com/'.$me['username'].'/picture?type=large';

        $user->save();

        $profile = new Profile();
        $profile->uid = $uid;
        $profile->username = $me['username'];
        $profile = $user->profiles()->save($profile);
    }
 
    $profile->access_token = $facebook->getAccessToken();
    $profile->save();
 
    $user = $profile->user;
 
    Auth::login($user);
 
    return Redirect::to('/users/dashboard')->with('message', 'Logged in with Facebook');
});