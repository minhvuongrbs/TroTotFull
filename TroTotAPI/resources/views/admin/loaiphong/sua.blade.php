@extends('admin.layout.index')
@section('content')
                  <!-- general form elements -->
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Sửa loại phòng</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                        @if(count($errors)>0)
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $err)
                                    {{$err}}<br>
                                @endforeach
                            </div>
                        @endif

                        @if(session('thongbao'))
                            <div class="alert alert-success">
                                {{session('thongbao')}}
                            </div>
                        @endif

                    <form action="admin/loaiphong/sua/{{$loaiphong->id}}" method="POST">
                    <input type="hidden" name="_token" value={{csrf_token()}} />
                      <div class="box-body">
                        <div class="form-group">
                          <label>Tên</label>
                          <input type="text" class="form-control" id="name" name="name" value="{{$loaiphong->name}}">
                        </div>
                        <div class="form-group">
                          <label>Mô tả</label>
                          <input class="form-control" id="description" name="description" value="{{$loaiphong->description}}">
                        </div>
                      </div>
                      <!-- /.box-body -->

                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>



@endsection