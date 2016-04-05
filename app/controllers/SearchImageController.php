<?php 

class SearchImageController extends BaseController {
	public $image;
	public $sqlite;

	public function __construct () {
		$this->image = new Image();
		$this->sqlite = new SqliteController();
	}

	public function get_search () {
		return View::make('search');
	}

	public function action_search_url_image () {
		$image_mean = $_POST['imageMean'];
		$url = "http://103.253.145.165:8787/search/" . $image_mean . "/20";
		try {
			$list_image = file_get_contents($url);	
			return $list_image;
		} catch (Exception $e) {
			return Response::json(array('status' => 304));
		}		
	}

	public function action_download_fix_image () {
		$image_id = $_POST['imageId'];
		$image_url = $_POST['imageUrl'];
		Image::where('imageId', $imageId)->update(array('url' => $image_url));
		$this->download_and_crop_image($image_id, $image_url);
		return Response::json(array('status' => 200));
	}

	// Hàm để xử lí khi người dùng chọn lại ảnh
	public function action_save_url_image () {
		$image_url = $_POST['url'];
		$image_id  = $_POST['id'];
		Image::where('imageId', $image_id)->update(array('url' => $image_url));
		$this->download_and_crop_image($image_id.'.jpg', $image_url);
		$this->resize_image($image_id.'.jpg');
		return Response::json(array('status' => 200));	
	}

	public function action_show_list_image () {
		$list_image = Image::where('status', 1)->paginate(20);
		$list['list_image'] = $list_image;
		return View::make('list-image', $list);
	}

	public function action_auto_search () {	
		ini_set('max_execution_time', 600000000);
		$filename = public_path() . '/word.db';
		$db = new SQLite3($filename);
		$results = $db->query('select * from word where id > 2507');
		$result = [];

		while ($row = $results->fetchArray()) {
			array_push($result, $row);
		}

		$size = count($result);
		for ($i = 0; $i < $size; $i++) {
			$e = $result[$i];
			$word_id = $e['id'];
			$word_ja = $e['ja'];
			$word_vi = $e['vi'];
			if (is_null($word_ja)) {
				$word_ja = 'null';
			}
			if (is_null($word_vi)) {
				$word_vi = 'null';
			}
			$error = new Error();
			$image = new Image();
			$url = "http://103.253.145.165:8787/search/" . $word_ja . "/3";
			try {
				$list_image = file_get_contents($url);	
				$list_image = json_decode($list_image);
				if ($list_image->status == 304) {
					$error->save_error($word_id, $word_vi, $word_ja);
				} else {
					$list_image_url = $list_image->result;
					$list_image1 = $list_image_url[0];
					$list_image2 = $list_image_url[1];
					$image->save_image($word_id, $word_vi, $word_ja, $list_image1, $list_image2);
				}
			} catch (Exception $e) {
				$error->save_error($word_id, $word_vi, $word_ja);
			}						
		}
	}

	public function action_download_image () {
		ini_set('max_execution_time', 600000000);
		$list_image = Image::where('status', 2)->get();
		$size = count($list_image);
		for ($i = 0; $i < $size; $i++) {
			$e = $list_image[$i];
			$url_download = $e->url;
			$image_id = $e->imageId;
			$image_name = $image_id . '.jpg';
			try {
				$this->download_and_crop_image($image_name, $url_download);
				Image::where('imageId', $image_id)->update(array('status' => 1));					
			} catch (Exception $e) {
				Image::where('imageId', $image_id)->update(array('status' => 2));
			}
		}
	}

	public function action_auto_fix_null () {
		ini_set('max_execution_time', 600000000);
		$filename = public_path() . '/word.db';
		$db = new SQLite3($filename);
		$results = $db->query('select * from word where ja is null');
		$result = [];

		while ($row = $results->fetchArray()) {
			array_push($result, $row);
		}

		$size = count($result);
		for ($i = 0; $i < $size; $i++) {
			$e = $result[$i];
			$word_id = $e['id'];
			$word_ja = $e['hira'];
			if ($word_id ==  null) {
				$word_ja = $e['vi'];
			}
			$error = new Error();
			$image = new Image();
			$url = "http://103.253.145.165:8787/search/" . $word_ja . "/3";
			try {
				$list_image = file_get_contents($url);	
				$list_image = json_decode($list_image);
				if ($list_image->status == 200) {
					$list_image_url = $list_image->result;
					$url_download = $list_image_url[0];
					$this->download_and_crop_image($word_id.'.jpg', $url_download);
					Image::where('imageId', $word_id)->update(array('imageMean' => $word_ja));
				}				
			} catch (Exception $e) {
				try {
					$list_image = file_get_contents($url);	
					$list_image = json_decode($list_image);
					if ($list_image->status == 200) {
						$list_image_url = $list_image->result;
						$url_download = $list_image_url[0];
						$this->download_and_crop_image($word_id.'.jpg', $url_download);
						Image::where('imageId', $word_id)->update(array('imageMean' => $word_ja));
					}				
				} catch (Exception $e) {
					
				}
			}	
		}	
	}

	public function download_and_crop_image ($file_name, $url_download) {
		$img = file_get_contents($url_download);
		$im  = imagecreatefromstring($img);
		$width = imagesx($im);
		$height = imagesy($im);
		if (($width / $height) > (334 / 254)) {
			$crop_width  = $height * 334 / 254;
			$crop_height = $height;
		} else {
			$crop_width  = $width;
			$crop_height = $width * 254 / 334;
		}
		$crop_measure  = min($crop_width, $crop_height);
		$to_crop_array = array('x' =>0 , 'y' => 0, 'width' => $crop_width, 'height'=> $crop_height);
		$thumb_im = imagecrop($im, $to_crop_array);
		imagejpeg($thumb_im, $file_name);
		// $this->resize_image($file_name);
	}

	public function resize_list_image () {
		ini_set('max_execution_time', 600000000);
		$list_image = Image::where('status', 2)->orWhere('status', 3)->get();
		$size = count($list_image);
		for ($i = 0; $i < $size; $i++) {
			$e = $list_image[$i];
			$image_id = $e->imageId;
			$this->resize_image($image_id . '.jpg');
			Image::where('imageId', $image_id)->update(array('status' => 1));
		}
	}

	public function resize_image ($file_name) {
		$image = new ResizeController();
	   	$image->load('image/' . $file_name);
	   	$image->resize(334, 254);
	   	$image->save('thumbnail/' . $file_name);
	}

}


?>