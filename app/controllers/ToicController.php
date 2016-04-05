<?php 

class ToicController extends BaseController {

	public function import_data_base () {
		ini_set('max_execution_time', 600000000);
		$data_word = json_decode(file_get_contents(public_path() . '/database/word.json'));
		$size = count($data_word);
		for ($i = 0; $i < $size; $i++) {
			$e = $data_word[$i];
			$w = $e->word;
			if ($w == '') {
				$w = $e->phonectic;
			}
			$word = array(
				"word_id" => $e->id_word,
				"subject_id" => $e->id_subject,
				"course_id" => 101,
				"word" => $w,
				"mean" => $e->mean
			);

			DB::table('toic')->insert($word);
		}

	}

	public function get_url_download () {
		ini_set('max_execution_time', 600000000);
		$auto = new AutoSearchController();
		$list_data = DB::table('toic')->get();
		$size = count($list_data);
		for ($i = 0; $i < $size; $i++) {
			$word = $list_data[$i]->word;
			if ($word == null || $word == '') {
				$word = $list_data[$i]->mean;
			}

			$list_url = $auto->crawler_url_image($word);
			if (count($list_url) > 0) {
				DB::table('toic')->where('word_id', $list_data[$i]->word_id)
				->update(array('url' => $list_url[0], 'sub_url' => $list_url[1]));
			}
		}
	}


}

?>