@extends('admin.layouts.app')
@section('title')
    <title>Quản lý phần thưởng</title>
@endsection
@section('content')
    <div id="app">
        <quan-ly-phan-thuong/>
    </div>
@endsection
@section("js_location")
    <script src="{{asset('js/quan-ly-phan-thuong.js?t='.Carbon\Carbon::now()->timestamp)}}"></script>
@endsection
