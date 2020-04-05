@extends('layouts.app')

{{-- @push('styles')
<link href="{{ asset('assets/vendor/datatables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/datatables/DataTables-1.10.20/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
@endpush --}}

@section('content')
{{-- <div class="text-center mt-3">
    <p>
        <a class="btn btn-primary" data-toggle="collapse" href="#activedTab" dism="{{ route('servicecalls.index','status=open') }}" role="button" aria-expanded="false" aria-controls="activedTab" id="serviceopen">
        Actived Tab
        </a>
        <a class="btn btn-primary" data-toggle="collapse" href="#logTab"  dism="{{ route('servicecalls.index','status=closed') }}" role="button" aria-expanded="false" aria-controls="logTab" id="serviceclose">
        Log Tab
        </a>
    </p>

    <div class="collapse" id="activedTab">
        <div class="container">
        <table id="activeTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Service Id</th>
                <th>Nojs</th>
                <th>Site</th>
                <th>Open Time</th>
                <th>LC</th>
                <th>Error</th>
                <th>Status</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Service Id</th>
                <th>Nojs</th>
                <th>Site</th>
                <th>Open Time</th>
                <th>LC</th>
                <th>Error</th>
                <th>Status</th>
            </tr>
            </tfoot>
        </table>
        </div>
    </div>

    <div class="collapse" id="logTab">
        <div class="container">
        <table id="logTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Service Id</th>
                <th>Nojs</th>
                <th>site</th>
                <th>Open Time</th>
                <th>Close Time</th>
                <th>Time To Close</th>
                <th>LC</th>
                <th>Error</th>
                <th>Status</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Service Id</th>
                <th>Nojs</th>
                <th>site</th>
                <th>Open Time</th>
                <th>Close Time</th>
                <th>Time To Close</th>
                <th>LC</th>
                <th>Error</th>
                <th>Status</th>
            </tr>
            </tfoot>
        </table>
        </div>
    </div>

</div> --}}
<h1>ServiceCalls</h1>
@endsection

@push('scripts')

@endpush
