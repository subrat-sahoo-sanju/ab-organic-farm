<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifyMe extends Model
{
    protected $table = 'notify_me';

    protected $fillable = ['email', 'product_name', 'product_slug', 'type'];
}
