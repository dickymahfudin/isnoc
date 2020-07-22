@if (Auth::user()->name === "malek" || Auth::user()->name === "dicky")
    <a href="{{ $url_show }}" class="btn-show" title="Detail: {{ $model->name }}"><i class="fa fa-eye" ></i></a> |
    <a href="{{ $url_edit }}" class="modal-show edit" title="Edit {{ $model->name }}"><i class="fa fa-pencil text-inverse"></i></a> |
    <a href="{{ $url_destroy }}" class="btn-delete" title="{{ $model->name }}"><i class="fa fa-trash"></i></a>

    @else
    <a href="{{ $url_show }}" class="btn-show" title="Detail: {{ $model->name }}"><i class="fa fa-eye" ></i></a>
@endif


{{-- <a href="{{ route('nojs.destroy',$js->nojs) }}" class="btn-delete btn-danger ml-2 btn-sm float-right" title="Delete: {{ $js->site }}" dism="{{ route('nojs.index') }}"><i class="fa fa-trash text-danger text-light"></i></a>
<a href="{{ route('nojs.edit',$js->nojs) }}" class="modal-show edit btn-success ml-2 btn-sm float-right" title="Edit {{ $js->site }}"><i class="fa fa-pencil text-inverse text-light"></i></a>
<a href="{{ route('nojs.show',$js->nojs) }}" class="btn-show btn-primary btn-sm ml-2 float-right" title="Detail: {{ $js->site }}"><i class="fa fa-eye text-primary text-light"></i></a> --}}
