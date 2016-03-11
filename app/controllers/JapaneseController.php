<?php 

class JapaneseController extends BaseController {
	public $path;

	public function __construct () {
		$this->path = public_path() . '/database/japanese/';
	}

	public function convert_data () {
		$data_word = json_decode(file_get_contents($this->path . 'word.json'));
		$size = count($data_word);
		$result = array();
		for ($i = 0; $i < $size; $i++) {
			$e = $data_word[$i];
			$word = array (
				"id_word" => $e->id,
			    "id_subject" => $e->groupId,
			    "id_course" => null,
			    "word" => $e->ja,
			    "mean" => $e->vi,
			    "example" => null,
			    "example_mean" => null,
			    "num_ef" => null,
			    "time_date" => null,
			    "next_time" => null,
			    "num_n" => null,
			    "num_i" => null,
			    "max_q" => null,
			    "phonectic" => $e->hira
			);
			array_push($result, $word);
		}

		echo json_encode($result);
	}

	public function convert_group () {
		$data_word = json_decode(file_get_contents($this->path . 'group2.json'));
		$size = count($data_word);
		for ($i = 0; $i < $size; $i++) {
			$data_word[$i]->id_subject = 104000000 + $data_word[$i]->id_subject - 73;
		}

		echo json_encode($data_word);
	}

	public function import_data () {
		ini_set('max_execution_time', 600000000);
		for ($k = 1; $k < 3; $k++) {
			$data_word = json_decode(file_get_contents($this->path . '10400000' . $k . '/word.json'));
			$size = count($data_word);
			for ($i = 0; $i < $size; $i++) {
				$e = $data_word[$i];
				$word = array(
					"word_id" => $e->id_word,
					"subject_id" => $e->id_subject,
					"course_id" => $e->id_course,
					"word" => $e->word,
					"mean" => $e->mean
				);

				DB::table('japanese')->insert($word);
			}
		}
	}

	public function get_url_download () {
		ini_set('max_execution_time', 600000000);
		$auto = new AutoSearchController();
		$list_data = DB::table('japanese')->get();
		$size = count($list_data);
		for ($i = 0; $i < $size; $i++) {
			$word = $list_data[$i]->word;
			if ($word == null || $word == '') {
				$word = $list_data[$i]->mean;
			}
			$list_url = $auto->crawler_url_image($word);
			DB::table('japanese')->where('word_id', $list_data[$i]->word_id)
			->update(array('url' => $list_url[0], 'sub_url' => $list_url[1]));
		}
	}


}

?>