<?php 

class ChineseController extends BaseController {

	public function get_data () {
		ini_set('max_execution_time', 600000000);
		$url = 'http://chinese.com.vn/214-bo-thu-trong-tieng-trung-quoc.html';
		libxml_use_internal_errors(true);
		$dom = new DOMDocument('1.0', 'utf-8');
		libxml_clear_errors();
		$dom->loadHTMLFile(trim($url));
		$xpath = new DOMXPath($dom);
		$item = $xpath->query('.//div[@class="post-single"]/table/tbody/tr');
		$list_word = [];
		$i = 0;
		foreach ($item as $key => $value) {
			if ($i != 0) {
				$td = $xpath->query('.//td', $value);
				foreach ($td as $key => $value) {
					switch ($key) {
						case 1:
							$word = trim($value->nodeValue);
							break;
						
						case 2:
							$mean = trim($value->nodeValue);
							break;
						case 3:
							$phonectic = trim($value->nodeValue);
							break;
						case 4:
							$des = trim($value->nodeValue);
							break;
					}
				}	
				if ($word == null || $word == '' || $word == ' ') {
					echo 'test';
				}

				$w = array (
					"id_word" => 103002472,
				    "id_subject" => 102000001,
				    "id_course" => 102000001,
				    "word" => $word,
				    "mean" => $mean,
				    "example" => null,
				    "example_mean" => null,
				    "num_ef" => null,
				    "time_date" => null,
				    "next_time" => null,
				    "num_n" => null,
				    "num_i" => null,
				    "max_q" => null,
				    "phonectic" => $phonectic,
				    "des" => $des
				);
				array_push($list_word, $w);
			}
			$i++;			
		}

		// echo '<pre>';
		// print_r($list_word);
		// echo '</pre>';
		echo json_encode($list_word);
	}

	public function get_kanji () {
		ini_set('max_execution_time', 600000000);
		$url = 'http://hoctiengtrungonline.com/500-chu-han-co-ban-tieng-trung-buoc-ban-phai-hoc-va-nho.html';
		libxml_use_internal_errors(true);
		$dom = new DOMDocument('1.0', 'utf-8');
		libxml_clear_errors();
		$dom->loadHTMLFile(trim($url));
		$xpath = new DOMXPath($dom);
		$list_strong = array();
		$item = $xpath->query('.//div[@class="entry-content"]/table/tbody/tr/td');
		$i = 1;
		foreach ($item as $key => $value) {
			$list_text = $xpath->query('.//strong/following-sibling::node()[not(preceding-sibling::em) and not(self::em)]', $value);
			foreach ($list_text as $key => $value) {
				$text = $value->nodeValue;
				$pos = strpos($text, 'sẽ có thưởng nhá');
				if ($pos === false && $text != 'và ' && $text != '(3 chữ)' ) {
				    echo $i . ': ' .$value->nodeValue;
					echo '<br>';
					$i++;
				}
			}
		}
	}

	public function get_kanji_test () {
		ini_set('max_execution_time', 600000000);
		$url = 'http://hoctiengtrungonline.com/500-chu-han-co-ban-tieng-trung-buoc-ban-phai-hoc-va-nho.html';
		libxml_use_internal_errors(true);
		$dom = new DOMDocument('1.0', 'utf-8');
		libxml_clear_errors();
		$dom->loadHTMLFile(trim($url));
		$xpath = new DOMXPath($dom);
		$list_strong = array();
		$item = $xpath->query('.//div[@class="entry-content"]/table/tbody/tr/td');
		foreach ($item as $key => $value) {
			$list_strong = $xpath->query('.//strong', $value);
			foreach ($list_strong as $key => $value) {
				echo trim($value->nodeValue);
				echo '<br>';
			}
		}
	}

	public function get_word ($link) {
		ini_set('max_execution_time', 600000000);
		$url = $link;
		libxml_use_internal_errors(true);
		$dom = new DOMDocument('1.0', 'utf-8');
		libxml_clear_errors();
		$dom->loadHTMLFile(trim($url));
		$xpath = new DOMXPath($dom);
		$list_word = [];
		$item = $xpath->query('.//div[@class="content clearfix"]/table/tbody/tr');
		foreach ($item as $key => $value) {
			if ($key > 0) {
				$td = $xpath->query('.//td', $value);
				foreach ($td as $key => $value) {
					if ($key == 1) {
						$word = $value->nodeValue;
					} else if ($key == 2) {
						$phonectic = $value->nodeValue;
					} else if ($key == 3) {
						$mean = $value->nodeValue;
					}
				}

				$word = array(
					"id_word" => 103002472,
				    "id_subject" => 102000001,
				    "id_course" => 102000001,
				    "word" => $word,
				    "mean" => $mean,
				    "example" => null,
				    "example_mean" => null,
				    "num_ef" => null,
				    "time_date" => null,
				    "next_time" => null,
				    "num_n" => null,
				    "num_i" => null,
				    "max_q" => null,
				    "phonectic" => $phonectic,
				    "des" => null
				);
				array_push($list_word, $word);
			}
		}	

		return $list_word;
	}

	public function get_subject () {
		ini_set('max_execution_time', 600000000);
		$url = 'http://tiengtrungnet.com/tong-hop-list-tu-vung-tieng-trung';
		libxml_use_internal_errors(true);
		$dom = new DOMDocument('1.0', 'utf-8');
		libxml_clear_errors();
		$dom->loadHTMLFile(trim($url));
		$xpath = new DOMXPath($dom);
		$list = [];
		$k = 1;
		$j = 1;
		$item = $xpath->query('.//div[@class="content clearfix"]/table/tbody/tr');
		foreach ($item as $key => $value) {
			if ($key > 0) {
				$td = $xpath->query('.//td', $value);
				foreach ($td as $key => $value) {
					if ($key == 1) {
						$name = $value->nodeValue;
					} else if ($key ==  2) {
						$link = $value->nodeValue;
						libxml_use_internal_errors(true);
						$domtd = new DOMDocument('1.0', 'utf-8');
						$domtd->loadHTMLFile(trim($link));
						$xpathtd = new DOMXPath($domtd);
						$itemtd = $xpathtd->query('.//div[@class="content clearfix"]/table/tbody/tr');
						foreach ($itemtd as $keytd => $valuetd) {
							if ($keytd > 0) {
								$tdtd = $xpathtd->query('.//td', $valuetd);
								foreach ($tdtd as $keytd => $valuetd) {
									if ($keytd == 1) {
										$word = $valuetd->nodeValue;
									} else if ($keytd == 2) {
										$phonectic = $valuetd->nodeValue;
									} else if ($keytd == 3) {
										$mean = $valuetd->nodeValue;
									}
								}

								$w = array(
									"id_word" => 102000000 + $k,
								    "id_subject" => 102000000 + $j,
								    "id_course" => 102000001,
								    "word" => $word,
								    "mean" => $mean,
								    "example" => null,
								    "example_mean" => null,
								    "num_ef" => null,
								    "time_date" => null,
								    "next_time" => null,
								    "num_n" => null,
								    "num_i" => null,
								    "max_q" => null,
								    "phonectic" => $phonectic,
								    "des" => null
								);
								$k++;
								array_push($list, $w);
							}
						}
						++$j;
					}
				}
				$file  = fopen(public_path() . "/test.txt", "w");
				echo fwrite($file, json_encode($list));
				fclose($file);
			}
		}	
	}

	public function get_list_subject () {
		ini_set('max_execution_time', 600000000);
		$url = 'http://tiengtrungnet.com/tong-hop-list-tu-vung-tieng-trung';
		libxml_use_internal_errors(true);
		$dom = new DOMDocument('1.0', 'utf-8');
		libxml_clear_errors();
		$dom->loadHTMLFile(trim($url));
		$xpath = new DOMXPath($dom);
		$list = [];
		$k = 1;
		$item = $xpath->query('.//div[@class="content clearfix"]/table/tbody/tr');
		foreach ($item as $key => $value) {
			if ($key > 0) {
				$td = $xpath->query('.//td', $value);
				foreach ($td as $key => $value) {
					if ($key == 1) {
						$name = $value->nodeValue;
						$sub = array(
							"name" => $name,
						    "id" => 102000000 + $k,
						    "id_course" => 102000001,
						    "mean" => null,
						    "total" => 19,
						    "num_word" => null,
						    "time_date" => null
						);
						++$k;
						array_push($list, $sub);
					} 
				}
			}
		}

		echo json_encode($list);
	}

	public function import_data () {
		$list_word = file_get_contents(public_path() . '/database/chinese/500.json');
		$list_word = json_decode($list_word);
		foreach ($list_word as $key => $value) {
			$word = array(
				"word_id" => $value->id_word,
				"subject_id" => $value->id_subject,
				"course_id" => $value->id_course,
				"word" => $value->word,
				"mean" => $value->des
			);
			DB::table('chinese')->insert($word);
		}
	}

	public function get_url_download () {
		ini_set('max_execution_time', 600000000);
		$auto = new AutoSearchController();
		$list_data = DB::table('chinese')->where('url', ' ')->get();
		$size = count($list_data);
		for ($i = 0; $i < $size; $i++) {
			$word = $list_data[$i]->word;
			if ($word == null || $word == '') {
				$word = $list_data[$i]->mean;
			}

			$list_url = $auto->crawler_url_image($word);
			if (count($list_url) > 0) {
				DB::table('chinese')->where('word_id', $list_data[$i]->word_id)
				->update(array('url' => $list_url[0], 'sub_url' => $list_url[1], 'sub_url_2' => $list_url[3]));
			}
		}
	}


}