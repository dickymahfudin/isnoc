{{ Form::model($datanojs, [
    'route' => $datanojs->exists ? ['nojs.update', $datanojs->nojs] : 'nojs.store',
    'method' => $datanojs->exists ? 'PUT' : 'POST',
    'dism' => route('nojs.index')
    // 'route' => 'nojs.store',
    // 'method' => 'POST'
]) }}

    <div class="form-group">
        <label for="" class="control-label">Nojs</label>
        {{  Form::text('nojs', null, ['class' => 'form-control', 'id' => 'nojs'])  }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Site</label>
        {{  Form::text('site', null, ['class' => 'form-control', 'id' => 'site'])  }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Provinsi</label>
        {{  Form::text('provinsi', null, ['class' => 'form-control', 'id' => 'provinsi'])  }}
    </div>

    <div class="form-group">
        <label for="lc">LC</label>
     {{  Form::select('lc', ['TELKOM' => 'TELKOM', 'IFORTE' => 'IFORTE', 'PSN' => 'PSN', 'IPT' => 'IPT'], null,['class'=>'form-control','placeholder' => '', 'id' => 'lc'])  }}
    </div>

    <div class="form-group">
        <label for="mitra">MITRA</label>
     {{  Form::select('mitra', ['Valtel' => 'Valtel', 'Ecom' => 'Ecom', 'Abbasy' => 'Abbasy', 'Fastech' => 'Fastech', 'Lindu' => 'Lindu',  ], null,['class'=>'form-control','placeholder' => '', 'id' => 'mitra'])  }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Ip</label>
        {{  Form::text('ip', null, ['class' => 'form-control', 'id' => 'ip'])  }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Latitude</label>
        {{  Form::text('latitude', null, ['class' => 'form-control', 'id' => 'latitude'])  }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Longitude</label>
        {{  Form::text('longitude', null, ['class' => 'form-control', 'id' => 'longitude'])  }}
    </div>



{{  Form::close()  }}
