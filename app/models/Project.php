<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Project extends \Eloquent {
	protected $fillable = [];
	
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

	public function estimates()
    {
        return $this->hasMany('Estimate');
    }
    public function tasks()
    {
        return $this->hasMany('Task');
    }
    public function client() {
        return $this->belongsTo('Client');
    }
}