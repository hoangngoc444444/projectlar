@extends('admin.master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-primary">Danh sách chuyên mục</a>
                </div>
                <br>
                <div class="panel panel-default">
                    <div class="panel-heading">Cập nhật chuyên mục</div>
                    <div class="panel-body">
                        <form action="{{ route('admin.category.update', ['id' => $category->id]) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Tên chuyên mục</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Tên chuyên mục"
                                       value="{{ $category->name }}">
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('order') ? 'has-error' : '' }}">
                                <label for="order">Thứ tự ưu tiên</label>
                                <input type="text" class="form-control" id="order" name="order" placeholder="Thứ tự ưu tiên"
                                       value="{{ $category->order }}">
                                <span class="help-block">{{ $errors->first('order') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('parent') ? 'has-error' : '' }}">
                                <label for="parent">Chuyên mục cha</label>
                                <select name="parent" id="parent" class="form-control">
                                    <option value="0">Không</option>
                                    @if (count($categories) > 0)
                                        @foreach($categories as $sCategory)
                                            <option value="{{ $sCategory->id }}" {{ $category->parent == $sCategory->id ? 'selected' : '' }}>{{ $sCategory->name }}</option>
                                        @endforeach
                                    @endif

                                </select>
                                <span class="help-block">{{ $errors->first('parent') }}</span>
                            </div>


                            <button type="submit" class="btn btn-success">Cập nhật chuyên mục</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection