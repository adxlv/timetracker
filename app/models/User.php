<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {
    use UserTrait, RemindableTrait, SoftDeletingTrait;

	// protected $fillable = [];
	protected $dates = ['deleted_at'];


	public function taskroles() {
        // return $this->belongsToMany('TaskRoleBind', 'role_user_bind', 'user_id', 'taskrolebinds_id');
        return $this->belongsToMany('TaskRoleBind', 'role_user_binds', 'user_id', 'taskrolebinds_id')->withPivot('id')->withTimestamps();
    }

    public function timelogs() {
        return $this->hasMany('UserTimeLog');
    }

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password','pass', 'remember_token');


}
