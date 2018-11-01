<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostRoom extends Model
{
    protected $table='posts';

    public function room_detail()
    {
      return $this->belongsTo('App\Models\RoomDetails','id','post_id');
    }

    public function galerys(){
      return $this->hasMany('App\Models\GalleryPostRoom','id','post_id');
    }
    public function user()
    {
      return $this->belongsTo('App\Models\User','user_id','id');
    }

}
