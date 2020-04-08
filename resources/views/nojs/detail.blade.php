
@extends('layouts.datatables')

@push('styles1')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.12/css/bootstrap-select.min.css">
    <link href="{{ asset('css/nojs/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datetime/css/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/nojs/bootstrap-select.min.js') }}" defer></script>
@endpush

@section('content1')
    <div class="container mt-3 mb-3">
        <div class="card text-white bg-primary">
        <div class="card-header font-weight-bold">Nojs Details
        </div>

            <div class="card-body bg-light text-dark">
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
                        <select  data-live-search="true" class="form-control selectpicker h-50 d-inline-block" data-size="5" data-style="btn-primary" title="NOJS" url="{{ route('nojs.table') }}" urllog="{{ route('noc.logger') }}">
                        </select>
                    </div>

                    <div class="col-md-5 ">
                        <button type="button" class="btn btn-primary btn-lg" id="btnstart">Start</button>
                    </div>
                </div>

                <div class="row justify-content-center d-none" id="detail">
                    <div class="col-md-6 mt-3">
                        <div class="card ">
                            <div class="card-body">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>Nojs</th>
                                            <th id="nojs"></th>
                                        </tr>
                                        <tr>
                                            <th>Site</th>
                                            <th id="site"></th>
                                        </tr>
                                        <tr>
                                            <th>Mitra</th>
                                            <th id="mitra"></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container mt-5" id="datatable">

                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts1')
    <script src="{{ asset('js/nojs/detail.js') }}" defer></script>
    <script src="{{ asset('vendor/datetime/js/moment.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datetime/js/jquery.datetimepicker.full.js') }}" defer></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}" defer></script>
@endpush


