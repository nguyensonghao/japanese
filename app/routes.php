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

Route::get('/', function()
{
	return View::make('hello');
});

Route::controller('search', 'SearchImageController');

Route::get('search', 'SearchImageController@get_search');

Route::post('save-url-image', 'SearchImageController@action_save_url_image');

Route::get('auto-search', 'SearchImageController@action_auto_search');

Route::post('search-image', 'SearchImageController@action_search_url_image');

Route::get('fix-error', 'SearchImageController@action_fix_error');

Route::get('download-image', 'SearchImageController@action_download_image');

Route::get('list-image', 'SearchImageController@action_show_list_image');

Route::get('resize-image', 'SearchImageController@resize_list_image');

Route::controller('auto', 'AutoSearchController');

Route::get('create-database', 'AutoSearchController@create_image_to_database');

Route::get('crawler', 'AutoSearchController@crawler_image_download');

Route::get('crawler-url-download', 'AutoSearchController@get_list_url_download');

Route::controller('convert', 'ConvertDataController');

Route::get('convert', 'ConvertDataController@import_data_base');

Route::get('test', function () {
	ini_set('max_execution_time', 600000000);
	$file_name = public_path() . '/database/data.json';
	$list = json_decode(file_get_contents($file_name));
	$size = count($list);
	$result = array();
	for ($i = 0; $i < $size; $i++) {
		if ($list[$i]->id_course == 101007) {
			array_push($result, $list[$i]);
		}
	}

	echo json_encode($result);
});

Route::get('demo', function () {
	ini_set('max_execution_time', 600000000);
	$file_name = public_path() . '/database/data.json';
	$list = json_decode(file_get_contents($file_name));
	$size = count($list);
	$index = 0;
	for ($i = 0; $i < $size; $i++) {
		$word_id = $list[$i]->id_word;
		if ($word_id <= 101001558) {
			$list[$i]->id_course = 101001;
			++$index;
		} else if ($word_id > 101001558 && $word_id <= 101002558) {
			$list[$i]->id_course = 101002;
		} else if ($word_id > 101002558 && $word_id <= 101002956) {
			$list[$i]->id_course = 101003;
		} else if ($word_id > 101002956 && $word_id <= 101003183) {
			$list[$i]->id_course = 101004;
		} else if ($word_id > 101003183 && $word_id <= 101003483) {
			$list[$i]->id_course = 101005;
		} else if ($word_id > 101003483 && $word_id <= 101004358) {
			$list[$i]->id_course = 101006;
		} else {
			$list[$i]->id_course = 101007;
		}
	}

	echo json_encode($list);
});

Route::controller('japanese', 'JapaneseController');

Route::get('convert-data', 'JapaneseController@convert_data');

Route::get('convert-subject', 'JapaneseController@convert_group');

Route::get('japanese-import', 'JapaneseController@import_data');

Route::get('japanese-get-url', 'JapaneseController@get_url_download');

Route::get('test1', function () {
	echo DB::table('word_image')->where('status', -2)->count();
});