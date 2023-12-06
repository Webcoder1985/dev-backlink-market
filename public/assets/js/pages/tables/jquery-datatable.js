$(function () {
        $('.js-basic-example').DataTable();
        $('.select2').select2();
        if ($('#blogDatatable').length) {
            $('#blogDatatable').DataTable({
                order: [[3, 'desc']],
                stateSave: true,
                stateSaveParams: function (settings, data) {
                    //data.columns="";
                    //console.log(data.columns);
                    var da_range_string = data.columns[1].search.search;
                    if (da_range_string != "") {
                        var da_array = da_range_string.split(",");
                        //console.log(da_array);
                        var da_range = $("#nouislider_da_range").data("ionRangeSlider");
                        da_range.update({
                            from: da_array[0],
                            to: da_array[1]
                        });
                    }

                    //nouislider_pa_range
                    var pa_range_string = data.columns[2].search.search;
                    if (pa_range_string != "") {
                        var pa_array = pa_range_string.split(",");
                        //console.log(pa_array);
                        var pa_range = $("#nouislider_pa_range").data("ionRangeSlider");
                        pa_range.update({
                            from: pa_array[0],
                            to: pa_array[1]
                        });
                    }

                    //nouislider_cf_range
                    var cf_range_string = data.columns[4].search.search;
                    if (cf_range_string != "") {
                        var cf_array = cf_range_string.split(",");
                        //console.log(cf_array);
                        var cf_range = $("#nouislider_cf_range").data("ionRangeSlider");
                        cf_range.update({
                            from: cf_array[0],
                            to: cf_array[1]
                        });
                    }

                    //nouislider_bl_range
                    var bl_range_string = data.columns[5].search.search;
                    if (bl_range_string != "") {
                        var bl_array = bl_range_string.split(",");
                        //console.log(bl_array);
                        var bl_range = $("#nouislider_bl_range").data("ionRangeSlider");
                        bl_range.update({
                            from: bl_array[0],
                            to: bl_array[1]
                        });
                    }

                    //nouislider_rd_range
                    var rd_range_string = data.columns[6].search.search;
                    if (rd_range_string != "") {
                        var rd_array = rd_range_string.split(",");
                        //console.log(rd_array);
                        var rd_range = $("#nouislider_rd_range").data("ionRangeSlider");
                        rd_range.update({
                            from: rd_array[0],
                            to: rd_array[1]
                        });
                    }

                    //nouislider_price_range
                    var price_range_string = data.columns[12].search.search;
                    if (price_range_string != "") {
                        var price_array = price_range_string.split(",");
                        //console.log(price_array);
                        var price_range = $("#nouislider_price_range").data("ionRangeSlider");
                        price_range.update({
                            from: price_array[0],
                            to: price_array[1]
                        });
                    }

                    //nouislider_obl_range
                    var obl_range_string = data.columns[7].search.search;
                    if (obl_range_string != "") {
                        var obl_array = obl_range_string.split(",");
                        //console.log(obl_array);
                        var obl_range = $("#nouislider_obl_range").data("ionRangeSlider");
                        obl_range.update({
                            from: obl_array[0],
                            to: obl_array[1]
                        });
                    }

                    //search keyword
                    var search_keyword_string = data.columns[14].search.search;
                    if (search_keyword_string != "") {
                        $('input#search-keyword').val(search_keyword_string);
                    }

                    //search keyword option
                    var search_keyword_option_string = data.columns[15].search.search;
                    if (search_keyword_option_string != "") {
                        $('select#search-keyword-option').val(search_keyword_option_string);
                        $('.bootstrap-select.search-keyword-option .filter-option').text($('select#search-keyword-option  option:selected').text());
                    }

                    //------add new for Language--------------
                    var lan_range_string = data.columns[9].search.search;
                    if (lan_range_string != "") {
                        var lan_array = lan_range_string.split(",");
                        // console.log('testtttttttttt'+lan_array);
                        // console.log('sec'+lan_range_string);

                        $('select.languages').select2().select2('val', lan_array);
                    }

                    //------add new for Country--------------
                    var country_range_string = data.columns[8].search.search;
                    if (country_range_string != "") {
                        var country_array = country_range_string.split(",");
                        // console.log('testtttttttttt'+country_array);
                        //console.log('sec'+lan_range_string);

                        $('select.countries').select2().select2('val', country_array);
                    }

                    //------add new for TLD--------------
                    var tld_range_string = data.columns[10].search.search;
                    if (tld_range_string != "") {
                        var tld_array = tld_range_string.split(",");
                        // console.log('testtttttttttt'+country_array);
                        //console.log('sec'+lan_range_string);

                        $('select.tlds').select2().select2('val', tld_array);
                    }


                    //------add new for categories--------------
                    var categories_range_string = data.columns[11].search.search;
                    if (categories_range_string != "") {
                        //var  categories_array= categories_range_string.split(",");
                        $.each(categories_range_string.split(","), function (i, e) {
                            $("#filter-categories option[value='" + e + "']").prop("selected", true);
                        });
                        /*for (var i = 0; i < categories_array.length; i++) {
                          $('#filter-categories').val(categories_array[i]);
                        }*/

                        // console.log('testtttttttttt'+country_array);
                        //console.log('sec'+lan_range_string);

                        //$('select.categories').('val',categories_array);
                    }


                    //console.log("Filter categories");
                    //console.log($('#blogDatatable').DataTable().state());
                    $('#filter-categories').searchableOptionList({
                        "showSelectionBelowList": true,
                        events: {
                            onInitialized: function () {

                                $('.sol-optiongroup-label').each(function () {

                                    $(this).siblings().toggleClass("auto-hide");
                                })
                            },
                        }
                    });

                },
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                fixedHeader: {headerOffset: -10},
                colReorder: true,
                oLanguage: {
                    "sInfo": "Showing _TOTAL_ URLs",
                    "sInfoFiltered": ""
                },

                dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'i><'col-sm-12 col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    //  'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: window.location.href,
                },
                columns: [
                    {data: 'seller_site_page_url', name: 'seller_site_page_url', responsivePriority: 2},
                    {data: 'moz_da', name: 'moz_da', searchable: false, responsivePriority: 4},
                    {data: 'moz_pa', name: 'moz_pa', searchable: false, responsivePriority: 5},
                    {data: 'maj_tf', name: 'maj_tf', searchable: false, responsivePriority: 6},
                    {data: 'maj_cf', name: 'maj_cf', searchable: false, responsivePriority: 7},
                    {data: 'maj_bl', name: 'maj_bl', searchable: false, responsivePriority: 8},
                    {data: 'rd', name: 'rd', searchable: false, responsivePriority: 9},
                    {data: 'obl', name: 'obl', searchable: false, responsivePriority: 9},
                    {data: 'country', name: 'country', searchable: false, responsivePriority: 9},
                    {data: 'language', name: 'language', searchable: false, responsivePriority: 9},
                    {data: 'tld', name: 'tld', searchable: false, responsivePriority: 9},
                    {data: 'category', name: 'category', searchable: false, sortable: false, responsivePriority: 9},
                    //   { data: 'is_active', name: 'is_active', searchable: false },
                    {data: 'page_price_buyer', name: 'page_price_buyer', searchable: false, responsivePriority: 3},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false, responsivePriority: 1},
                    {data: 'title', name: 'title', searchable: false, sortable: false, "visible": false},
                    {data: 'content', name: 'content', searchable: false, sortable: false, "visible": false}
                ],
                columnDefs: [
                    //{ responsivePriority: 2, targets: [-2, -1] },
                    //  { responsivePriority: 10, targets: 12 }, // Price
                    //  { responsivePriority: 11, targets: 13 }, // Add to Card Button
                    {
                        "targets": [14, 15],
                        "visible": false
                    }
                ]
            });
        }
        $(".search_box").on('change', function () {
            if ($('#OrderDatatable').length) {
                orderDrawDatatable();
            }

        });
        if ($('#OrderDatatable').length) {
            $('#OrderDatatable').DataTable({
                order: [[0, 'desc']],
                stateSave: true,
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'><'col-sm-12 col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: window.location.href,
                },
                columns: [
                    {data: 'order_id', name: 'order_id'},
                    {data: 'ca', name: 'ca', searchable: false},
                    {data: 'buyer_name', name: 'buyer_name', orderable: false, sortable: false, searchable: false},
                    {
                        data: 'seller_full_name',
                        name: 'seller_full_name',
                        orderable: false,
                        sortable: false,
                        searchable: false
                    },
                    {data: 'page_url', name: 'page_url', orderable: false, sortable: false, searchable: false},
                    {data: 'page_pay_price', name: 'page_pay_price', searchable: false},
                    {data: 'status', name: 'status', orderable: false, sortable: false, searchable: false},
                    {data: 'action', name: 'action', searchable: false, orderable: false, sortable: false}

                ],
                columnDefs: []
            });
        }
        if ($('#blogPagesDatatable').length) {
            var blogtable = $('#blogPagesDatatable').DataTable({
                order: [[0, 'desc']],
                stateSave: false,
                stateSaveParams: function (settings, data) {
                    //for (var i = 0;i < data.columns.length; i++){
                    //     delete data.columns[0].search;
                    //     }
                },
                processing: true,
                serverSide: true,
                responsive: false,
                autoWidth: false,
                dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'><'col-sm-12 col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: window.location.href,

                },
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'seller_site_page_url', name: 'seller_site_page_url'},
                    {data: 'moz_da', name: 'moz_da', searchable: false},
                    {data: 'moz_pa', name: 'moz_pa', searchable: false},
                    {data: 'maj_tf', name: 'maj_tf', searchable: false},
                    {data: 'maj_cf', name: 'maj_cf', searchable: false},
                    {data: 'maj_bl', name: 'maj_bl', searchable: false},
                    {data: 'rd', name: 'rd', searchable: false},
                    {data: 'obl', name: 'obl', searchable: false},
                    {data: 'language', name: 'language', searchable: false},
                    {data: 'category', name: 'category', searchable: false},
                    {data: 'indexed', name: 'Indexed', searchable: false},
                    {data: 'is_active', name: 'is_active', searchable: false},
                    {data: 'page_price_seller', name: 'page_price_seller', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, orderable: false},
                    {
                        data: 'select_pages',
                        name: 'select_pages',
                        searchable: false,
                        sortable: false,
                        className: 'selectall-checkbox'
                    }
                ],
                columnDefs: [],
                "fnDrawCallback": function (oSettings) {
                    jQuery("body").find(".show-tooltip").tooltip({
                        container: "body",
                        trigger: "hover",
                        placement: "bottom"
                    })
                }
            });

            // On DataTables select / deselect event check / uncheck all checkboxes. And deal with the checkbox
            // in the thead (check or uncheck).
            blogtable.on('select.dt deselect.dt', function (e, dt, type, indexes) {
                var countSelectedRows = blogtable.rows({selected: true}).count();
                var countItems = blogtable.rows().count();

                if (countItems > 0) {
                    if (countSelectedRows == countItems) {
                        $('thead .selectall-checkbox input[type="checkbox"]', this).prop('checked', true);
                    } else {
                        $('thead .selectall-checkbox input[type="checkbox"]', this).prop('checked', false);
                    }
                }

                if (e.type === 'select') {
                    $('.selectall-checkbox input[type="checkbox"]', blogtable.rows({selected: true}).nodes()).prop('checked', true);
                } else {
                    $('.selectall-checkbox input[type="checkbox"]', blogtable.rows({selected: false}).nodes()).prop('checked', false);
                }
            });

            // When clicking on "thead .selectall-checkbox", trigger click on checkbox in that cell.
            blogtable.on('click', 'thead .selectall-checkbox', function () {
                $('input[type="checkbox"]', this).trigger('click');
            });


            // When clicking on the checkbox in "thead .selectall-checkbox", define the actions.
            blogtable.on('click', 'thead .selectall-checkbox input[type="checkbox"]', function (e) {
                if (this.checked) {
                    blogtable.rows().select();
                } else {
                    blogtable.rows().deselect();
                }

                e.stopPropagation();
            });
        }

        $('#usersDatatable').DataTable({
            order: [[0, 'desc']],
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'><'col-sm-12 col-md-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            ajax: {
                url: window.location.href,
                data: function (d) {
                    d.fID = $('#filterID').val();
                    d.fEmail = $('#filterEmail').val();
                    d.fFirstName = $('#filterFirstName').val();
                    d.fLastName = $('#filterLastName').val();
                    d.fCountry = $('#filterCountry').val();
                    d.fStatus = $('#filterStatus').val();

                },
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'email', name: 'email'},
                {data: 'balance', name: 'balance'},
                {data: 'user_status', name: 'user_status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'registration_ip', name: 'registration_ip'},
                {data: 'last_login_ip', name: 'last_login_ip'},
                {data: 'country', name: 'country'},
                {data: 'notes', name: 'notes'},
                {data: 'action', name: 'action', searchable: false, sortable: false},

            ],
            columnDefs: [
                {responsivePriority: 1, targets: -1}
            ]
        });

        $('#sitesDatatable').DataTable({
            order: [[5, 'desc']],
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'><'col-sm-12 col-md-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [],
            ajax: {
                url: window.location.href,
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'site_url', name: 'site_url'},
                {data: 'site_auth_key', name: 'site_auth_key'},
                {data: 'plugin_version', name: 'plugin_version'},
                {data: 'is_active', name: 'is_active'},
                {data: 'created_at', name: 'created_at'},
                {data: 'pages', name: 'pages', sortable: false},
                {data: 'action', name: 'action', searchable: false, sortable: false},


            ],
            columnDefs: [
                {responsivePriority: 1, targets: -1},
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ]
        });

        //Admin seller blog page
        $('#adminSitesDatatable').DataTable({
            order: [[0, 'desc']],
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'><'col-sm-12 col-md-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            ajax: {
                url: window.location.href,
                data: function (d) {
                    d.fUserID = $('#filterUserID').val();
                    d.fDomain = $('#filterDomain').val();
                    d.fStatus = $('#filterStatus').val();
                    d.fBanStatus = $('#filterBanStatus').val();
                },
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'seller_id', name: 'seller_id'},
                {data: 'site_url', name: 'site_url'},
                {data: 'is_active', name: 'is_active'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', searchable: false, sortable: false},

            ],
            columnDefs: [
                {responsivePriority: 1, targets: -1},
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ]
        });

        if ($('#IncomeDatatable').length) {
            $("#Search_Btn").click(function () {
                if ($('#IncomeDatatable').length) {
                    incomeDrawDatatable();
                    $('.dt-buttons').show();
                }

            });
            $('#IncomeDatatable').DataTable({
                order: [[0, 'desc']],
                stateSave: false,
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                paging: false,
                dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Download Excel',
                        filename: function () {
                            return new Date($('#order_date_start').val()).getMonth() + 1 + '_' + new Date($('#order_date_start').val()).getFullYear() + '_Umsätze_blmkt';
                        },
                        title: function () {
                            const monthNames = ["Januar", "Februar", "März", "April", "Mai", "Juni",
                                "Juli", "August", "September", "Oktober", "November", "Dezember"
                            ];
                            var d = new Date($('#order_date_start').val())
                            return monthNames[d.getMonth()] + ' ' + d.getFullYear() + ' Umsätze BLMKT';
                        }
                    }
                ],
                ajax: {
                    url: window.location.href
                },
                columns: [
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'nachname', name: 'nachname', searchable: false},
                    {data: 'umsatz_brutto', name: 'umsatz_brutto', searchable: false},
                    {data: 'umsatz_netto', name: 'umsatz_netto', searchable: false},
                    {data: 'ust', name: 'ust', searchable: false},
                    {data: 'steuersatz', name: 'steuersatz', searchable: false},
                    {data: 'gebuehren', name: 'gebuehren', searchable: false},
                    {data: 'land', name: 'land', searchable: false},
                    {data: 'kategorie', name: 'kategorie', searchable: false},
                    {data: 'steuernummer', name: 'steuernummer', searchable: false}

                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(2,)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page

                    // Update footer

                    $('#calc').html('Total: €' + total);
                },
            });
        }
        //Range Example
        if ($('#nouislider_da_range')[0]) {
            var rangeSlider = $('#nouislider_da_range');
            if (rangeSlider) {
                rangeSlider.ionRangeSlider({
                    type: "double",
                    grid: true,
                    min: da_min,
                    max: da_max,
                    step: 1,
                    onChange: function () {
                        drawDatatable();
                    }
                });
            }

        }


        if ($('#nouislider_pa_range')[0]) {
            var paRangeSlider = $('#nouislider_pa_range');
            if (paRangeSlider) {
                paRangeSlider.ionRangeSlider({
                    type: "double",
                    grid: true,
                    min: pa_min,
                    max: pa_max,
                    step: 1,
                    onChange: function (data) {
                        drawDatatable(data, false);
                    }
                });
            }
        }
        if ($('#nouislider_tf_range')[0]) {
            var tfRangeSlider = $('#nouislider_tf_range');
            if (tfRangeSlider) {
                tfRangeSlider.ionRangeSlider({
                    type: "double",
                    grid: true,
                    min: tf_min,
                    max: tf_max,
                    step: 1,
                    onChange: function (data) {
                        drawDatatable(data, false);
                    }
                });
            }
        }
        if ($('#nouislider_cf_range')[0]) {
            var cfRangeSlider = $('#nouislider_cf_range');
            if (cfRangeSlider) {
                cfRangeSlider.ionRangeSlider({
                    type: "double",
                    grid: true,
                    min: cf_min,
                    max: cf_max,
                    step: 1,
                    onChange: function (data) {
                        drawDatatable(data, false);
                    }
                });
            }
        }
        if ($('#nouislider_bl_range')[0]) {
            var blRangeSlider = $('#nouislider_bl_range');
            if (blRangeSlider) {
                blRangeSlider.ionRangeSlider({
                    type: "double",
                    grid: true,
                    min: bl_min,
                    max: bl_max,
                    step: 1,
                    onChange: function (data) {
                        drawDatatable(data, false);
                    }
                });
            }
        }
        if ($('#nouislider_price_range')[0]) {
            var priceRangeSlider = $('#nouislider_price_range');
            if (priceRangeSlider) {
                priceRangeSlider.ionRangeSlider({
                    type: "double",
                    grid: true,
                    min: price_min,
                    max: price_max,
                    step: 1,
                    prefix: "$",
                    onChange: function (data) {
                        drawDatatable(data, false);
                    }
                });
            }
        }
        if ($('#nouislider_obl_range')[0]) {
            var oblRangeSlider = $('#nouislider_obl_range');
            if (oblRangeSlider) {
                oblRangeSlider.ionRangeSlider({
                    type: "double",
                    grid: true,
                    min: obl_min,
                    max: obl_max,
                    step: 1,
                    onChange: function (data) {
                        drawDatatable(data, false);
                    }
                });
            }
        }
        if ($('#nouislider_rd_range')[0]) {
            var rdRangeSlider = $('#nouislider_rd_range');
            if (rdRangeSlider) {
                rdRangeSlider.ionRangeSlider({
                    type: "double",
                    grid: true,
                    min: rd_min,
                    max: rd_max,
                    step: 1,
                    onChange: function (data) {
                        drawDatatable(data, false);
                    }
                });
            }
        }
        $(".select2").on('change', function () {
            if ($('#blogDatatable').length) {
                drawDatatable();
            }
            if ($('#blogPagesDatatable').length) {
                drawDatatable();
            }
        });

        $('#reset-filter').on('click', function () {
            var table = $('#blogDatatable').DataTable();
            /*var oldpagelen=table.page.len();
            table.state.clear().draw();
            table.page.len(oldpagelen).draw();*/
            table.columns().search('').draw();
            //$('#blogDatatable').DataTable().state.columns.length=0
            //$('#blogDatatable').DataTable().state.clear().columns();
            setTimeout(function () {
                window.location.reload();
            }, 500);
        });

    }
)
;

function drawDatatable(range_min, range_max) {
    var rangeSlider = $('#nouislider_da_range').val().split(/(?:;| )+/);
    var range_slider_min = parseFloat(rangeSlider[0]).toFixed(2);
    var range_slider_max = parseFloat(rangeSlider[1]).toFixed(2);

    //==================================================================
    var paRangeSlider = $('#nouislider_pa_range').val().split(/(?:;| )+/);
    var pa_slider_min = parseFloat(paRangeSlider[0]).toFixed(2);
    var pa_slider_max = parseFloat(paRangeSlider[1]).toFixed(2);

    //=================================================================
    var tfRangeSlider = $('#nouislider_tf_range').val().split(/(?:;| )+/);
    var tf_slider_min = parseFloat(tfRangeSlider[0]).toFixed(2);
    var tf_slider_max = parseFloat(tfRangeSlider[1]).toFixed(2);

    //=================================================================
    var cfRangeSlider = $('#nouislider_cf_range').val().split(/(?:;| )+/);
    var cf_slider_min = parseFloat(cfRangeSlider[0]).toFixed(2);
    var cf_slider_max = parseFloat(cfRangeSlider[1]).toFixed(2);

    //=================================================================
    var rdRangeSlider = $('#nouislider_rd_range').val().split(/(?:;| )+/);
    var rd_slider_min = parseFloat(rdRangeSlider[0]).toFixed(2);
    var rd_slider_max = parseFloat(rdRangeSlider[1]).toFixed(2);

    //=================================================================
    var oblRangeSlider = $('#nouislider_obl_range').val().split(/(?:;| )+/);
    var obl_slider_min = parseFloat(oblRangeSlider[0]).toFixed(2);
    var obl_slider_max = parseFloat(oblRangeSlider[1]).toFixed(2);

    //=================================================================
    var priceRangeSlider = $('#nouislider_price_range').val().split(/(?:;| )+/);
    var price_slider_min = parseFloat(priceRangeSlider[0]);
    var price_slider_max = parseFloat(priceRangeSlider[1]);

    //=================================================================
    var blRangeSlider = $('#nouislider_bl_range').val().split(/(?:;| )+/);
    var bl_slider_min = parseFloat(blRangeSlider[0]);
    var bl_slider_max = parseFloat(blRangeSlider[1]);


    //$('#blogDatatable').DataTable().column(1).search([range_slider_min, range_slider_max]).column(2).search([pa_slider_min, pa_slider_max]).column(3).search([tf_slider_min, tf_slider_max]).column(4).search([cf_slider_min, cf_slider_max]).column(5).search([rd_slider_min, rd_slider_max]).column(6).search([obl_slider_min, obl_slider_max]).column(12).search([price_slider_min, price_slider_max]).column(8).search($("select.languages").val()).column(9).search($("select.tlds").val()).column(10).search($("select.categories").val()).column(7).search($("select.countries").val()).draw();

    $('#blogDatatable').DataTable().column(1).search([range_slider_min, range_slider_max]).column(2).search([pa_slider_min, pa_slider_max]).column(3).search([tf_slider_min, tf_slider_max]).column(4).search([cf_slider_min, cf_slider_max]).column(5).search([bl_slider_min, bl_slider_max]).column(6).search([rd_slider_min, rd_slider_max]).column(12).search([price_slider_min, price_slider_max]).column(7).search([obl_slider_min, obl_slider_max]).column(8).search($("select.countries").val()).column(9).search($("select.languages").val()).column(10).search($("select.tlds").val()).column(11).search($("select.categories").val()).column(14).search($("input#search-keyword").val()).column(15).search($("select#search-keyword-option").val()).draw();

    $('#blogPagesDatatable').DataTable().column(2).search([range_slider_min, range_slider_max]).column(3).search([pa_slider_min, pa_slider_max]).column(4).search([tf_slider_min, tf_slider_max]).column(5).search([cf_slider_min, cf_slider_max]).column(7).search([rd_slider_min, rd_slider_max]).column(8).search([obl_slider_min, obl_slider_max]).column(13).search([price_slider_min, price_slider_max]).column(9).search($("select.languages").val()).column(10).search($("select.categories").val()).column(12).search($("select.is_active").val()).draw();
}

//Get noUISlider Value and write on
function getNoUISliderValue(slider, percentage) {
    slider.noUiSlider.on('update', function () {
        var val = slider.noUiSlider.get();
        if (percentage) {
            val = parseInt(val);
            val += '%';
        }
        $(slider).parent().find('span.js-nouislider-value').text(val);
    });
}

function orderDrawDatatable() {
    $('#OrderDatatable').DataTable().column(0).search($("#order_id").val()).column(1).search($("#buyer_name").val()).column(2).search($("#order_date").val()).draw();
}

function incomeDrawDatatable() {
    $('#IncomeDatatable').DataTable().column(0).search([$("#order_date_start").val(), $("#order_date_end").val()]).draw();

}