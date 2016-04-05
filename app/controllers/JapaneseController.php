<?php 

class JapaneseController extends BaseController {
	public $path;

	public function __construct () {
		$this->path = public_path() . '/database/japanese/';
	}

	public function convert_data () {
		$data_word = json_decode(file_get_contents($this->path . 'soumatomen2.json'));
		$size = count($data_word);
		$result = array();
		$chap = $data_word->chapters;
		// $k = 4966;
		// $word_id = 104000133;
		$k = 2790;
		$word_id = 104000078;
		foreach ($chap as $key => $value) {
			$word = $value->words;
			foreach ($word as $key => $value) {
				$w = $value->kanji;
				if($w == '') {
					$w = $value->word;
				}
				$word = array (
					"id_word" => 104 * 1000000 + $k,
				    "id_subject" => $word_id,
				    "id_course" => 104000003,
				    "word" => $w,
				    "mean" => $value->mean,
				    "example" => null,
				    "example_mean" => null,
				    "num_ef" => null,
				    "time_date" => null,
				    "next_time" => null,
				    "num_n" => null,
				    "num_i" => null,
				    "max_q" => null,
				    "phonectic" => $value->word
				);
				++$k;
				array_push($result, $word);
			}
			++$word_id;
		}
		foreach ($data_word as $key => $value) {
			
		}

		echo json_encode($result);
	}

	public function convert_group () {
		$data_word = json_decode(file_get_contents($this->path . 'soumatomen3.json'));
		$data_word = $data_word->chapters;
		$k = 104000133;
		$result = [];
		foreach ($data_word as $key => $value) {
			$group = array(
				"id" => $k,
			    "id_course" => 104000004,
			    "name" => $value->name,
			    "mean" => null,
			    "total" => count($value->words),
			    "num_word" => null,
			    "time_date" => null
			);
			++$k;
			array_push($result, $group);
		}

		echo json_encode($result);
	}

	public function import_data () {
		ini_set('max_execution_time', 600000000);
		for ($k = 3; $k < 5; $k++) {
			$data_word = json_decode(file_get_contents($this->path . '10400000' . $k . '/word.json'));
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
					"course_id" => $e->id_course,
					"word" => $w,
					"mean" => $e->mean
				);

				DB::table('soumato')->insert($word);
			}
		}
	}

	public function get_url_download () {
		ini_set('max_execution_time', 600000000);
		$auto = new AutoSearchController();
		$list_data = DB::table('soumato')->where('url', '')->get();
		$size = count($list_data);
		for ($i = 0; $i < $size; $i++) {
			$word = $list_data[$i]->word;
			if ($word == null || $word == '') {
				$word = $list_data[$i]->mean;
			}

			$list_url = $auto->crawler_url_image($word);
			if (count($list_url) > 0) {
				DB::table('soumato')->where('word_id', $list_data[$i]->word_id)
				->update(array('url' => $list_url[0], 'sub_url' => $list_url[1]));
			}
		}
	}

	public function download_image () {
		ini_set('max_execution_time', 600000000);
		$auto = new AutoSearchController();
		$data_base = DB::table('japanese')->get();
		foreach ($data_base as $key => $value) {
			$url = $value->url;
			$sub_url = $value->sub_url;
			$course_id = $value->course_id;
			if ($auto->download_and_crop_image('roots/' . $course_id, $value->word_id . '.jpg', $url, 254, 334)) {
				DB::table('japanese')->where('word_id', $value->word_id)
				->update(array('status' => 1));
				if ($auto->resize_image('roots/' . $course_id, 'thumbnails/' . $course_id, $value->word_id . '.jpg', 254, 334)) {
					DB::table('japanese')->where('word_id', $value->word_id)
				    ->update(array('status' => 2));
				} else {
					DB::table('japanese')->where('word_id', $value->word_id)
				    ->update(array('status' => -3));
				}
			} else {
				if ($auto->download_and_crop_image('roots/' . $course_id, $value->word_id . '.jpg', $url, 254, 334)) {
					DB::table('japanese')->where('word_id', $value->word_id)
				    ->update(array('status' => 1));
				    if ($auto->resize_image('roots/' . $course_id, 'thumbnails/' . $course_id, $value->word_id . '.jpg', 254, 334)) {
						DB::table('japanese')->where('word_id', $value->word_id)
					    ->update(array('status' => 2));
					} else {
						DB::table('japanese')->where('word_id', $value->word_id)
					    ->update(array('status' => -3));
					}
				} else {
					DB::table('japanese')->where('word_id', $value->word_id)
				    ->update(array('status' => -2));
				}
			}
		}
	}

}

?>