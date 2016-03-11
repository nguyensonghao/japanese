<?php 
// Status = 0 đã lấy được url download
// Status = 1 down được ảnh và crop ảnh
// Status = 2 down được ảnh, crop ảnh và resize ảnh
// Status = -1 lỗi không lấy được url download từ api
// Status = -2 lỗi chưa down được ảnh
// Status = -3 lỗi down được ảnh nhưng chưa resize

class AutoSearchController extends BaseController {
	public $convert;
	public $resize;
	public $data;

	public function __construct () {
		$this->convert = new ConvertDataController();
		$this->resize = new ResizeController();
	}

	public function load_data_json ($file_name) {
		return $this->convert->load_data_json($file_name);
	}

	// Lấy danh sách url download cho từ và thêm vào trong cơ sở dữ liệu
	public function create_image_to_database () {
		ini_set('max_execution_time', 600000000);
		$file_name = 'word.json';
		$this->data = $this->load_data_json($file_name);
		if (count($this->data) == 0) {
			echo 'Kiểm tra lại file' . $file_name . 'có tồn tại';
		} else {
			foreach ($this->data as $key => $value) {
				$word = array();
				$word['word_id'] = $value->id_word;
				$word['mean'] = $value->mean;
				$word['word'] = $value->word;
				$query = $value->word;
				DB::table('word_crawler')->insert($word);
			}
		}
	}

	public function get_list_url_download () {
		ini_set('max_execution_time', 600000000);
		$list_data = DB::table('word_image')->where('word_id', '>', 101001558)->get();
		foreach ($list_data as $key => $value) {
			$query = $value->word;
			if ($query == null || $query == '') {
				$query = $value->mean;
			}
			$word_id = $value->word_id;
			$list_url_download = $this->crawler_url_image($query);
			if (count($list_url_download) == 0) {
				DB::table('word_image')->where('word_id', $word_id)
				->update(array('status' => -1));
			} else {
				DB::table('word_image')->where('word_id', $word_id)
				->update(array('status' => 0, 'url' => $list_url_download[0], 'sub_url' => $list_url_download[1]));
			}
			sleep(0.2);
		}
	}

	// Download ảnh từ url có sẵn trong database
	public function crawler_image_download () {
		ini_set('max_execution_time', 600000000);
		$data_base = DB::table('word_image')->where('status', -2)->get();
		foreach ($data_base as $key => $value) {
			$url = $value->url;
			$sub_url = $value->sub_url;
			$course_id = $value->course_id;
			if ($this->download_and_crop_image('roots/' . $course_id, $value->word_id . '.jpg', $url, 254, 334)) {
				DB::table('word_image')->where('word_id', $value->word_id)
				->update(array('status' => 1));
				if ($this->resize_image('roots/' . $course_id, 'thumbnails/' . $course_id, $value->word_id . '.jpg', 254, 334)) {
					DB::table('word_image')->where('word_id', $value->word_id)
				    ->update(array('status' => 2));
				} else {
					DB::table('word_image')->where('word_id', $value->word_id)
				    ->update(array('status' => -3));
				}
			} else {
				if ($this->download_and_crop_image('roots/' . $course_id, $value->word_id . '.jpg', $url, 254, 334)) {
					DB::table('word_image')->where('word_id', $value->word_id)
				    ->update(array('status' => 1));
				    if ($this->resize_image('roots/' . $course_id, 'thumbnails/' . $course_id, $value->word_id . '.jpg', 254, 334)) {
						DB::table('word_image')->where('word_id', $value->word_id)
					    ->update(array('status' => 2));
					} else {
						DB::table('word_image')->where('word_id', $value->word_id)
					    ->update(array('status' => -3));
					}
				} else {
					DB::table('word_image')->where('word_id', $value->word_id)
				    ->update(array('status' => -2));
				}
			}
		}
	}

	// Hàm lấy url crawler theo tên
	public function crawler_url_image ($query) {
		try {
			$query = rawurlencode($query);
			$data = file_get_contents('https://www.googleapis.com/customsearch/v1element?key=AIzaSyCVAXiUzRYsML1Pv6RwSG1gunmMikTzQqY&rsz=filtered_cse&num=20&hl=en&prettyPrint=true&source=gcsc&gss=.com&searchtype=image&q=' . $query . '&cx=011716203299611176711:7eykurmmksk');
			$data = json_decode($data);
			$result = $data->results;
			return array($result[0]->unescapedUrl, $result[1]->unescapedUrl);		
		} catch (Exception $e) {
			return array();
		}
	}

	// Download ảnh từ url trong database
	public function download_and_crop_image ($file_path, $file_name, $url_download, $h, $w) {
		try {
			$img = file_get_contents($url_download);
			$im  = imagecreatefromstring($img);
			$width = imagesx($im);
			$height = imagesy($im);
			if (($width / $height) > ($w / $h)) {
				$crop_width  = $height * $w / $h;
				$crop_height = $height;
			} else {
				$crop_width  = $width;
				$crop_height = $width * $h / $w;
			}

			$crop_measure  = min($crop_width, $crop_height);
			$to_crop_array = array('x' =>0 , 'y' => 0, 'width' => $crop_width, 'height'=> $crop_height);
			$thumb_im = imagecrop($im, $to_crop_array);
			imagejpeg($thumb_im, public_path() . '/' . $file_path . '/' . $file_name);			
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	// Hàm resize ảnh
	public function resize_image ($to_path, $from_path, $file_name, $h, $w) {
		try {
			$resize = new ResizeController();
		   	$resize->load(public_path() . '/' . $to_path . '/' . $file_name);
		   	$resize->resize($w, $h);
		   	$resize->save(public_path() . '/' . $from_path . '/' . $file_name);			
		   	return true;
		} catch (Exception $e) {
			return false;
		}
	}

}

?>