{{ Form::model($serviceCall, [
    'route' => ['serviceCall.update', $serviceCall->service_id],
    'method' => 'PUT'

]) }}

    <div class="form-group">
        <label for="" class="control-label">Error</label>
        {{  Form::text('error', null, ['class' => 'form-control', 'id' => 'error'])  }}
    </div>

{{  Form::close()  }}
