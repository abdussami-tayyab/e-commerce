<?php

// app/controllers/ProductsController.php

class ProductsController extends BaseController {
	protected $layout = 'layouts.loggedin';
	protected static $restful = true;
	public static $rules = array(
	    'name'=>'required|alpha|min:2',
	    'price'=>'required|integer',
	    'count'=>'required|integer',
	);

	public function __construct()
	{
		$this->afterFilter('loginMasla', array('only'=>array('getLogin')));
	}

	public function getIndex()
	{
		$allCats=Product::getAllCategories();
		return View::make('products.dashboard')->with('allCats', $allCats);
	}

	public function getDashboard()
	{
		$allCats = Product::getAllCategories();
		$allProducts = Product::getAllProducts();
		$this->layout->content = View::make('products.dashboard')->with('allCats', $allCats)->with('allProducts', $allProducts);
	}

	public function postAdd()
	{
		$i = 1;
		$allCats=Product::getAllCategories();

		$validator = Validator::make(Input::all(), Product::$rules);
		if($validator->passes()) {
			$product = new Product();
			$res = DB::table('categories')->select('catId')->where('catName', Input::get('hiddenCategory'))->first();
			$product->categoryId = $res->catId;
			$product->name = Input::get('name');
			$product->price = Input::get('price');
			$product->count = Input::get('count');
			$imagesStr = "";

			if(Input::hasFile('files')) {
				$files = Input::file('files');
				$destinationPath = 'public/uploads/products/';
				foreach ($files as $file) {
					$str = $i . '_' . Input::get('name') . '.jpg';
					$uploadSuccess = $file->move($destinationPath, $str);
					if( $uploadSuccess ) {
						$i = $i + 1;
						$imagesStr = $imagesStr . $str . ',';
					}
				}

				$product->images = $imagesStr;
			}

			$product->save();

	        return Redirect::to('products/dashboard')->with('message', 'Product has been added!');
		} else {
			return Redirect::to('products/dashboard')->with('message', 'The following errors occurred')
												  ->withErrors($validator)
												  ->withInput();
		}
	}

	public function postEdit()
	{
		$i = 1;
		$partsA = $explodedStr = "";
		$parts = array();
		$validator = Validator::make(Input::all(), Product::$editRules);
		if($validator->passes()) {
			$prodName = Input::get('name');
			if($prodName != "") {
				Product::updateTable('name', $prodName, Input::get('hidden'));
			}
			if(Input::get('count') != "") {
				Product::updateTable('count', Input::get('count'), Input::get('hidden'));
			}
			if(Input::get('price') != "") {
				Product::updateTable('price', Input::get('price'), Input::get('hidden'));
			}

			if(Input::hasFile('files')) {
				$prodName = Input::get('hidden');
				//product name get karr
				$prodRow = Product::where('name', $prodName)->get();
				if($prodRow[0]->images == "")
					$i = 1;
				else {
					$explodedStr = $prodRow[0]->images;
					$parts = explode(',', $explodedStr);
					$partsA = $parts[count($parts) - 2][0];
					$i = $partsA + 1;
				}

				$imagesStr = "";
				$files = Input::file('files');
				$destinationPath = 'public/uploads/products/';
				foreach ($files as $file) {
					$str = $i . '_' . Input::get('hidden') . '.jpg';
					$uploadSuccess = $file->move($destinationPath, $str);
					if( $uploadSuccess ) {
						$i = $i + 1;
						$imagesStr = $imagesStr . $str . ',';
					}
				}

				$finalStr = $explodedStr . $imagesStr;

				Product::where('name', $prodName)->update(array('images'=>$finalStr));
			}

	        return Redirect::to('products/dashboard')->with('message', $prodName);
		} else {
			return Redirect::to('products/dashboard')->with('message', 'The following errors occurred')
												  ->withErrors($validator)
												  ->withInput();
		}
	}

	public function postDelete()
	{
		$res = DB::table('products')->where('name', Input::get('asalDel'))->delete();
		if($res)
		{
			$str = Input::get('asalDel') . ' has been deleted successfully!';
	        return Redirect::to('products/dashboard')->with('message', $str);
	    }
        return Redirect::to('products/dashboard')->with('message', 'Not deleted');
	}

	public function getFetchSubCategory()
	{
		$input = Input::get('option');
		$cat = Category::where('catName', '=', $input)->get();
		$subCategs = Category::where('par_catId', '=', $cat[0]->catId)->get();
		return Response::json($subCategs, 200);
	}

	public function getFetchImages()
	{
		$input = Input::get('option');
		$images = Product::where('name', '=', $input)->get();
		return Response::json($images, 200);
	}

	public function getDeleteImage()
	{
		//product name get karr
		$addr = Input::get('addr');
		$first = explode('_', $addr);
		$second = explode('.', $first[1]);

		//original address get karr
		$str = Product::where('name','=', $second[0])->get();

		//original main se nikal
		$addr = $addr . ",";
		$addr = str_replace($addr, '', $str[0]->images);

		Product::where('name', $second[0])
			   ->update(array('images' => $addr));

		//update
		return Redirect::to('products/dashboard')->with('message', 'Image has successfully been deleted!');
	}
}
