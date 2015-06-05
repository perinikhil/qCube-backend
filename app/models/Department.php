<?php

class Department extends \Eloquent {

	protected $fillable = ['name', 'abbr', 'organization_id'];

	public static $rules = [
		'name' => 'required'
	];

	public function subjects()
	{
		return $this->hasMany('Subject');
	}

	public function organization()
	{
		return $this->belongsTo('Organization');
	}
}