<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MediaController extends ApiController
{
    protected $imageExtensions=['jpg','png','jpeg','gif'];
    public function uploadfile(Request $request){
      $folder = 'images';
      $data = [];
      if( $request->hasFile('file') ) {
        $image = $request->file('file');
        $extension = $request->file->extension();
        $length = strlen($image->getClientOriginalName()) - strlen($extension);
        $filename = substr($image->getClientOriginalName(), 0, $length);
        $filename = str_slug($filename).'-'.time().'.'.$extension;
        $image->storeAs('public/'.$folder, $filename);
        $data['path'] = 'storage/app/public/'.$folder.'/'.$filename;
      }
      return $this->withSuccess('Upload file success', $data);
    }
}
