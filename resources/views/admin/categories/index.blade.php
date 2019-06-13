@extends('admin.master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <a href="{{ route('admin.category.create') }}" class="btn btn-primary">Tạo chuyên mục</a>
                </div>
                <br>
                <div class="panel panel-default">
                    <div class="panel-heading">Danh sách chuyên mục</div>
                    <div class="panel-body">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Slug</th>
                                    <th>Thứ tự</th>
                                    <th>Cha</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày cập nhật</th>
                                    <th>Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>{{ $category->order }}</td>
                                        <td>{{ $category->parent }}</td>
                                        <td>{{ $category->created_at }}</td>
                                        <td>{{ $category->updated_at }}</td>
                                        <td>
                                            <a href="{{ route('admin.category.show', ['id' => $category->id]) }}"
                                               class="btn btn-primary">Sửa</a>
                                            <a href="{{ route('admin.category.delete', ['id' => $category->id]) }}"
                                               class="btn btn-danger"
                                               onclick="event.preventDefault();
                                                        window.confirm('Bạn đã chắc chắn xóa chưa?') ?
                                                       document.getElementById('category-delete-{{ $category->id }}').submit() :
                                                       0;">Xóa</a>
                                            <form action="{{ route('admin.category.delete', ['id' => $category->id]) }}"
                                                  method="post" id="category-delete-{{ $category->id }}">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No data</td>
                                    </tr>
                                @endforelse


                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection