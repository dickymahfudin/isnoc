
@extends('layouts.datatables')

@push('styles1')
    <link href="{{ asset('css/nojs/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datetime/css/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/nojs/bootstrap-select.min.js') }}" defer></script>
@endpush

@section('content1')
    <div class="container mt-3 mb-3">
        <div class="card text-white bg-primary">
        <div class="card-header font-weight-bold">
        </div>
            <div class="card-body bg-light text-dark">

                @if ( Auth::user()->name === "malek" || Auth::user()->name === "dicky")
                    <div class="row justify-content-center">
                        <div class="col-md-5 col-sm-6 mb-3">
                            <form action="{{route('ajn.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="file[]" multiple>
                                    <button type="submit" class="btn btn-primary">import</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-5 col-sm-6 mb-3">
                            @if (session('status'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong> {{session('status')}} </strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if (isset($errors) && $errors->any())
                                {{-- <div class="alert alert-danger" role="alert">
                                    @foreach ($errors->all() as $error)
                                        {{$error}}
                                    @endforeach
                                </div> --}}

                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>
                                        @foreach ($errors->all() as $error)
                                            {{$error}}
                                        @endforeach
                                    </strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="row justify-content-center">
                    <div class="input-group-prepend col-md-5 col-sm-6 mb-3">
                        <button type="button" id="toggleStart" class="input-group-text"><i class="fa fa-calendar"></i></button>
                        <input type="text" id="start" class="form-control start " placeholder="START" value="">
                    </div>

                    <div class="input-group-prepend col-md-5 col-sm-6 mb-3">
                        <button type="button" id="toggleEnd" class="input-group-text"><i class="fa fa-calendar"></i></button>
                        <input type="text" id="end" class="form-control end start" placeholder="END" value="">
                    </div>
                </div>


                <div class="row justify-content-center">
                    <div class="form-group col-md-5 ">
                        <select  data-live-search="true" class="form-control selectpicker h-50 d-inline-block" data-size="5" data-style="btn-primary" title="SITE">
                        </select>
                    </div>

                    <div class="col-md-5 ">
                        <button type="button" class="btn btn-primary btn-lg" id="btnstart">Start</button>
                    </div>
                </div>

                <div class="row justify-content-center mt-3 d-none" id="collapsebtn">
                    <div class="col-md-5">
                        <a class="btn btn-coll btn-success" data-toggle="collapse" href="#data-daily"  role="button" aria-expanded="false" id="daily">Daily</a>
                        <a class="btn btn-coll btn-success" data-toggle="collapse" href="#data-logger" role="button" aria-expanded="false" id="loggers">Logger</a>
                    </div>
                    <div class="col-md-5">
                    </div>


                    <div class="collapse show active container mt-5" id="data-daily">
                    </div>

                    <div class="collapse container mt-5" id="data-logger">
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@push('scripts1')
    <script type="module" src="{{ asset('js/other/ajn.js') }}" defer></script>
    <script src="{{ asset('vendor/datetime/js/moment.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datetime/js/jquery.datetimepicker.full.js') }}" defer></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}" defer></script>
@endpush
