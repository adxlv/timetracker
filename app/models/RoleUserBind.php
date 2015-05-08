<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class RoleUserBind extends \Eloquent {
	protected $fillable = [];

	use SoftDeletingTrait;
	protected $dates = ['deleted_at'];
}