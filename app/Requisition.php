<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'edit_at',
        'updated_at',
        'deleted_at',
        'document_at',
        'approve_at',
        'none_approve_at',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function take()
    {
        return $this->belongsTo(Take::class);
    }

    public function createUser()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function editUser()
    {
        return $this->belongsTo(User::class, 'edit_user_id');
    }

    public function deleteUser()
    {
        return $this->belongsTo(User::class, 'deleted_user_id');
    }

    public function approveUser()
    {
        return $this->belongsTo(User::class, 'approve_user_id');
    }

    public function noneApproveUser()
    {
        return $this->belongsTo(User::class, 'none_approve_user_id');
    }

    public function requisitionGoods()
    {
        return $this->hasMany(RequisitionGood::class);
    }
}
