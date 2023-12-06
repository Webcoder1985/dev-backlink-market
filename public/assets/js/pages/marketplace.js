$(function(){
    $('#sellerPageAdd').on('submit', function (e) {
        e.preventDefault();
        $('.frmSubmitBtn i').css('display', 'inline-block');
        $('.frmSubmitBtn').attr('disabled', true);
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: base_url + '/seller/pageSave',
            data: data,
            success: function (data) {
                var text = 'Page(s) added successfully.'
                if ($('#id').val() == '') {
                    text = 'Page(s) added successfully.';
                }
                swal({
                    title: "Success!",
                    icon: "success",
                    text: text,
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        $('#blogDatatable').DataTable().draw()
                        $('#blogPagesDatatable').DataTable().draw()
                        $('#addNewSellerPageModal').modal('hide');
                        $('.frmSubmitBtn').attr('disabled', false);
                        $('.frmSubmitBtn i').css('display', 'none');
                    }
                });

            },
            error: function (err) {
                if (err.responseJSON.errors) {
                    var text = '';
                    const wrapper = document.createElement('div');
                    $.each(err.responseJSON.errors, function (key, val) {
                        wrapper.innerHTML += (val + ' ');
                    })
                    swal({
                        title: "Oops!",
                        icon: "warning",
                        content: wrapper,
                        html: true,
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    });
                    $('.frmSubmitBtn').attr('disabled', false);
                    $('.frmSubmitBtn i').css('display', 'none');
                }
            }
        });
    })
    $('#sellerPageDetail').on('submit', function (e) {
        e.preventDefault();
        $('.frmSubmitBtn i').css('display', 'inline-block');
        $('.frmSubmitBtn').attr('disabled', true);
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: base_url + '/seller/editPage',
            data: data,
            success: function (data) {
                var text = 'Page updated successfully.'
                if ($('#id').val() == '') {
                    text = 'Page added successfully.';
                }
                swal({
                    title: "Success!",
                    icon: "success",
                    text: text,
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        $('#blogDatatable').DataTable().draw()
                        $('#blogPagesDatatable').DataTable().draw()
                        $('#addNewSellerPageModal').modal('hide');
                        $('.frmSubmitBtn').attr('disabled', false);
                        $('.frmSubmitBtn i').css('display', 'none');
                    }
                });

            },
            error: function(err){
                if(err.responseJSON.errors) {
                    var text = '';
                    const wrapper = document.createElement('div');
                    $.each(err.responseJSON.errors, function(key, val){
                        wrapper.innerHTML += (val + ' ');
                    })
                    swal({
                        title: "Oops!",
                        icon: "warning",
                        content: wrapper,
                        html:true,
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    });
                    $('.frmSubmitBtn').attr('disabled', false);
                    $('.frmSubmitBtn i').css('display', 'none');
                }
            }
        });
    })
 $('.frmGenerateContentBtn').on('click', function(e){
        $('.frmGenerateContentBtn').attr('disabled', true);
        $('.frmGenerateContentBtn').html('<i class="zmdi zmdi-edit zmdi-hc-spin mr-2"></i> waiting...')
        var data = $("#addToCart").serialize();

        $('#content_keyword').removeClass("is-invalid");
        $('#anchor_text').removeClass("is-invalid");
        $.ajax({
            type: 'POST',
            url: base_url + '/getContentAPI',
            data: data,
            success: function(data) {
                if (data.length > 350) {
                  data = data.substring(0, 349) + ".";
                   swal({
                      title: "Attention!",
                      icon: "warning",
                      text: "The content has been truncated. Please check last sentence for correctness.",
                      confirm: {
                          text: "OK",
                          value: true,
                          visible: true,
                          className: "",
                          closeModal: true
                      }
                  });
                }

                $('#link_content').val(data);
                $('#link_content_counter').text(350-data.length);
                $('#link_content').highlightWithinTextarea('update');
                check_anchor();
            },
            error: function(err){
              if(err.responseJSON.errors)
              {
                  var text = '';
                  $.each(err.responseJSON.errors, function(key, val){
                      text += (val + '\n');
                      $('#'+key).addClass("is-invalid");
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
                  $('.frmAddtoCartSubmitBtn').attr('disabled', false);
                  $('.frmAddtoCartSubmitBtn i').css('display', 'none');
              }
            },
            complete: function(){
            $('.frmGenerateContentBtn').attr('disabled', false);
            $('.frmGenerateContentBtn').html('<i class="zmdi zmdi-edit mr-2"></i> Generate content')


            }
        });
    })

    $('.frmAddtoCartSubmitBtn').on('click', function(e){

        var data = $("#addToCart").serialize();
        $.each($("#addToCart").serializeArray(), function(_, field) {
            /* use field.name, field.value */
             $('#'+field.name).removeClass("is-invalid");
        });
        $.ajax({
            type: 'POST',
            url: base_url + '/add_to_cart',
            data: data,
            success: function(data) {
                obj = jQuery.parseJSON(data);
                //$("#cart_res").text(obj.message);
                var product_id = obj.product_id;
                $('#addToCartPageModalLabel').html('');
                $("#action_" + product_id).html('<a href="javascript:void(0)" onclick="deletecartitem_ajax(\'' + obj.cart_id + '\',\'' + product_id + '\',\'' + obj.price + '\');">Remove from Cart</a>');
                $("#lblCartCount").html(obj.cart_count);
                $(".cart_success").show();
                $("#cart_items").hide();
            },
            error: function(err){
              if(err.responseJSON.errors)
              {
                  var text = '';
                  $.each(err.responseJSON.errors, function(key, val){
                      text += ('- ' + val + '\n');
                      $('#'+key).addClass("is-invalid");
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
                  $('.frmAddtoCartSubmitBtn').attr('disabled', false);
                  $('.frmAddtoCartSubmitBtn i').css('display', 'none');
              }
            }
        });
    })
    $(document).on('click', '.pr_chk', function() {
        $(".cart_success").hide();
        $("#cart_items").show();
        $('#addToCartPageModalLabel').html('Add to Cart');
        $('#addToCartPageModal').modal('show');
        var price = 0.00;
        var product_id = $(this).attr('data-id');
        $('#link_content').val('');
        $('#content_keyword').val('');
        $('#anchor_text').val('');
        $('#promoted_url').val('');
        price = parseFloat($(this).attr('data-price')) + parseFloat(price);
        $("#product_id").val(product_id);
        $("#price").val(price);
        $("#cart_price").text(price);
        $("#link_content").highlightWithinTextarea({highlight: ''});
        $('#anchor_check_span_success').hide();
        $('#anchor_check_span_failed').hide();
    });

    $(document).on('click', '.close_btn', function () {
        $(".cart_success").hide();
        $("#cart_items").show();
    });
})
$(function () {
    $('#sellerPageEdit').on('submit', function (e) {
        e.preventDefault();
        $('.frmSubmitBtn i').css('display', 'inline-block');
        $('.frmSubmitBtn').attr('disabled', true);
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: base_url + '/seller/editPage',
            data: data,
            success: function (data) {
                var text = 'Page edited successfully.'
                if ($('#id').val() == '') {
                    text = 'Page edited successfully.';
                }
                swal({
                    title: "Success!",
                    icon: "success",
                    text: text,
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        $('#blogDatatable').DataTable().draw()
                        $('#blogPagesDatatable').DataTable().draw()
                        $('#editSellerPageModal').modal('hide');
                        $('.frmSubmitBtn').attr('disabled', false);
                        $('.frmSubmitBtn i').css('display', 'none');
                    }
                });

            },
            error: function (err) {
                if (err.responseJSON.errors) {
                    var text = '';
                    const wrapper = document.createElement('div');
                    $.each(err.responseJSON.errors, function (key, val) {
                        wrapper.innerHTML += (val + ' ');
                    })
                    swal({
                        title: "Oops!",
                        icon: "warning",
                        content: wrapper,
                        html: true,
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    });
                    $('.frmSubmitBtn').attr('disabled', false);
                    $('.frmSubmitBtn i').css('display', 'none');
                }
            }
        });
    })
    /*$('#sellerPageDetail').on('submit', function (e) {
        e.preventDefault();
        $('.frmSubmitBtn i').css('display', 'inline-block');
        $('.frmSubmitBtn').attr('disabled', true);
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: base_url + '/seller/editPage',
            data: data,
            success: function (data) {
                var text = 'Page updated successfully.'
                if ($('#id').val() == '') {
                    text = 'Page added successfully.';
                }
                swal({
                    title: "Success!",
                    icon: "success",
                    text: text,
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        $('#blogDatatable').DataTable().draw()
                        $('#blogPagesDatatable').DataTable().draw()
                        $('#addNewSellerPageModal').modal('hide');
                        $('.frmSubmitBtn').attr('disabled', false);
                        $('.frmSubmitBtn i').css('display', 'none');
                    }
                });

            },
            error: function (err) {
                if (err.responseJSON.errors) {
                    var text = '';
                    const wrapper = document.createElement('div');
                    $.each(err.responseJSON.errors, function (key, val) {
                        wrapper.innerHTML += (val + ' ');
                    })
                    swal({
                        title: "Oops!",
                        icon: "warning",
                        content: wrapper,
                        html: true,
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    });
                    $('.frmSubmitBtn').attr('disabled', false);
                    $('.frmSubmitBtn i').css('display', 'none');
                }
            }
        });
    })*/
    /*$('.frmAddtoCartSubmitBtn').on('click', function (e) {

        var data = $("#addToCart").serialize();
        $.ajax({
            type: 'POST',
            url: base_url + '/add_to_cart',
            data: data,
            success: function (data) {
                obj = jQuery.parseJSON(data);
                //$("#cart_res").text(obj.message);
                var product_id = obj.product_id;
                $('#addToCartPageModalLabel').html('');
                $("#action_" + product_id).html('<a href="javascript:void(0)" onclick="deletecartitem_ajax(\'' + obj.cart_id + '\',\'' + product_id + '\',\'' + obj.price + '\');">Remove from Cart</a>');
                $("#lblCartCount").html(obj.cart_count);
                $(".cart_success").show();
                $("#cart_items").hide();
            },
            error: function (err) {
                if (err.responseJSON.errors) {
                    var text = '';
                    $.each(err.responseJSON.errors, function (key, val) {
                        text += (val + ' ');
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
                    $('.frmAddtoCartSubmitBtn').attr('disabled', false);
                    $('.frmAddtoCartSubmitBtn i').css('display', 'none');
                }
            }
        });
    })

    $(document).on('click', '.close_btn', function () {
        $(".cart_success").hide();
        $("#cart_items").show();
    });*/
})
function openCreatePageModal(id)
{
    $('#addNewSellerPageModal .modal-body').html('<div class="modalLoading"><i class="zmdi zmdi-settings zmdi-hc-spin"></i> Please wait...</div>');
    $('#addNewSellerPageModalLabel').html('Add Post/Page');
    $('#addNewSellerPageModal').modal('show');
    $.ajax({
        type: 'GET',
        url: base_url + '/seller/create-page-form/'+id,
        success: function(data) {
            $('#addNewSellerPageModal .modal-body').html(data);
            $('#addNewSellerPageModal .select2').select2();
        }
    });
}
function editPage(id) {
    $('#editSellerPageModal .modal-body').html('<div class="modalLoading"><i class="zmdi zmdi-settings zmdi-hc-spin"></i> Please wait...</div>');
    $('#editSellerPageModalLabel').html('Edit Page');
    $('#editSellerPageModal').modal('show');
    $('.frmSubmitBtn').hide();
    $.ajax({
        type: 'GET',
        url: base_url + '/seller/page-form/' + id,
        success: function (data) {
            $('.frmSubmitBtn').show();
            $('#editSellerPageModal .modal-body').html(data);
            $('#editSellerPageModal .select2').select2();
        }
    });
}

function deletePage(id)
{
    swal({
        title: "Are you sure?",
        icon: "warning",
        text: "Page will be deleted permanently.",
        cancel: "Cancel",
        confirm: "Confirm",
        buttons: {
            cancel: true,
            confirm: "Confirm"
        },
    }).then(function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'GET',
                url: base_url + '/marketplace/delete/' + id,
                success: function(data) {
                    swal({
                        title: "Success!",
                        icon: "success",
                        text: "Page deleted successfully.",
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            $('#blogDatatable').DataTable().draw()
                        }
                    });
                },
                error: function(err){
                    swal({
                        title: "Oops!",
                        icon: "danger",
                        text: 'Somethind went wrong! Please try again.',
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    });
                    $('#blogDatatable').DataTable().draw()
                }
            });
        }
    });
}
function check_anchor()
{

     var content= $('#link_content').val().toLowerCase();
     var anchor=$('#anchor_text').val().toLowerCase();
     if(anchor.length>0 && content.length>0 ) {
         if (content.includes(anchor) == true) {
             $('#anchor_check_span_success').show();
             $('#anchor_check_span_failed').hide();
         } else {
             $('#anchor_check_span_success').hide();
             $('#anchor_check_span_failed').show();
         }
     }
}
function charcounter(val,id,counter)
{
  var len = val.value.length;
        if (len > counter) {
          val.value = val.value.substring(0, counter);
        } else {
          $('#'+id).text(counter - len);
        }
}
function deletecartitem_ajax(params,pr_id,price) {
    swal({
        title: "Are you sure?",
        icon: "warning",
        text: "Cart item will be deleted.",
        dangerMode: true,
        cancel: "Cancel",
        confirm: "Confirm",
        buttons: {
            cancel: true,
            confirm: "Delete"
        },
    }).then(function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'GET',
                url: base_url + '/deletecartitem/' + params,
                success: function(data) {
                    swal({
                        title: "Success!",
                        icon: "success",
                        text: "Cart Item deleted successfully.",
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            $("#action_" + pr_id).html('<a href="javascript:void(0)" class="pr_chk" data-id = "' + pr_id + '" data-price="' + price + '">Add to Cart</a>');
                            (data.cart_count > 0) ? $("#lblCartCount").html(data.cart_count) : $("#lblCartCount").html('');
                          //location.reload();
                        }
                    });
                }
            });
        }
    });
}

$(document).on('change', '#country', function() {
    var eucountry = $('option:selected', this).attr('eucountry');
    var country = $('option:selected', this).attr("value");
    if (eucountry == 1) {
        $(".vat_field").show();
    } else {
        $(".vat_field").hide();
    }
    if (country == 'DE') {
        $(".de_field").show();
    } else {
        $(".de_field").hide();
    }
});
