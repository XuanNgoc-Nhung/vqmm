@extends('admin.layouts.app')
@section('title')
    <title>Lịch sử vòng quay</title>
@endsection
@section('content')
    <div id="app">
        <lich-su-vong-quay/>
    </div>
@endsection
@section("js_location")
    <script src="{{asset('js/lich-su-vong-quay.js?t='.Carbon\Carbon::now()->timestamp)}}"></script>
@endsection
