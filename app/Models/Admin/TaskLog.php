<?php

namespace App\Models\Admin;

use App\Models\Elegant;

class TaskLog extends Elegant
{


    public $timestamps = false;
    protected $table = 'task_logs';
    protected $fillable = [
        'job',
        'output',
        'created_at'
    ];

}
