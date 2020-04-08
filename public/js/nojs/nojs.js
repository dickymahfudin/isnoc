let url = $('#tableusers').attr('url');
let table = $('#tableusers').DataTable({
    rowReorder: {
        selector: 'td:nth-child(2)'
    },
    responsive: true,
    procesing: true,
    serverSide: true,
    dom: 'Bfrtip',
    lengthChange: false,
    lengthMenu: [
        [10, 25, 50, -1],
        ['10 rows', '25 rows', '50 rows', 'Show all']
    ],
    buttons: {
        dom: {
            button: {
                tag: 'button',
                className: 'btn-group'
            }
        },
        buttons: [{
                extend: 'pageLength',
                className: 'btn btn-sm btn-secondary mr-2',
                titleAttr: 'Sort',
            },
            {
                extend: 'excel',
                className: 'btn btn-sm btn-success mr-2',
                titleAttr: 'Excel export.',
                text: 'Excel',
                filename: 'excel-export',
                extension: '.xlsx'
            }, {
                extend: 'copy',
                className: 'btn btn-sm btn-primary mr-2',
                titleAttr: 'Copy table data.',
                text: 'Copy'
            }, {
                extend: 'pdf',
                className: 'btn btn-sm btn-warning mr-2',
                titleAttr: 'Pdf export.',
                text: 'Pdf',
                filename: 'pdf-export',
            },
        ]
    },
    drawCallback: function () {
        $('.pagination').addClass("d-flex justify-content-center");
    },
    ajax: url,
    columns: [{
            "data": "nojs"
        },
        {
            "data": "site"
        },
        {
            "data": "lc"
        },
        {
            "data": "mitra"
        },
        {
            "data": "action"
        },
    ]
});


$('body').on('click', '.modal-show', function (e) {
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title');

    $('#modal-title').text(title);
    $('#modal-btn-save').removeClass('d-none').text(me.hasClass('edit') ? 'Update' : 'Create');
    $.ajax({
        url: url,
        dataType: 'html',
        success: function (response) {
            $('#modal-body').html(response);
        }
    });

    $('#modal').modal('show');
});

$('#modal-btn-save').click(function (e) {
    e.preventDefault();

    var form = $('#modal-body form'),
        url = form.attr('action'),
        dism = form.attr('dism'),
        method = $('input[name=_method]').val() == undefined ? 'POST' : 'PUT';

    form.find('.invalid-feedback').remove();
    form.find('.form-control').removeClass('is-invalid');
    $.ajax({
        url: url,
        method: method,
        data: form.serialize(),
        success: function (response) {
            form.trigger('reset');
            $('#modal').modal('hide');
            table.ajax.reload();
            swal({
                type: 'success',
                title: 'Success!',
                text: 'Data has been saved!'
            });
        },
        error: function (xhr) {
            var res = xhr.responseJSON;
            if ($.isEmptyObject(res) == false) {
                $.each(res.errors, function (key, value) {
                    $('#' + key)
                        // .closest('.form-group')
                        .addClass('is-invalid')
                        // .appand('<div class="invalid-feedback">' + value + '</div >')
                        .after('<span class="invalid-feedback"><strong>' + value + '</strong></span>');
                });

            }
        }
    });
});

$('body').on('click', '.btn-delete', function (e) {
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        csrf_token = $('meta[name="csrf-token"]').attr('content'),
        dism = me.attr('dism');

    swal({
        title: 'Are you sure want to delete ' + title + ' ?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: csrf_token
                },
                success: function (response) {
                    table.ajax.reload();
                    swal({
                        type: 'success',
                        title: 'Success!',
                        text: 'Data has been deleted!'
                    });
                },
                error: function (xhr) {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            });
        }
    });
});

$('body').on('click', '.btn-show', function (e) {
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title');

    $('#modal-title').text(title);
    $('#modal-btn-save').addClass('d-none');

    $.ajax({
        url: url,
        dataType: 'html',
        success: function (response) {
            $('#modal-body').html(response);
        }
    });

    $('#modal').modal('show');
});
