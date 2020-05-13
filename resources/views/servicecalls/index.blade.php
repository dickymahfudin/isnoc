@extends('layouts.datatables')

@section('content1')
    <div class="container mt-3 mb-3" id="url" url="{{ route('servicecalls.index') }}" sla="{{route('apislaprtg')}}">
        <div class="card text-white bg-primary">
        <div class="card-header font-weight-bold text-center">
            <a class="btn btn-success" data-toggle="collapse" href="#activedTab" role="button" aria-expanded="false" aria-controls="activedTab" id="serviceopen">Actived Tab</a>
            <a class="btn btn-success" data-toggle="collapse" href="#logTab"  role="button" aria-expanded="false" aria-controls="logTab" id="serviceclose">Log Tab</a>
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
                </div>
            </div>

            <div class="collapse" id="logTab">
                <div class="container">
                <table id="logTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Service Id</th>
                            <th scope="col">Nojs</th>
                            <th scope="col">site</th>
                            <th scope="col">Open Time</th>
                            <th scope="col">Close Time</th>
                            <th scope="col">Time To Close</th>
                            <th scope="col">LC</th>
                            <th scope="col">Error</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                </table>
                </div>
            </div>
            </div>
        </div>
    </div>
@include('nojs._modal')

@endsection

@push('scripts1')
    <script type="module" src="{{asset('js/servicecall/serviceCalls.js')}}" defer></script>
@endpush
