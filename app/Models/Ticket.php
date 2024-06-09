<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'assign_staff',
        'company_id',
        'priority',
        'cc',
        'description',
        'file',
        'user_id',
        'status',
        'to_email',
        'message_id',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assign_staff');
    }

    public function userProfile()
    {
        return $this->assignedUser()->hasOne(UserProfile::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}