@extends('layouts.app')

<?php
echo 'Hello  123123213';
?>

@section('content')
{{ $id }}
<form action="{{ route('user.update', ['id' =>  $id]) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <input type="text" name="text">
    <button type="submit">Submit</button>
</form>
@endsection

@section('title')
    ABC - @parent
@endsection