<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Branches extends \Eloquent {
	protected $fillable = [];

	use SoftDeletingTrait;
	protected $dates = ['deleted_at'];
}