class dataSlaPrtg {
    lvdVsat(data) {
        let temp, vsat, dvisat, upvisat, downvisat, site;
        let auth = $('#auth').attr('auth');
        let username = 'Power APT';
        let password = 'APT12345';
        $.ajax({
            url: data.data.url,
            type: "GET",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            data: {
                id: data.data.id_lvdvsat,
                sdate: data.sdate,
                edate: data.edate,
                username: username,
                password: password,
            },
            async: false,
            success: function (response) {
                let parser = new DOMParser();
                let xmlDoc = parser.parseFromString(response, "text/xml");
                let cekData = xmlDoc.childNodes[0].childNodes.length;
                if (cekData > 10) {
                    $(xmlDoc).find('uptimepercent').each(function () {
                        const data = $(this);
                        vsat = (data[0].childNodes[1].data);
                    });
                    $(xmlDoc).find('downtimepercent').each(function () {
                        const data = $(this);
                        dvisat = (data[0].childNodes[1].data);
                    });
                    $(xmlDoc).find('uptime').each(function () {
                        const data = $(this);
                        upvisat = (data[0].childNodes[1].data);
                    });
                    $(xmlDoc).find('downtime').each(function () {
                        const data = $(this);
                        downvisat = (data[0].childNodes[1].data);
                    });
                    $(xmlDoc).find('parentdevicename').each(function () {
                        const data = $(this);
                        site = (data[0].childNodes[1].data.split('SNMP')[0]);
                    });
                } else {
                    vsat = "Error";
                }
            }
        });
        return temp = {
            vsat: vsat,
            dvisat: dvisat,
            upvisat: upvisat,
            downvisat: downvisat,
            site: site
        };
    }

    slaRealtimeVsat(data) {
        let today = new Date();
        let sdate;
        if (data.status == 'day') {
            sdate = `${today.getFullYear()}-${this.format(today.getMonth()+1)}-${this.format(today.getDate())}-00-00`;
        } else {
            sdate = `${today.getFullYear()}-${this.format(today.getMonth()+1)}-01-00-00`;
        }

        let edate = `${today.getFullYear()}-${this.format(today.getMonth()+1)}-${this.format(today.getDate())}-${this.format(today.getHours())}-${this.format(today.getMinutes())}`;
        console.log(sdate);
        console.log(edate);
        console.log();
        return this.lvdVsat({
            data: data,
            sdate: sdate,
            edate: edate
        });

    }

    format(data) {
        return (data < 10) ? `0${data}` : data;
    }
}

export {
    dataSlaPrtg,
};
