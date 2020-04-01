@extends('layouts.app')
{{-- @section('title','Nojs') --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/nojs.css')}}">
@endpush

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-lg-6">
            <a href="{{ route('nojs.create') }}" class="btn btn-success modal-show" title="Create User"><i class="icon-plus"></i> Create</a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-3">
        <form action="{{ route('nojs.index') }}" method="get">
            <div class="input-group">
            <input type="text" class="form-control" placeholder="Search nojs.." name="search" id="search" autocomplete="off">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit" id="tombolCari"><i class="icon-search"></i> Search</button>
            </div>
            </div>
        </form>
        </div>
    </div>
    <div class="ml-2">{{count($datanojs)}} User</div>
    <ul class="list">
        @foreach ($datanojs as $js)
        <li class="font-weight-bold">
            {{ $js->nojs }}
            {{ $js->site }}
            <a href="{{ route('nojs.destroy',$js->nojs) }}" class="btn-delete btn-danger ml-2 btn-sm float-right" title="Delete: {{ $js->site }}" dism="{{ route('nojs.index') }}"><i class="fa fa-trash text-danger text-light"></i></a>
            <a href="{{ route('nojs.edit',$js->nojs) }}" class="modal-show edit btn-success ml-2 btn-sm float-right" title="Edit {{ $js->site }}"><i class="fa fa-pencil text-inverse text-light"></i></a>
            <a href="{{ route('nojs.show',$js->nojs) }}" class="btn-show btn-primary btn-sm ml-2 float-right" title="Detail: {{ $js->site }}"><i class="fa fa-eye text-primary text-light"></i></a>
        </li>
        @endforeach
    </ul>
</div>

@push('scripts')
    <script src="{{asset('js/nojs.js')}}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

@endpush

@include('nojs._modal')
@endsection
