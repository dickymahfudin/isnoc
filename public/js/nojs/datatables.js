let table = $('#tableusers'),
    url = table.attr('url');
table.DataTable({
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
                className: ''
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
