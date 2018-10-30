<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPostRoom extends Model
{
    protected $table='gallerys';
    protected $fillable=['post_id','path'];

    public function post_room(){
      return $this->belongsTo('App\Models\PostRoom','post_id');
    }

}
