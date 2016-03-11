<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Error extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'error';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function save_error ($imageId, $imageWord, $imageMean) {
		$error = new Error();
		$error->imageId = $imageId;
		$error->status  = 0;		
		$error->imageWord = $imageWord;
		$error->imageMean = $imageMean;
		if ($error->save()) {
			return array('status' => 200);
		} else return array('status' => 304);
	}

}
