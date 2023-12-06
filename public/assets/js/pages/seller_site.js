
$(function(){

    $('#siteurl').keypress(function(e){
        if(e.which == 13){//Enter key pressed
             event.preventDefault();
            $('#saveButton').click();//Trigger button click event
        }
    });
    $('#auth_key').keypress(function(e){
        if(e.which == 13){//Enter key pressed
             event.preventDefault();
            $('#confirmButton').click();//Trigger button click event
        }
    });
    $(document).on("click", "#saveButton", function(event) {
        event.preventDefault();
        $('#saveButton').attr('disabled', true);
        var data = $('#siteDetail').serialize();
        $(".page-loader-wrapper").show();
        $.ajax({
            type: 'POST',
            url: base_url + '/save_sites',
            dataType: "json",
            data: data,
            success: function(data) {
                $(".page-loader-wrapper").hide();
                var text = 'Seller site updated successfully.'
                if(data.success==false)
                {
                  if(data.redirecturl!="")
                    $("#siteurl").val(data.redirecturl);
                  $("#siteurl_error").html(data.message);
                  $('#saveButton').attr('disabled', false);
                }
                if($('#id').val() == '')
                {
                 // console.log(data);
                  if(data.site_auth_key && data.site_auth_key!=""){
                    $("#auth_key").val(data.site_auth_key);
                    $("#site_id").val(data.site_id);
                    $('#sitesDatatable').DataTable().draw()
                    $('#exampleModal').modal('hide');
                    $('#saveButton').attr('disabled', false);
                    $('#verificationModal').modal('show');
                  }
                  text = 'Seller site added successfully.';

                }
                else{
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
                          $('#sitesDatatable').DataTable().draw()
                          $('#exampleModal').modal('hide');
                          $('#saveButton').attr('disabled', false);

                      }
                  });
                }
                /**/

            },
            error: function(err){
                $(".page-loader-wrapper").hide();
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
                    $('#saveButton').attr('disabled', false);
                }
                 else{
                     swal({
                        title: "Oops!",
                        icon: "warning",
                        text: err.responseJSON.message,
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    });
                     $('#saveButton').attr('disabled', false);
                 }
            }
        });
    });


    $(document).on("click", "#confirmButton", function(event) {
        event.preventDefault();
        var data = $('#verifyDetail').serialize();
        $("#verify_status").hide();
        $(".page-loader-wrapper").show();
        $.ajax({
            type: 'POST',
            url: base_url + '/verify_site',
            dataType: "json",
            data: data,
            success: function(data) {
              var text = 'Seller site added successfully.\n\nWe take you now to the Post Selection!\n\n(this may take up to 60 seconds)';
              $(".page-loader-wrapper").hide();
              if(data.valid==true)
              {
                  $("#verify_status").hide();
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
                          $('#verificationModal').modal('hide');
                          $('#confirmButton').attr('disabled', false);
                        //  $('#sitesDatatable').DataTable().draw();
                           $(".page-loader-wrapper").show();
                          window.location = settings.baseurl + "/seller_pages/" + $("#site_id").val() + "?firstSync=true";
                      }
                  });
              }
              else {
                  $("#verify_status").html(data.validreason);
                  $("#verify_status").show();
              }
            },
            error: function(err){
                $(".page-loader-wrapper").hide();
                var text = 'Some error occured, please try later or check your blog';
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
        });
    });

    $(document).on("click","#add-redirect", function(event) {
        event.preventDefault();
        $("#siteurl").val($(this).attr("data-url-key"));
    });

    $('#exampleModal').on('hidden.bs.modal', function (e) {
        resetform()
    })

    $('#verificationModal').on('hidden.bs.modal', function (e) {
        resetform()
    })
});
function resetform(){
    document.getElementById('siteDetail').reset();
    $('#id').val('');
    $('#siteurl').val('');
    //$('#site_category').val('').trigger('change');
    $('#siteurl_error').html('');
    $("#verify_status").html('');
    $('#confirmButton').attr('disabled', false);
    $("#verify_status").hide();
}
function deleteSite(params) {
    swal({
        title: "Are you sure?",
        icon: "warning",
        text: "Seller site will be deleted permanently.",
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
                url: base_url + '/deletesite/' + params,
                success: function(data) {
                    swal({
                        title: "Success!",
                        icon: "success",
                        text: "Seller site deleted successfully.",
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            $('#sitesDatatable').DataTable().draw()
                        }
                    });
                }
            });
        }
    });
}
function recheckVersion(params) {
$('#recheck_icon').addClass("zmdi-hc-spin");
            $.ajax({
                type: 'GET',
               complete: function(){
                   $('#recheck_icon').removeClass("zmdi-hc-spin");
               },
                url: base_url + '/site_pages/' + params + '/versionrecheck/',
                success: function(data) {
                    if(data["status"]=="success"){
                          swal({
                      title: "Success!",
                      icon: "success",
                      text: "New Plugin Version detected!",
                      confirm: {
                          text: "OK",
                          value: true,
                          visible: true,
                          className: "",
                          closeModal: true
                      }
                  }).then(function(isConfirm) {
                      if (isConfirm) {
                        reload_table();
                      }
                  });

                    }
                    else{
                       swal({
                    title: "Oops!",
                    icon: "warning",
                    text: data["message"],
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                })

                    }

                }
            });

}

function editSite(params) {
    $('#siteurl').val($(params).attr('data-site'))
   // $('#site_category').val($(params).attr('data-site-category')).trigger('change')
    $('#is_active').val($(params).attr('data-active'))
    $('#is_active').trigger('change');
    $('#id').val($(params).attr('data-id'))
    $('#exampleModal').modal('show');
}

function confirmSite(params)
{
  $('#site_id').val($(params).attr('data-id'));
  $('#auth_key').val($(params).attr('data-auth-key'))
  $('#verificationModal').modal('show');
}
