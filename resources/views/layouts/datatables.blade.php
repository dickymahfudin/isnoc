@extends('layouts.app')

@push('styles')
    <link href="{{ asset('vendor/datatables/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @stack('styles1')
@endpush

@section('content')
     @yield('content1')
@endsection

@push('scripts')
    {{-- <script src=https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js></script> --}}

    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}" defer></script>
    <script src="{{ asset('vendor/datatables/js/dataTables.buttons.min.js') }}" defer></script>

    <script src="{{ asset('vendor/datatables/js/dataTables.responsive.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datatables/js/buttons.bootstrap4.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datatables/js/jszip.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datatables/js/pdfmake.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datatables/js/vfs_fonts.js') }}" defer></script>

    <script src="{{ asset('vendor/datatables/js/buttons.html5.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datatables/js/buttons.print.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datatables/js/buttons.colVis.min.js') }}" defer></script>
    <script src="{{ asset('vendor/datatables/js/responsive.bootstrap4.min.js') }}" defer></script>


    {{-- <script src=https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js></script>
    <script src=https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js></script>
    <script src=https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js></script>
    <script src=https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js></script>


    <script src=https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js></script>
    <script src=https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js></script>
    <script src=https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js></script>
    <script src=https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap4.min.js></script>
    <script src=https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js></script>
    <script src=https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js></script>
    <script src=https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js></script>
    <script src=https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js></script>
    <script src=https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js></script>
    <script src=https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js></script> --}}
    @stack('scripts1')
@endpush
