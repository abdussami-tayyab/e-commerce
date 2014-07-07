<?php

// app/controllers/CategoriesController.php

class CategoriesController extends BaseController{
	protected $layout = 'layouts.loggedin';
	protected static $restful = true;

	public function __construct()
	{
		$this->afterFilter('loginMasla', array('only'=>array('getLogin')));
	}

	public function getIndex()
	{
		return View::make('categories.dashboard');
	}

	public function getDashboard()
	{
		$allCats = Category::whereNull('par_catId')->get();
		$this->layout->content = View::make('categories.dashboard')->with('allCats', $allCats);
	}

	public function postAdd()
	{
		$validator = Validator::make(Input::all(), Category::$rules);
		if($validator->passes()) {
			$cat = new Category();
			$cat->catName = Input::get('category');
			$cat->save();
	        return Redirect::to('categories/dashboard')->with('message', 'Category has been added!');
		} else {
			return Redirect::to('categories/dashboard')->with('message', 'The following errors occurred')
												  ->withErrors($validator)
												  ->withInput();
		}
	}

	public function postAddSub()
	{
		$validator = Validator::make(Input::all(), Category::$SCRules);
		if($validator->passes()) {
			$parCategory = Input::get('SCParent');
			$res = DB::table('categories')->where('catName', Input::get('SCParent'))->first();

			$cat = new Category();
			$cat->catName = Input::get('SCText');
			$cat->par_catId = $res->catId;
			$cat->save();

	        return Redirect::to('categories/dashboard')->with('message', 'Sub-Category successfully added');
		} else {
			return Redirect::to('categories/dashboard')->with('message', 'The following errors occurred')
												  ->withErrors($validator)
												  ->withInput();
		}
	}

	public function postEdit()
	{
		$validator = Validator::make(Input::all(), Category::$rules);
		if($validator->passes()) {
			$catcatName = Input::get('category');
			$cat = DB::table('categories')->where('catName', Input::get('hidden'))
										  ->update(array('catName'=> $catcatName));

	        return Redirect::to('categories/dashboard')->with('message', 'Category has been updated!');
		} else {
			return Redirect::to('categories/dashboard')->with('message', 'The following errors occurred')
												  ->withErrors($validator)
												  ->withInput();
		}
	}

	public function postDelete()
	{
		$res = DB::table('categories')->where('catName', '=', Input::get('asalDel'))->delete();
		$str = Input::get('asalDel') . ' have been deleted successfully!';
        return Redirect::to('categories/dashboard')->with('message', $str);
	}

}

