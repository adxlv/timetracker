<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Task extends \Eloquent {
    protected $fillable = [];
    
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

	public function jobroles() {
        return $this->belongsToMany('JobRole', 'task_role_binds', 'task_id', 'jobrole_id')->withPivot('hours','id')->withTimestamps();;;
    }

    public function taskrolebinds() {
        return $this->hasMany('TaskRoleBind');
    }
    public function estimate() {
        return $this->belongsTo('Estimate');
    }
    public function project() {
        return $this->belongsTo('Project');
    }

}