<?php

class NgAppTable extends \Eloquent {
	protected $fillable = [];


	public function setDataAttribute($value) {
        $this->attributes['data'] = json_encode($value);
    }
    public function getDataAttribute($value) {
        return json_decode($value);
    }
}