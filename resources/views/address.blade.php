@extends('layouts.app_new')
@section('content')
<address online-order="{{ $onlineOrder }}" enabled="{{ $enabled }}"></address>
@endsection('content')