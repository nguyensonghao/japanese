<?php 

class KoreaControler extends BaseController {

	public function get_data_from_website () {
		$url = 'http://tuvungtienghan.com/tu-vung/chi-tiet/nhung-cap-tu-trai-nghia-nhau-phan-hai-178.html';
		libxml_use_internal_errors(true);
		$dom = new DOMDocument('1.0', 'utf-8');
		libxml_clear_errors();
		$dom->loadHTMLFile(trim($url));
		$xpath = new DOMXPath($dom);
		$item = $xpath->query('.//div[@class="list_show"]/div[@class="voca_list voca_list_show"]');
		$list_vn = array();
		$list_korea = array();
		$list_word = array();
		$vn = $xpath->query('.//div[@class="voca_childrenb"]');
		$korea = $xpath->query('.//div[@class="voca_children"]');
		foreach ($vn as $key => $value) {
			array_push($list_vn, $value->nodeValue);
		}

		foreach ($korea as $key => $value) {
			array_push($list_korea, $value->nodeValue);
		}

		for($i = 0; $i < count($list_vn); $i++) {
			$word = array (
				"id_word" => $i + 1,
			    "id_subject" => $i + 1,
			    "id_course" => 103000001,
			    "word" => $list_korea[$i],
			    "mean" => $list_vn[$i],
			    "example" => null,
			    "example_mean" => null,
			    "num_ef" => null,
			    "time_date" => null,
			    "next_time" => null,
			    "num_n" => null,
			    "num_i" => null,
			    "max_q" => null,
			    "phonectic" => null
			);
			array_push($list_word, $word);
		}

		echo json_encode($list_word);
	}

	public function get_group_from_website () {
		ini_set('max_execution_time', 600000000);
		$url = 'http://tuvungtienghan.com/chuyen-nganh/tu-vung-tieng-han-quoc-chuyen-nganh.html';
		// $url = 'http://tuvungtienghan.com/tu-vung/danh-sach-tu-vung-tieng-han-quoc.html';
		libxml_use_internal_errors(true);
		$dom = new DOMDocument('1.0', 'utf-8');
		libxml_clear_errors();
		$dom->loadHTMLFile(trim($url));
		$xpath = new DOMXPath($dom);
		$item = $xpath->query('.//ul[@class="listz_two"]/li');
		$list_link = array();
		$k = 103000111;
		foreach ($item as $key => $value) {
			$link = $xpath->query('.//a', $value)->item(0)->nodeValue;
			$group = array(
				"id" => $k,
			    "id_course" => 103000002,
			    "name" => null,
			    "mean" => $link,
			    "total" => 18,
			    "num_word" => null,
			    "time_date" => null
			);
			array_push($list_link, $group);
			++$k;
		}

		echo json_encode($list_link);
	}

	public function test () {
		ini_set('max_execution_time', 600000000);
		$url = 'http://tuvungtienghan.com/chuyen-nganh/tu-vung-tieng-han-quoc-chuyen-nganh.html';
		libxml_use_internal_errors(true);
		$dom = new DOMDocument('1.0', 'utf-8');
		libxml_clear_errors();
		$dom->loadHTMLFile(trim($url));
		$xpath = new DOMXPath($dom);
		$item = $xpath->query('.//ul[@class="listz_two"]/li');
		$list_word = array();
		$list_link = array();
		foreach ($item as $key => $value) {
			$link = $xpath->query('.//a/@href', $value)->item(0)->nodeValue;
			array_push($list_link, $link);
		}
		$k = 0;

		for ($i = 0; $i < count($list_link); $i++) {
			$link = $list_link[$i];
			libxml_use_internal_errors(true);
			$dom = new DOMDocument('1.0', 'utf-8');
			libxml_clear_errors();
			$dom->loadHTMLFile(trim($link));
			$xpath = new DOMXPath($dom);
			$item = $xpath->query('.//div[@class="list_show"]/div[@class="voca_list voca_list_show"]');
			$list_vn = array();
			$list_korea = array();
			$vn = $xpath->query('.//div[@class="voca_childrenb"]');
			$korea = $xpath->query('.//div[@class="voca_children"]');
			foreach ($vn as $key => $value) {
				array_push($list_vn, $value->nodeValue);
			}

			foreach ($korea as $key => $value) {
				array_push($list_korea, $value->nodeValue);
			}

			for($j = 0; $j < count($list_vn); $j++) {
				$word = array (
					"id_word" => $k,
				    "id_subject" => $i,
				    "id_course" => 103000001,
				    "word" => $list_korea[$j],
				    "mean" => $list_vn[$j],
				    "example" => null,
				    "example_mean" => null,
				    "num_ef" => null,
				    "time_date" => null,
				    "next_time" => null,
				    "num_n" => null,
				    "num_i" => null,
				    "max_q" => null,
				    "phonectic" => null
				);
				array_push($list_word, $word);
				$k++;
			}
		}

		echo json_encode($list_word);

	}

	public function convert_data () {
		$path = public_path() . '/database/korea/';
		for ($i = 2; $i < 3; $i++) {
			$file_name = $path . '10300000' . $i . '.json';
			$list_data = json_decode(file_get_contents($file_name));
			$k = 102005512;
			for ($j = 0; $j < count($list_data); $j++) {
				$list_data[$j]->id_word = $k;
				$list_data[$j]->id_subject += 103000111;
				$k++;
			}
		}

		echo json_encode($list_data);
	}

	public function import_data () {
		ini_set('max_execution_time', 600000000);
		for ($k = 1; $k < 3; $k++) {
			$data_word = json_decode(file_get_contents(public_path() . '/database/korea/10300000' . $k . '.json'));
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

				DB::table('korea')->insert($word);
			}
		}
	}

	public function get_url_download () {
		ini_set('max_execution_time', 600000000);
		$auto = new AutoSearchController();
		$list_data = DB::table('korea')->where('url', '')->get();
		$size = count($list_data);
		for ($i = 0; $i < $size; $i++) {
			$word = $list_data[$i]->word;
			if ($word == null || $word == '') {
				$word = $list_data[$i]->mean;
			}

			$list_url = $auto->crawler_url_image($word);
			if (count($list_url) > 0) {
				DB::table('korea')->where('word_id', $list_data[$i]->word_id)
				->update(array('url' => $list_url[0], 'sub_url' => $list_url[1]));
			}
		}
	}

	public function get_phonectic () {
		ini_set('max_execution_time', 600000000);
		$list = [];
		$list_word = json_decode(file_get_contents(public_path() . '/database/korea/103000001.json'));
		for ($i = 5272; $i < count($list_word); $i++) {
			$word = $list_word[$i]->word;
			$pos = strpos($word, ' ');
			if ($pos === false) {
				$word = urlencode($word);
				try {
					$result = json_decode(file_get_contents('http://localhost/crawler/get-phonetic/' . $word));
					$result = json_decode($result);
					$phonetic = $result->sentences[1]->src_translit;
					$phonetic = explode(',', $phonetic)[0];
				} catch (Exception $e) {
					$phonetic = null;
				}
			} else {
			    $phonetic = null;
			}
			$list_word[$i]->phonectic = $phonetic;
			array_push($list, $list_word[$i]);
			$string = json_encode($list);
			$file  = fopen(public_path() . "/test.txt", "w");
			echo fwrite($file, $string);
		}
		fclose($file);
	}

	public function get_phonectic_2 () {
		ini_set('max_execution_time', 600000000);
		$list = [];
		$list_word = json_decode(file_get_contents(public_path() . '/database/korea/103000002.json'));
		for ($i = 0; $i < count($list_word); $i++) {
			if ($list_word[$i]->id_word > 103008599) {
				$word = $list_word[$i]->word;
				$pos = strpos($word, ' ');
				if ($pos === false) {
					try {
						$result = json_decode(file_get_contents('http://localhost/crawler/get-phonetic/' . $word));
						$result = json_decode($result);
						$phonetic = $result->sentences[1]->src_translit;
						$phonetic = explode(',', $phonetic)[0];
					} catch (Exception $e) {
						$phonetic = null;
					}
				} else {
				    $phonetic = null;
				}
				$list_word[$i]->phonectic = $phonetic;
				array_push($list, $list_word[$i]);
				$string = json_encode($list);
				$file  = fopen(public_path() . "/test1.txt", "w");
				echo fwrite($file, $string);
			}
		}
		fclose($file);
	}

	public function test1 () {
		echo $this->get_phonetic_with_google(urlencode('직경/외경'));
	}
 
	public function get_phonetic_with_google ($query) {
		try {
			$opts = array('http' => array('header' => 'User-Agent : Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36,
        Content-Type: application/json; charset=utf-8,
        accept-language: en-US,en;q=0.8,en-GB;q=0.6,vi;q=0.4,ja;q=0.2,zh-CN;q=0.2,zh;q=0.2,
        authority: translate.googleapis.com'));
			$context = stream_context_create($opts);
			$url = "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&dt=bd&dj=1&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=at";
			$url .= "&sl=ko&tl=en&q=" . $query;
			$translate = file_get_contents($url, false, $context);
			return Response::json($translate);
		} catch (Exception $e) {
			Log::info($e);
			return 'error';
		}
		
	}

	public function test3 () {
		ini_set('max_execution_time', 600000000);
		$url = 'http://tiengtrungnet.com/tu-vung-ve-dien-thoai-dien-tu';
		libxml_use_internal_errors(true);
		$dom = new DOMDocument('1.0', 'utf-8');
		libxml_clear_errors();
		$dom->loadHTMLFile(trim($url));
		$xpath = new DOMXPath($dom);
		$item = $xpath->query('.//div[@class="content clearfix"]/p');
		$k = 102016258;
		$i = 0;
		$list_word = [];
		foreach ($item as $key => $value) {
			if ($key != 0 || $key != 1 || $key != 45) {
				$list_span = $xpath->query('.//span[@style="font-size: medium;"]', $value);
				if ($list_span->length > 3) {
					foreach($list_span as $key => $value) {
						if ($key == 0) {
							$word = $value->nodeValue;
						} else if ($key == 2) {
							try {
								$mean = $value->nodeValue;
							} catch (Exception $e) {
								$mean = '';
							}
							echo $mean;
							
						}
					}
				}

			}
		}

		echo json_encode($list_word);

	}
 
}

?>