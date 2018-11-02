<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\PostRoom;

class CommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
          //thêm comment mới có user id người đăng
          $comment=new Comment;
          $comment->post_id=$request->post_id;
          $comment->user_id=$request->user_id;
          $comment->description=$request->description;
          $comment->save();
          //post_user là chủ bài post
          $post_user=PostRoom::
          select('user_id')
          ->where('id',$comment->post_id)
          ->first();
          // echo $post_user;

          //lấy mảng tất cả user_id liên quan cần nhận thông báo
          $comment_user=Comment::
          select('user_id') //chỉ chọn user_id từ bảng comment
          ->distinct()
          ->where('post_id',$request->post_id)  //nơi có post_id là nơi comment
          ->orWhere('user_id',$post_user)  //hoặc của người đăng bài
          // ->Where('user_id','!=',$request->user_id) //nhưng nếu trùng user_id thì không thông báo
          ->get();
          $comment_user->push($post_user);
          // echo $comment_user;
          foreach ($comment_user as $user) {
            if($user->user_id != $request->user_id)
            {
              $notification=new Notification;
              $notification->comment_id=$comment->id;
              $notification->post_id=$comment->post_id;
              $notification->user_id=$user->user_id;
              $notification->isRead=0;
              $notification->save();
            }
          }
          return $this->withSuccess('Stored', $comment);
        }catch(\Exception $e){
          return $this->setStatusCode(500)->withError($e->getMessage());
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
      $comment=Comment::
        where('post_id',$id)
         ->get();
       foreach ($comment as $cmt) {
           $cmt->user;
         }
      return $this->setStatusCode(200)->withSuccess('index',$comment);
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
}
