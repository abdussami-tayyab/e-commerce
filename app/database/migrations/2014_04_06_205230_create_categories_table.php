<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function($table)
		{
			$table->increments('catId', true);
			$table->integer('par_catId')->unsigned();
			$table->string('catName', 20);
			$table->timestamps();
		});
		Schema::table('categories', function($table)
		{
			$table->foreign('par_catId')->references('catId')->on('categories');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categories');
	}

}
