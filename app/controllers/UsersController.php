<?php

// app/controllers/UserController.php

class UsersController extends BaseController
{
	protected $layout = 'layouts.main';
	protected $layout2 = 'layouts.loggedin';

	public function __construct()
	{
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('auth', array('only'=>array('getDashboard')));
		$this->afterFilter('loginMasla', array('only'=>array('getLogin')));
	}

	/**
	 * 
	 * users will register through this func
	 * 
	 */
	public function getRegister()
	{
		$this->layout->content = View::make('users.register');
	}

	/**
	 * Create User
	 **/
	public function postCreate()
	{
		$validator = Validator::make(Input::all(), User::$rules);
		if($validator->passes()) {
			$user = new User;
			$user->firstname = Input::get('firstname');
		    $user->lastname = Input::get('lastname');
		    $user->email = Input::get('email');
		    $user->password = Hash::make(Input::get('password'));
			$string=str_random(20);
			$user->actCode = $string;

			//Image
			$file = Input::file('file');
			$destinationPath = 'uploads/';
			$filename = $user->destinationPath . $user->id . '_' . $file->getClientOriginalName();
			//$extension =$file->getClientOriginalExtension(); //if you need extension of the file
			$uploadSuccess = Input::file('file')->move($destinationPath, $filename);
			 
			if( $uploadSuccess ) {
				$user->picture = $filename;
			    $user->save();
			    Mail::send('mails.welcome', array('firstname'=>Input::get('firstname'), 'actCode'=>$string), function($message) {
			    	$message->to(Input::get('email'), Input::get('firstname').' '.Input::get('lastname'))->subject('Welcome to Amazon!');
			    });
		        return Redirect::to('users/login')->with('message', 'Thanks for registering!');
			}
		} else {
	        return Redirect::to('users/register')->with('message', 'The following errors occurred')
	        									 ->withErrors($validator)
	        									 ->withInput();
		}
	}

	public function getLogin()
	{
		$this->layout->content = View::make('users.login');
	}

	public function postSignin()
	{
		if (Auth::attempt(array('email'=>Input::get('email'), 'password'=>Input::get('password')))) {

			if(Auth::user()->actCode != '1') {
				return Redirect::to('users/login')->with('message', 'Your account is not activated yet!');
			} else {
				return Redirect::to('users/dashboard')->with('message', 'You are now logged in!');
			}
		} else {
			return Redirect::to('users/login')
			        ->with('message', 'Your username/password combination was incorrect')
			        ->withInput();
		}
	}

	public function getDashboard()
	{
		return View::make('layouts.loggedin', array('content' => View::make('users.dashboard')));
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::to('users/login')->with('message', 'You are now logged out!');
	}

	public function postEdit()
	{
		$validator = Validator::make(Input::all(), User::$editRules);
		if($validator->passes()) {
			$user = new User();
			$user = User::find(Auth::user()->id);
			if(Input::get('firstname') != "")
				$user->firstname = Input::get('firstname');
			if(Input::get('lastname') != "")
				$user->lastname = Input::get('lastname');
			if(Input::get('email') != "")
				$user->email = Input::get('email');
		    $user->save();

			//Image
			if(Input::hasFile('file')) {
				$file = Input::file('file');
				$destinationPath = 'uploads/users/';
				$filename = $user->id . '_' . $file->getClientOriginalName();
				//$extension =$file->getClientOriginalExtension(); //if you need extension of the file
				$uploadSuccess = Input::file('file')->move($destinationPath, $filename);

				if($uploadSuccess) {
					$user->picture = $filename;
				    $user->save();
			        return Redirect::to('users/dashboard')->with('message', 'Your profile has been successfully been updated!');
				}
			}
	        return Redirect::to('users/dashboard')->with('message', 'Your profile has been successfully been updated!');
		} else {
			return Redirect::to('users/dashboard')->with('message', 'The following errors occurred')
												  ->withErrors($validator)
												  ->withInput();
		}
	}
}