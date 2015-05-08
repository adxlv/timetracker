<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Client extends \Eloquent {
	protected $fillable = [];

	use SoftDeletingTrait;
	protected $dates = ['deleted_at'];

	public function projects()
    {
        return $this->hasMany('Project','client_id','id');
    }
}