function iniciarTabla(id_tabla, url, id_buscar, columnas) {
    let data = $(id_tabla).KTDatatable({
        data: {
            type: "remote",
            source: {
                read: {
                    url: APP_URL + url,
                    map: function (t) {
                        var a = t;
                        return void 0 !== t.data && (a = t.data), a
                    }}
            },
            pageSize: 10,
            serverPaging: !0,
            serverFiltering: !0,
            serverSorting: !0,
            scrollX: true,
        },
        layout: {
         
            footer: !1,
            scrollX: true,
        },
        sortable: !0,
        pagination: !0,
        search: {
            input: $(id_buscar),
            key: "generalSearch"},
        columns: columnas
    });

    return data;
}
