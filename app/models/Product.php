<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Product extends Eloquent {

	public static $rules = array(
	    'name'=>'required|min:2',
	    'price'=>'required|integer',
	    'count'=>'required|integer',
	    'files'=>'required'
    );

	public static function getAllCategories() {
	    return Category::whereNull('par_catId')->get();
	}

	public static function getAllProducts()
	{
		return Product::get();
	}

	public static function updateTable($attr, $newVar, $hidden)
	{
		$cat = DB::table('products')->where('name', $hidden)
									->update(array($attr=> $newVar));
	}

    public static $editRules = array(
	);

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'products';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

}