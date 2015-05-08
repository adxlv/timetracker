<?php

class UserTimeLog extends \Eloquent {
	protected $fillable = [];

	public function user()
    {
        return $this->belongsTo('User','user_id');
    }
    public function task()
    {
        return $this->belongsTo('Task','task_id');
    }
    public function getTimeAttribute($value) {
    	$date = new DateTime($value);
    	// $date->setTimezone(new DateTimeZone('Europe/Riga'));
    	
    	return $date->format(DateTime::ISO8601);
    }

    public function setTimeAttribute($value) {
        $date = new DateTime($value);
        $this->attributes['time'] = date_format($date, 'Y-m-d H:i:s');
    }
}