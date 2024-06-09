<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'profile_image', 'email', 'phone_number', 'telephone_number', 'website', 'about_company',
        'street_address', 'city', 'state_province', 'zipcode', 'country',
        'facebook', 'twitter', 'linkedin', 'skype', 'whatsapp', 'instagram', 'status'
    ];   
}
