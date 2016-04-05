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

Route::get('subject', 'AutoSearchController@crawler_image_download_subject');

Route::get('crawler-url-download', 'AutoSearchController@get_list_url_download');

Route::controller('convert', 'ConvertDataController');

Route::get('convert', 'ConvertDataController@import_data_base');

Route::controller('japanese', 'JapaneseController');

Route::get('convert-data', 'JapaneseController@convert_data');

Route::get('convert-subject', 'JapaneseController@convert_group');

Route::get('japanese-import', 'JapaneseController@import_data');

Route::get('japanese-get-url', 'JapaneseController@get_url_download');

Route::get('japanese-crawler', 'JapaneseController@download_image');

Route::get('demo', 'AutoSearchController@test');

Route::controller('korea', 'KoreaControler');

Route::get('korea-getdata', 'KoreaControler@get_data_from_website');

Route::get('korea-test', 'KoreaControler@test');

Route::get('korea-convert', 'KoreaControler@convert_data');

Route::get('korea-import', 'KoreaControler@import_data');

Route::get('korea-get-url', 'KoreaControler@get_url_download');

Route::get('korea-get-group', 'KoreaControler@get_group_from_website');

Route::get('korea-test3', 'KoreaControler@test3');

Route::controller('toic', 'ToicController');

Route::get('toic-import', 'ToicController@import_data_base');

Route::get('toic-get-url', 'ToicController@get_url_download');

Route::get('phonetic', 'KoreaControler@get_phonectic');

Route::get('phonetic2', 'KoreaControler@get_phonectic_2');

Route::get('get-phonetic/{query}', 'KoreaControler@get_phonetic_with_google');

Route::controller('chinese', 'ChineseController');

Route::get('chinese-getdata', 'ChineseController@get_data');

Route::get('kanji', 'ChineseController@get_kanji');

Route::get('kanji-test', 'ChineseController@get_kanji_test');

Route::get('chinese-subject', 'ChineseController@get_subject');

Route::get('chinese-list-subject', 'ChineseController@get_list_subject');

Route::get('chinese-import', 'ChineseController@import_data');

Route::get('chinese-get-url', 'ChineseController@get_url_download');

Route::get('test', 'KoreaControler@test1');

Route::get('download-subject', 'AutoSearchController@download');

Route::get('demo2', function () {
	$list_data = file_get_contents(public_path() . '/database/chinese/subject 2.json');
	$list_data = json_decode($list_data);
	$list_word = file_get_contents(public_path() . '/database/chinese/215thu.json');
	$list_word = json_decode($list_word);
	for ($i = 0; $i < count($list_data); $i++) {
		$k = 0;
		$subject_id = $list_data[$i]->id;
		for ($j = 0; $j < count($list_word); $j++) {
			if ($subject_id == $list_word[$j]->id_subject) {
				$k++;
			}
		}
		$list_data[$i]->total = $k;
	}

	echo json_encode($list_data);
});

  