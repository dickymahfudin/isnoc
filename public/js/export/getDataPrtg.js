class dataSlaPrtg {
    getDataPrtg(data) {
        let temp, vsat, dvisat, upvisat, downvisat, site;
        let auth = $("#auth").attr("auth");
        let username = "Power APT";
        let password = "APT12345";
        $.ajax({
            url: data.url,
            type: "GET",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
            },
            data: {
                id: data.id,
                sdate: data.sdate,
                edate: data.edate,
                username: username,
                password: password
            },
            async: false,
            success: function(response) {
                temp = response;
            }
        });
        return temp;
    }

    slaRealtime(data) {
        let today = new Date();
        let sdate;
        if (data.status == "day") {
            sdate = `${today.getFullYear()}-${this.format(
                today.getMonth() + 1
            )}-${this.format(today.getDate())}-00-00`;
        } else {
            sdate = `${today.getFullYear()}-${this.format(
                today.getMonth() + 1
            )}-01-00-00`;
        }

        let edate = `${today.getFullYear()}-${this.format(
            today.getMonth() + 1
        )}-${this.format(today.getDate())}-${this.format(
            today.getHours()
        )}-${this.format(today.getMinutes())}`;
        return this.getDataPrtg({
            id: data.id,
            url: data.url,
            sdate: sdate,
            edate: edate
        });
    }

    slaLocal(data) {
        let auth = $("#auth").attr("auth");
        let temp;
        $.ajax({
            url: "/api/prtg/sla",
            type: "GET",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
            },
            data: {
                nojs: data
            },
            async: false,
            success: function(response) {
                temp = response;
            },
            error: function(e) {
                temp = e;
            }
        });
        return temp;
    }
    format(data) {
        return data < 10 ? `0${data}` : data;
    }
}

export { dataSlaPrtg };
