
@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{asset('css/noc/chart-home.css')}}">
    <link rel="stylesheet" href="{{ asset('css/pagination/pagination.css') }}">
    <script src="{{asset('js/noc/Chartjs.js')}}" defer></script>

@endpush

@section('content')
    <div class="container-chart"></div>
    <div class="d-flex justify-content-center paginationjs m-3" id="pagination"  dism="{{ route('noc.datauser') }}" dismlog="{{ route('noc.logger') }}"></div>
@endsection

@push('scripts')
    <script src="{{asset('js/pagination/pagination.js')}}"defer></script>
    <script  type="module" src="{{asset('js/noc/noc.js')}}"defer></script>
@endpush

