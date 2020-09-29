{{ Form::model($listMaterial, [
    'route' => $listMaterial->exists ? ['material.update', $listMaterial->id] : 'material.store',
    'method' => $listMaterial->exists ? 'PUT' : 'POST',
    'dism' => route('material.index')
]) }}

    <div class="form-group">
        <label for="" class="control-label">Nama Barang</label>
        {{  Form::text('nama_barang', null, ['class' => 'form-control', 'id' => 'nama_barang'])  }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Serial</label>
        {{  Form::text('serial', null, ['class' => 'form-control', 'id' => 'serial'])  }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Jumlah Barang</label>
        {{  Form::text('jumlah_barang', null, ['class' => 'form-control', 'id' => 'jumlah_barang'])  }}
    </div>

    <div class="form-group">
        <label for="mitra">Mitra</label>
     {{  Form::select('mitra', ['Valtel' => 'Valtel', 'Ecom' => 'Ecom', 'Abbasy' => 'Abbasy', 'Fastech' => 'Fastech', 'Lindu' => 'Lindu',  ], null,['class'=>'form-control','placeholder' => '', 'id' => 'mitra'])  }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Tanggal Keluar</label>
        {{  Form::date('tanggal_keluar',  null, ['class' => 'form-control', 'id' => 'tanggal_keluar'])   }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Tanggal Terima</label>
        {{  Form::date('tanggal_terima',  null, ['class' => 'form-control', 'id' => 'tanggal_terima'])   }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Tanggal Pemasangan</label>
        {{  Form::date('tanggal_pemasangan',  null, ['class' => 'form-control', 'id' => 'tanggal_pemasangan'])   }}
    </div>


    <div class="form-group">
        <label for="" class="control-label">Nojs</label>
        {{  Form::text('nojs', null, ['class' => 'form-control', 'id' => 'nojs'])  }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Site</label>
        {{  Form::text('site', null, ['class' => 'form-control', 'id' => 'site'])  }}
    </div>

    <div class="form-group">
        <label for="" class="control-label">Teknisi</label>
        {{  Form::text('teknisi', null, ['class' => 'form-control', 'id' => 'teknisi'])  }}
    </div>

    <div class="form-group">
        <label for="status">Status</label>
     {{  Form::select('status', ['MASUK' => 'MASUK', 'KELUAR' => 'KELUAR'  ], null,['class'=>'form-control','placeholder' => '', 'id' => 'status'])  }}
    </div>

{{  Form::close()  }}
