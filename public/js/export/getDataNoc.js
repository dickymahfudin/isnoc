class dataSlaNoc {
    getDataNoc(data) {
        let temp;
        let auth = $('#auth').attr('auth');

        $.ajax({
            type: "GET",
            url: data.url,
            "beforeSend": function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            data: {
                nojs: data.nojs,
                sdate: data.sdate,
                edate: data.edate
            },
            async: false,
            dataType: "json",
            success: function (response) {
                temp = response;
            },
            error: function () {
                console.log("error");
                temp = "error";
            }
        });
        return temp;
    }

    getDataJs(data) {
        let temp = [];
        $.ajax({
            type: "GET",
            url: data.url,
            async: false,
            dataType: "json",
            success: function (response) {
                temp = response.data;
                response.data.forEach(data => {
                    $('.selectpicker').append(`<option value="${data.nojs}">${data.nojs} - ${data.site}</option>`);
                });
                $('.selectpicker').selectpicker('refresh');
            },
            error: function () {
                console.log("error");
            }
        });
        return temp;
    }

    getSlaNoc(data) {
        let error = 0;
        let temp;
        let response = this.getDataNoc(data);
        response.forEach(data => {
            if (data.eh1 != null) error++;
        });
        temp = (error / (response.length)) * 100;
        temp = temp.toFixed(2)
        return temp;

    }
}

export {
    dataSlaNoc,
};
