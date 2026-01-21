<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterventionTemplate extends Model
{
    protected $table = 'interventiontemplates';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'level',
        'risk_level',
        'category',
        'title_template',
        'message_template',
        'heed_message',
        'actions_template',
        'is_mandatory',
        'is_active'
    ];

    protected $casts = [
        'actions_template' => 'array'
    ];
}
