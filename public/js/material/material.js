import { dataTables } from "../export/dataTables.js";

$(document).ready(function() {
    let dataTable = new dataTables();
    let url = $("#alllist").attr("url");
    let cadangan = $("#table-cadangan").attr("cadangan");

    let table = dataTable.tables({
        id: "#alllist",
        ajax: url,
        columns: [
            { data: "nama_barang" },
            { data: "serial" },
            { data: "jumlah_barang" },
            { data: "mitra" },
            { data: "tanggal_keluar" },
            { data: "tanggal_terima" },
            { data: "tanggal_pemasangan" },
            { data: "nojs" },
            { data: "site" },
            { data: "teknisi" },
            { data: "status" },
            { data: "action" }
        ]
    });

    let tableCadangan = dataTable.tables({
        id: "#table-cadangan",
        ajax: {
            type: "GET",
            url: cadangan,
            dataType: "json",
            dataSrc: function(json) {
                return json;
            }
        },
        columns: [
            { data: "nama_barang" },
            { data: "serial" },
            { data: "jumlah_barang" },
            { data: "mitra" },
            { data: "tanggal_keluar" },
            { data: "tanggal_terima" },
            { data: "tanggal_pemasangan" },
            { data: "nojs" },
            { data: "site" },
            { data: "teknisi" },
            { data: "status" }
        ]
    });

    $(".toggle").click(function(e) {
        $(".active").removeClass("active");
        $(this).addClass("active");

        $(".show").removeClass("show");
        $(this.collapse).addClass("show");
    });

    $("body").on("click", ".modal-show", function(e) {
        e.preventDefault();

        let me = $(this),
            url = me.attr("href"),
            title = me.attr("title");

        $("#modal-title").text(title);
        $("#modal-btn-save")
            .removeClass("d-none")
            .text(me.hasClass("edit") ? "Update" : "Create");
        $.ajax({
            url: url,
            dataType: "html",
            success: function(response) {
                $("#modal-body").html(response);
            }
        });

        $("#modal").modal("show");
    });

    $("#modal-btn-save").click(function(e) {
        e.preventDefault();

        let form = $("#modal-body form"),
            url = form.attr("action"),
            dism = form.attr("dism"),
            method =
                $("input[name=_method]").val() == undefined ? "POST" : "PUT";

        form.find(".invalid-feedback").remove();
        form.find(".form-control").removeClass("is-invalid");
        $.ajax({
            url: url,
            method: method,
            data: form.serialize(),
            success: function(response) {
                form.trigger("reset");
                $("#modal").modal("hide");
                table.ajax.reload();
                tableCadangan.ajax.reload();
                swal({
                    type: "success",
                    title: "Success!",
                    text: "Data has been saved!"
                });
            },
            error: function(xhr) {
                let res = xhr.responseJSON;
                if ($.isEmptyObject(res) == false) {
                    $.each(res.errors, function(key, value) {
                        $("#" + key)
                            // .closest('.form-group')
                            .addClass("is-invalid")
                            // .appand('<div class="invalid-feedback">' + value + '</div >')
                            .after(
                                '<span class="invalid-feedback"><strong>' +
                                    value +
                                    "</strong></span>"
                            );
                    });
                }
            }
        });
    });

    $("body").on("click", ".btn-delete", function(e) {
        e.preventDefault();

        let me = $(this),
            url = me.attr("href"),
            title = me.attr("title"),
            csrf_token = $('meta[name="csrf-token"]').attr("content"),
            dism = me.attr("dism");

        swal({
            title: "Are you sure want to delete " + title + " ?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then(result => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: "DELETE",
                        _token: csrf_token
                    },
                    success: function(response) {
                        table.ajax.reload();
                        tableCadangan.ajax.reload();
                        swal({
                            type: "success",
                            title: "Success!",
                            text: "Data has been deleted!"
                        });
                    },
                    error: function(xhr) {
                        swal({
                            type: "error",
                            title: "Oops...",
                            text: "Something went wrong!"
                        });
                    }
                });
            }
        });
    });

    $("body").on("click", ".btn-show", function(e) {
        e.preventDefault();

        let me = $(this),
            url = me.attr("href"),
            title = me.attr("title");

        $("#modal-title").text(title);
        $("#modal-btn-save").addClass("d-none");

        $.ajax({
            url: url,
            dataType: "html",
            success: function(response) {
                $("#modal-body").html(response);
            }
        });

        $("#modal").modal("show");
    });
});
