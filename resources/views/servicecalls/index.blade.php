@extends('layouts.datatables')

@push('styles1')
    <link href="{{ asset('vendor/datetime/css/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{asset('js/noc/Chartjs.js')}}" defer></script>
@endpush

@section('content1')
    <div class="container mt-3 mb-3" id="url" url="{{ route('servicecalls.index') }}" sla="{{route('apislaprtg')}}" urlnoc="{{ route('nojs.table') }}">
        <div class="card text-white bg-primary">
        <div class="card-header font-weight-bold text-center">
            <a class="btn btn-success" data-toggle="collapse" href="#activedTab" role="button" aria-expanded="false" aria-controls="activedTab" id="serviceopen">Actived Tab</a>
            <a class="btn btn-success" data-toggle="collapse" href="#logTab"  role="button" aria-expanded="false" aria-controls="logTab" id="serviceclose">Chart Tab</a>
        </div>

            <div class="card-body bg-light text-dark">

                <div class="collapse show" id="activedTab">
                <div class="container">

                <table id="activeTable" class="table table-striped table-bordered dt-responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Service Id</th>
                            <th scope="col">Nojs</th>
                            <th scope="col">Site</th>
                            <th scope="col">PMS</th>
                            <th scope="col">Open Time</th>
                            <th scope="col">LC</th>
                            <th scope="col">Mitra</th>
                            <th scope="col">Error</th>
                            <th scope="col">Status</th>
                            <th scope="col">Sla Prtg Day</th>
                            <th scope="col">Sla Prtg Month</th>
                            <th scope="col">Edit</th>
                        </tr>
                    </thead>
                </table>
                <div class="alert alert-warning bg-warning text-dark float-sm-right" id="total">
                </div>
                </div>
            </div>

            <div class="collapse" id="logTab">

                <div class="row justify-content-center" id="edit">
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
                    <div class="form-group col-md-5" id="radio">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="inlineRadioOptions" id="1week" value="1week">
                            <label class="custom-control-label" for="1week">1 Week</label>
                        </div>

                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="inlineRadioOptions" id="2week" value="2week">
                            <label class="custom-control-label" for="2week">2 Week</label>
                        </div>

                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="inlineRadioOptions" id="1month" value="1month">
                            <label class="custom-control-label" for="1month">1 Month</label>
                        </div>

                    </div>

                    <div class="col-md-5 ">
                        <button type="button" class="btn-primary btn-lg" id="btnstart">Start</button>
                    </div>
                </div>

                <div class="mt-4 chart-service">

                </div>

            </div>
            </div>
        </div>
    </div>
@include('nojs._modal')

@endsection

@push('scripts1')
    <script src="{{ asset('vendor/datetime/js/moment.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datetime/js/jquery.datetimepicker.full.js') }}" defer></script>
    <script type="module" src="{{asset('js/servicecall/serviceCalls.js')}}" defer></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}" defer></script>
@endpush
