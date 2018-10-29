<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PostRoom;
use App\Models\RoomDetails;
use App\Http\Controllers\Controller;

class ControllerPostRoom extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $limit=request()->limit ?:10;
      $postRoom=PostRoom::where('post_type_id',1)->orderBy('created_at','desc')->paginate($limit);
      return $postRoom;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try{
        $data=$this->dataFilter($request);
        $postRoom=PostRoom::create($data);
        $roomDetail=RoomDetails::create(['post_id'=>$postRoom->id]);
      }catch(\Exception $e){
        echo 'exeption 500';
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $postRoom=PostRoom::where('post_id', $id)->first();
        return $postRoom;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    // public function dataFilter($data)
    // {
    //   $pureData=[];
    //   $pureData=['post_type_id']=$data->post_type_id;
    //   $pureData=['user_id']=$data->user_id;
    //   $pureData=['tittle']=$data->tittle;
    //   $pureData=['price']=$data->price;
    //   $pureData=['room_type_id']=$data->room_type_id;
    //   $pureData=['phone']=$data->post_type_id;
    //   return $pureData;
    // }
}
