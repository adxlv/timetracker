<?php

class EstimateEntry extends \Eloquent {
	protected $fillable = [];

	

	public function setHoursAttribute($value) {
        $this->attributes['hours'] = json_encode($value);
    }
    public function getHoursAttribute($value) {
        return json_decode($value);
    }
}