<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Estimate extends Eloquent {
    protected $fillable = [];

    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];
    
    public function tasks() {
        return $this->hasMany('Task');
    }
    public function expences() {
        return $this->hasMany('Expence');
    }
    public function entries() {
        return $this->hasMany('EstimateEntry');
    }
    public function bill_entries() {
        return $this->hasMany('EstimateBillentry');
    }
    public function project() {
        return $this->belongsTo('Project');
    }
    public function branch() {
        return $this->hasOne('Branches', 'id', 'group');
    }

    public function setInvolvedRolesAttribute($value) {
        $this->attributes['involved_roles'] = json_encode($value);
    }
    public function getInvolvedRolesAttribute($value) {
        return json_decode($value);
    }
    
    // public function setGroupAttribute($value) {
    //     $this->attributes['group'] = json_encode($value);
    // }
    // public function getGroupAttribute($value) {
    // 	return json_decode($value);
    // }


}