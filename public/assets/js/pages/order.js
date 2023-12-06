$(document).on('click', '.btn-expand-col', function () {
    $(this).children('.zmdi-minus, .zmdi-plus').toggleClass("zmdi-minus zmdi-plus");
    var id = $(this).attr('id');
    var table_id = $(this).closest('table').attr('id'); // table ID
    if ($("#" + table_id + " .tr_row_" + id).length > 0) {
        $("#" + table_id + " .tr_row_" + id).remove();
    } else {
        var pu = $(this).attr('pu');
        var ant = $(this).attr('ant');
        var aft = $(this).attr('aft');
        var bet = $(this).attr('bet');
        var msg = '<tr class="tr-hide tr_row_' + id + '"><td colspan="7" style="text-align: left !important;">Promoted URL : ' + pu + '<br/>Anchor Text : ' + ant + '<br/>Link Content : ' + bet +'</td></tr>';
        $("#" + table_id + " #tr_row_" + id).after(msg);
    }
});

$(document).on('click', '.btn-expand-col-orderdetails', function () {
    $(this).children('.zmdi-minus, .zmdi-plus').toggleClass("zmdi-minus zmdi-plus");
    var id = $(this).attr('id');
    var table_id = $(this).closest('table').attr('id'); // table ID
    var orderDetailsTable = $("#orderDetails-" + id);
    if (orderDetailsTable.length > 0) {
        orderDetailsTable.remove();
    } else {
        var pu = $(this).attr('pu');
        var msg = $(this).attr('pu');
        $("#" + table_id + " #tr_row_" + id).after(msg);
    }
});

$(document).on('click', '.btn-admin-expand-col', function () {
    $(this).children('.zmdi-minus, .zmdi-plus').toggleClass("zmdi-minus zmdi-plus");
    var id = $(this).attr('id');
    if ($(".tr_row_" + id).length > 0) {
        $(".tr_row_" + id).remove();
    } else {
        var $curRow = $(this).closest('tr');

        var pu = $(this).attr('pu');
        var ant = $(this).attr('ant');
        var aft = $(this).attr('aft');
        var bet = $(this).attr('bet');
        var msg = '<tr class="tr-hide tr_row_' + id + '"><td colspan="7" style="text-align: left !important;">Promoted URL : ' + pu + '<br/>Anchor Text : ' + ant + '<br/>Before Text : ' + bet +'</td></tr>';
        $curRow.after(msg);
    }
});

function stopSubscription(params) {
    swal({
        title: "Stop Link Placement?",
        icon: "error",
        text: "This cannot be revoked. Earnings will be lost.",
        cancel: "Cancel",
        confirm: "Confirm",
        buttons: {
            cancel: true,
            confirm: "Confirm"
        },
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'GET',
                url: base_url + '/stopsubscription/' + params,
                success: function (data) {
                    swal({
                        title: "Success!",
                        icon: "success",
                        text: "Order Paused successfully.",
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                }
            });
        }
    });
}

function buyerStopSubscription(link_id) {
    swal({
        title: "Stop Link Placement?",
        icon: "error",
        text: "This cannot be revoked. Only a partial refund will be given.",
        cancel: "Cancel",
        confirm: "Confirm",
        buttons: {
            cancel: true,
            confirm: "Confirm"
        },
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'GET',
                dataType: "text",
                cache: false,
                url: base_url + '/buyerstopsubscription/' + link_id,
                success: function (data) {
                    swal({
                        title: "Success!",
                        icon: "success",
                        text: "Link has been removed.",
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                },
                error: function (error) {
                    swal({
                        title: "Failed!",
                        icon: "warning",
                        text: "Something went wrong!",
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                }
            });
        }
    });
}

function buyerResumeSubscription(link_id) {
    swal({
        title: "Resume Link Placement?",
        icon: "success",
        text: "Are you sure?",
        cancel: "Cancel",
        confirm: "Confirm",
        buttons: {
            cancel: true,
            confirm: "Confirm"
        },
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'GET',
                dataType: "text",
                cache: false,
                url: base_url + '/buyerresumesubscription/' + link_id,
                success: function (data) {
                    swal({
                        title: "Success!",
                        icon: "success",
                        text: "Link is active again!",
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                },
                error: function (error) {
                    swal({
                        title: "Failed!",
                        icon: "warning",
                        text: "Something went wrong!",
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                }
            });
        }
    });
}

function editOrderDetail(params) {
    $("#promoted_url").val($(params).attr('data-pu'));

    //console.log($(params).attr('data-nf'));
    if ($(params).attr('data-nf') == "1")
        $("#no_follow").prop('checked', true);
    else
        $("#no_follow").prop('checked', false);
    $('#id').val($(params).attr('data-id'));
    $('#order_id').val($(params).attr('data-order-id'));


    if ($(params).attr('data-bat').length > 350) {
        $("#link_content").val($(params).attr('data-bat').substring(0, 350));
    } else {
        $("#link_content").val($(params).attr('data-bat'));
        $('#link_content_counter').text(350 - $(params).attr('data-bat').length);
    }

    if ($(params).attr('data-at').length > 60) {
        $("#anchor_text").val($(params).attr('data-at').substring(0, 60));
    } else {
        $("#anchor_text").val($(params).attr('data-at'));
        $('#anchor_text_counter').text(60 - $(params).attr('data-at').length);
    }
    $('#link_content').highlightWithinTextarea({highlight: $(params).attr('data-at')});
    //$('#anchor_check_span_success').hide();
    // $('#anchor_check_span_failed').hide();
    check_anchor();
    $('#editOrderDetailPageModal').modal('show');
}

function AddToCart(params) {
    $('#addToCartPageModal #addToCartPageModalLabel').html('Buy Selected Links');
                $("#addToCartPageModal .cart_success").hide();
                $("#addToCartPageModal #cart_items").show();
                $('.frmAddtoCartSubmitBtn').attr('disabled', false);
                $('.frmAddtoCartSubmitBtn i').css('display', 'none');
    $("#addToCartPageModal #promoted_url").val($(params).attr('data-pu'));
    $("#addToCartPageModal #cart_price").text($(params).attr('data-price'));
    $("#addToCartPageModal #product_id").val($(params).attr('data-page'));
    //console.log($(params).attr('data-nf'));
    if ($(params).attr('data-nf') == "1")
        $("#addToCartPageModal #no_follow").prop('checked', true);
    else
        $("#addToCartPageModal #no_follow").prop('checked', false);
    $('#addToCartPageModal #id').val($(params).attr('data-id'));
    $('#addToCartPageModal #order_id').val($(params).attr('data-order-id'));


    if ($(params).attr('data-bat').length > 350) {
        $("#addToCartPageModal #link_content").val($(params).attr('data-bat').substring(0, 350));
    } else {
        $("#addToCartPageModal #link_content").val($(params).attr('data-bat'));
        $('#addToCartPageModal #link_content_counter').text(350 - $(params).attr('data-bat').length);
    }

    if ($(params).attr('data-at').length > 60) {
        $("#addToCartPageModal #anchor_text").val($(params).attr('data-at').substring(0, 60));
    } else {
        $("#addToCartPageModal #anchor_text").val($(params).attr('data-at'));
        $('#addToCartPageModal #anchor_text_counter').text(60 - $(params).attr('data-at').length);
    }
    $('#addToCartPageModal #link_content').highlightWithinTextarea({highlight: $(params).attr('data-at')});
    //$('#addToCartPageModal #anchor_check_span_success').hide();
    // $('#addToCartPageModal #anchor_check_span_failed').hide();

    $('#addToCartPageModal').modal('show');
    check_anchor("#addToCartPageModal");
}

function check_anchor($TargetForm = "") {
    if ($TargetForm !== "") $TargetForm = $TargetForm + ' ';
    var content = $($TargetForm + '#link_content').val().toLowerCase();
    var anchor = $($TargetForm + '#anchor_text').val().toLowerCase();
    if (anchor.length > 0 && content.length > 0) {
        if (content.includes(anchor) == true) {
            $($TargetForm + '#anchor_check_span_success').show();
            $($TargetForm + '#anchor_check_span_failed').hide();
        } else {
            $($TargetForm + '#anchor_check_span_success').hide();
            $($TargetForm + '#anchor_check_span_failed').show();
        }
    }
}

function check_anchor_add_cart() {

    var content = $('#link_content').val().toLowerCase();
    var anchor = $('#anchor_text').val().toLowerCase();
    if (anchor.length > 0 && content.length > 0) {
        if (content.includes(anchor) == true) {
            $('#anchor_check_span_success').show();
            $('#anchor_check_span_failed').hide();
        } else {
            $('#anchor_check_span_success').hide();
            $('#anchor_check_span_failed').show();
        }
    }
}

$('#editOrderDetailPageModal').on('hidden.bs.modal', function (e) {
    $("#promoted_url").val();
    $("#link_content").val();
    $("#anchor_text").val();
    $('#id').val();
    $('#order_id').val();
    $("#no_follow").prop('checked', false);
})

function charcounter(val, id, counter) {
    var len = val.value.length;
    if (len > counter) {
        val.value = val.value.substring(0, counter);
    } else {
        $('#' + id).text(counter - len);
    }
}

$(function () {
    $('.frmUpdateOrderDetailSubmitBtn').on('click', function (e) {
        var data = $("#editOrderDetail").serialize();
        $.ajax({
            type: 'POST',
            url: base_url + '/update-links',
            data: data,
            success: function (data) {
                $('#editOrderDetailPageModal').modal('hide');
                swal({
                    title: "Success!",
                    icon: "success",
                    text: "Link updated.",
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        location.reload();
                    }
                });
            },
            error: function (err) {
                if (err.responseJSON.errors) {
                    var text = '';
                    $.each(err.responseJSON.errors, function (key, val) {
                        text += (val + '\n');
                    })
                    swal({
                        title: "Oops!",
                        icon: "warning",
                        text: text,
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    });
                }
            }
        });
    })
});

$('.frmAddtoCartSubmitBtn').on('click', function (e) {

    var data = $("#addToCart").serialize();
    $.each($("#addToCart").serializeArray(), function (_, field) {
        /* use field.name, field.value */
        $('#' + field.name).removeClass("is-invalid");
    });
    $.ajax({
        type: 'POST',
        url: base_url + '/add_to_cart',
        data: data,
        success: function (data) {
            obj = jQuery.parseJSON(data);
            //$("#cart_res").text(obj.message);
            var product_id = obj.product_id;
            $('#addToCartPageModal #addToCartPageModalLabel').html('');
            $("#action_" + product_id).html('<a href="javascript:void(0)" onclick="deletecartitem_ajax(\'' + obj.cart_id + '\',\'' + product_id + '\',\'' + obj.price + '\');">Remove from Cart</a>');
            $("#lblCartCount").html(obj.cart_count);
            $("#addToCartPageModal .cart_success").show();
            $("#addToCartPageModal #cart_items").hide();
        },
        error: function (err) {
            if (err.responseJSON.errors) {
                var text = '';
                $.each(err.responseJSON.errors, function (key, val) {
                    text += ('- ' + val + '\n');
                    $('#' + key).addClass("is-invalid");
                })
                swal({
                    title: "Oops!",
                    icon: "warning",
                    text: text,
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                });

            }
        }
    });
})
