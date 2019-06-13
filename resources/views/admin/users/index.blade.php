@extends('admin.master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <a href="{{ route('admin.user.create') }}" class="btn btn-primary">Tạo người dùng</a>
                </div>
                <br>
                <div class="panel panel-default">
                    <div class="panel-heading">Danh sách người dùng</div>
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
                                    <th>Email</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày cập nhật</th>
                                    <th>Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>{{ $user->updated_at }}</td>
                                        <td>
                                            <a href="{{ route('admin.user.show', ['id' => $user->id]) }}"
                                               class="btn btn-primary">Sửa</a>
                                            <a href="{{ route('admin.user.delete', ['id' => $user->id]) }}"
                                               class="btn btn-danger"
                                               onclick="event.preventDefault();
                                                       window.confirm('Bạn đã chắc chắn xóa chưa?') ?
                                                       document.getElementById('user-delete-{{ $user->id }}').submit():
                                                       0;">Xóa</a>
                                            <form action="{{ route('admin.user.delete', ['id' => $user->id]) }}"
                                                  method="post" id="user-delete-{{ $user->id }}">
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
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection