<?php

class TaskRoleBind extends \Eloquent {
	protected $fillable = [];

	public function jobrole()
    {
        return $this->belongsTo('JobRole','jobrole_id');
    }
    public function task()
    {
        return $this->belongsTo('Task','task_id');
    }
    public function users() {
        // return $this->belongsToMany('TaskRoleBind', 'role_user_bind', 'user_id', 'taskrolebinds_id');
        return $this->belongsToMany('User', 'role_user_binds', 'taskrolebinds_id', 'user_id')->withPivot('id')->withTimestamps();
    }
    public function usertimelogs() {
        return $this->hasMany('UserTimeLog');
    }
}