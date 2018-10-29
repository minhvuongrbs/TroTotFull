<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostRoom extends Model
{
    protected $table='posts';

    public function room_detail()
    {
      return $this->belongsTo('Models\RoomDetails','id','post_id');
    }


}
