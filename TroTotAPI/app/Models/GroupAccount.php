<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupAccount extends Model
{
    public $timestamps=false;
    protected $table='group_of_account';
    protected $fillable=[
      'user_id',
      'group_id',
    ];
}
