<?php

class Question extends \Eloquent {
	protected $fillable = ['subject_id', 'unit', 'text', 'marks', 'tags', 'course_outcome'];

	public static $rules = [
		'subject_id' => 'required',
		'unit' => 'required',
		'text' => 'required',
		'marks' => 'required'
	];

	public function subject()
	{
		return $this->belongsTo('Subject');
	}

	public function attachments()
	{
		return $this->hasMany('Attachment');
	}
}
