<?php 

class ConvertDataController extends BaseController {

	public $list_word;

	public function load_data_json ($file_name) {
		$file_path = public_path() . '/database/' . $file_name;
		try {
			$this->list_word = json_decode(file_get_contents($file_path));
			return $this->list_word;			
		} catch (Exception $e) {
			return array();
		}
	}

	public function import_data_base () {
		ini_set('max_execution_time', 600000000);
		$file_name = 'data.json';
		$data = $this->load_data_json($file_name);
		if (count($data) == 0) {
			echo 'Kiểm tra lại file' . $file_name . 'có tồn tại';
		} else {
			foreach ($data as $key => $value) {
				$word = array();
				$word['word_id'] = $value->id_word;
				$word['mean'] = $value->mean;
				$word['word'] = $value->word;
				$query = $value->word;
				DB::table('word_image')->insert($word);
			}
		}
	}

	public function convert_data () {
		ini_set('max_execution_time', 600000000);
		$list_word = [];
		for ($k = 1; $k < 8; $k++) {
			$file_name = '10100' . $k . '.json';
			$list_data = file_get_contents(public_path() . '/database/' . $file_name);
			$list_data = json_decode($list_data);
			$list_groups = $list_data->groups; 
			for ($i = 0; $i < count($list_groups); $i++) {
				$card = $list_groups[$i]->cards;
				for ($j = 0; $j < count($card); $j++) {
					$word = array(
						"id_word" => $card[$j]->id,
						"object_id" => $list_groups[$i]->id,
					    "word"    => $card[$j]->front,
					    // "phonetic" =>  $card[$j]->,
					    "mean"    => $card[$j]->back,
					    "example" => null,
					    "example_mean" => null,
					    "num_ef" => null,
					    "time_date" => null,
					    "next_time" => null,
					    "num_n" => null,
					    "num_i" => null,
					    "max_q" => null
					);
					$pos = strpos($card[$j]->front, ' ');
					if ($pos === false) {
						try {
							$word['phonectic'] = file_get_contents('http://103.253.145.165:8787/translate/' . $word['word']);						
							sleep(0.1);	
						} catch (Exception $e) {
							$word['phonectic'] = null;	
						}
					} else {
						$word['phonectic'] = null;
					}
					array_push($list_word, $word);
				}
			}
		}	
		echo json_encode($list_word);
	}
}

?>