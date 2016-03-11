<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Image extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'image';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function save_image ($imageId, $imageWord, $imageMean, $url1, $url2) {
		$image = new Image();
		$image->imageId = $imageId;
		$image->imageWord = $imageWord;
		$image->imageMean = $imageMean;
		$image->url  = $url1;
		$image->url2 = $url2;
		$image->status = 0;
		if ($image->save()) {
			return array('status' => 200);
		} else return array('status' => 304);
	}

}
