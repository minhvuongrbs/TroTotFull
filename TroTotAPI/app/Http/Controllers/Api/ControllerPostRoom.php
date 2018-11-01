<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PostRoom;
use App\Models\RoomDetails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use DB;

class ControllerPostRoom extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $limit = request()->limit ?: 10;
        $postRoom = PostRoom::orderBy('created_at','desc')->paginate($limit);
        foreach($postRoom as $post){
            // $post->created_at = $post->created_at->format('d M Y');
            $post->user;
            $post->gallery;
        }
        return $this->setStatusCode(200)->withSuccess('index', $postRoom);
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
      // $rules = $this->initRule();
      //   $messages = $this->initMessage();
      //   $validator = Validator::make($request->all());
      //   if ($validator->fails()) {
      //       return $this->setStatusCode(400)->setErrors($validator->messages())->withError('error');
      //   } else {
            try {

                $postRoom=new PostRoom;
                $postRoom->post_type_id=$request->post_type_id;
                $postRoom->room_type_id=$request->room_type_id;
                $postRoom->user_id=$request->user_id;
                $postRoom->title=$request->title;
                $postRoom->price=$request->post_id;
                $postRoom->minPrice=$request->minPrice;
                $postRoom->maxPrice=$request->maxPrice;
                $postRoom->address=$request->address;
                $postRoom->phone=$request->phone;
                $postRoom->address=$request->address;
                $postRoom->save();
                $roomDetail=new RoomDetails;
                $roomDetail->post_id=$postRoom->id;
                $roomDetail->aceage=$request->aceage;
                $roomDetail->minAceage=$request->minAceage;
                $roomDetail->maxAceage=$request->maxAceage;
                $roomDetail->description=$request->description;
                $roomDetail->longitute=$request->longitute;
                $roomDetail->latitude=$request->latitude;
                $roomDetail->save();
                return $this->withSuccess('Stored', $postRoom);
            } catch (\Exception $e) {
                return $this->setStatusCode(500)->withError($e->getMessage());
            }
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
          $postRoom=PostRoom::where('id', $id)->first();
          $postRoom->room_detail;
          $postRoom->user;
          return $this->withSuccess('show',$postRoom);
        }catch(\Exception $e){
          return $this->setStatusCode(500)->withError($e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function show_map()
    {
      try{
        $mytime = Carbon::now()->subDays(14);
        // echo $mytime;
        $map=DB::table('posts')
        ->join('rooms_detail','posts.id','=','rooms_detail.post_id')
        ->select('post_id','longitute','latitude','title')
          ->where('longitute','!=',null)
        ->where('latitude','!=',null)
        ->where('created_at','>',$mytime)
        ->get();
        return $this->withSuccess('showMap',$map);
      }catch(\Exception $e){
        return $this->setStatusCode(500)->withError($e->getMessage());
      }
    }
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
    //   $pureData=['room_type_id']=$data->room_type_id;
    //   $pureData=['user_id']=$data->user_id;
    //   $pureData=['title']=$data->title;
    //   $pureData=['price']=$data->price;
    //   $pureData=['minPrice']=$data->minPrice;
    //   $pureData=['maxPrice']=$data->maxPrice;
    //   $pureData=['price']=$data->price;
    //   $pureData=['price']=$data->price;
    //
    //   $pureData=['phone']=$data->post_type_id;
    //   return $pureData;
    // }

    public function initRule(){
      $rules = [];
      $rules['address'] = 'required|string|max:255';
      // $rules['phone'] = 'required|string|max:255';
      // $rules['acreage'] = 'required|integer';
      // $rules['electric_bill'] = 'required|integer';
      // $rules['water_bill'] = 'required|integer';
      // $rules['rate'] = 'required|integer';
      return $rules;
  }
  /**
   * Init Message for notification if got error
   *
   * @return void
   */
  public function initMessage(){
      $messages = [];
      $messages = [
          'address' => "Please update the room's address",
          // 'phone' => "Please update the room's phone contract",
          // 'acreage' => "You've input wrong value for arceage",
          // 'electric_bill' => "You've input wrong value for electric bill",
          // 'water_bill' => "You've input wrong value for water bill",
          // 'room_bill' => "You've input wrong value for room bill",
      ];
      return $messages;
  }
}
