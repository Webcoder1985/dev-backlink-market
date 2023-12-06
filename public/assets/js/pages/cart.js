function deletecartitem(params) {

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
                          location.reload();
                        }
                    });
                }
            });
        }
    });
}

$(function(){
    $('#placeorder').on('submit', function(e){
        e.preventDefault();
        $('.frmSubmitBtn i').css('display', 'inline-block');
        $('.frmSubmitBtn').attr('disabled', true);
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: base_url + '/order/saveorder',
            data: data,
            dataType: "json",
            success: function(data) {
                if(data.status == 'success')
                {
                  window.location=data.redirect_url;
                  var text = 'Order Place successfully';
                  /*swal({
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
                        var url = base_url + '/marketplace';
                        window.location.replace(url);
                      }
                  });*/
                }
                else {
                  var text = 'There is some problem';
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
                  }).then(function(isConfirm) {
                      if (isConfirm) {
                        var url = base_url + '/marketplace';
                        window.location.replace(url);
                      }
                  });
                }


            },
            error: function(err){
                if(err.responseJSON.errors)
                {
                    var text = '';
                    $.each(err.responseJSON.errors, function(key, val){
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
                    $('.frmSubmitBtn').attr('disabled', false);
                    $('.frmSubmitBtn i').css('display', 'none');
                }
            }
        });
    })

})

function editcartitem(params) {
    $("#promoted_url").val($("#promoted_url_"+params).val());
    $("#link_content").val($("#link_content_"+params).val());
    $("#product_id").val($("#product_id_"+params).val());
    $("#cart_price").text($("#price_"+params).val());
    $("#price").val($("#current_price_"+params).val());
    $("#cart_id").val($("#cart_id_"+params).val());


    var len1 = $('#link_content').val().length;
        if (len1 > 350) {
          $("#link_content").val($('#link_content').val.substring(0, 350));
        } else {
          $('#link_content_counter').text(350 - len1);
        }
    $('#anchor_text').val($('#anchor_text_'+params).val());
    var len = $('#anchor_text').val().length;
    if (len > 60) {
      $('#anchor_text').val( $('#anchor_text').val.substring(0, 60));
    } else {
      $('#anchor_text_counter').text(60 - len);
    }

    var no_follow = $('#no_follow_'+params).val();
    if(no_follow == 1)
    {
        $('#no_follow').prop('checked', true);
    }
    else
    {
        $('#no_follow').prop('checked', false);
    }

    $('#link_content').highlightWithinTextarea({highlight: $('#anchor_text_' + params).val()});

    $('#anchor_check_span_success').hide();
    $('#anchor_check_span_failed').hide();
    check_anchor();
    $("#addToCartPageModal").modal();
    return false;
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
$('.frmAddtoCartSubmitBtn').on('click', function(e){

    var data = $("#addToCart").serialize();
    $.ajax({
        type: 'POST',
        url: base_url + '/update_to_cart',
        data: data,
        success: function(data) {
            obj = jQuery.parseJSON(data);
            //$("#cart_res").text(obj.message);
            var product_id = obj.product_id;
           // $('#addToCartPageModalLabel').html('');
            location.reload();
        },
        error: function(err){
          if(err.responseJSON.errors)
          {
              var text = '';
              $.each(err.responseJSON.errors, function(key, val){
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
$('.frmAddtoCartSubmitBtn').on('click', function(e){

    var data = $("#addToCart").serialize();
    $.ajax({
        type: 'POST',
        url: base_url + '/update_to_cart',
        data: data,
        success: function(data) {
            obj = jQuery.parseJSON(data);
            //$("#cart_res").text(obj.message);
            var product_id = obj.product_id;
           // $('#addToCartPageModalLabel').html('');
            location.reload();
        },
        error: function(err){
          if(err.responseJSON.errors)
          {
              var text = '';
              $.each(err.responseJSON.errors, function(key, val){
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
