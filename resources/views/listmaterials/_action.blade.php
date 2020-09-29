@if (Auth::user()->name === "malek" || Auth::user()->name === "dicky")
    <a href="{{ $url_edit }}" class="modal-show edit" title="Edit {{ $model->name }}"><i class="fa fa-pencil text-inverse"></i></a> |
    <a href="{{ $url_destroy }}" class="btn-delete" title="{{ $model->name }}"><i class="fa fa-trash"></i></a>
@endif
