<?php

class Expence extends \Eloquent {
	protected $table = 'estimate_expences';

	protected $fillable = [];

	public function estimate() {
        return $this->belongsTo('Estimate');
    }
}