function declinerequest(params) {
    swal({
        title: "Are you sure?",
        icon: "warning",
        text: "Withdraw Request will be deleted. NOTE: Funds are NOT added back to Seller Balance!",
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
                url: base_url + '/declinewitdraw/' + params,
                success: function(data) {
                    swal({
                        title: "Success!",
                        icon: "success",
                        text: "Withdraw Request deleted successfully.",
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

function approverequest(params) {
    swal({
        title: "Are you sure?",
        icon: "warning",
        text: "Withdraw Reuquest will be Approved",
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
                url: base_url + '/approvewitdraw/' + params,
                success: function(data) {
                    console.log(data.status);
                    if(data.status == 'success'){
                        swal({
                            title: "Success!",
                            icon: "success",
                            text: "Withdraw Reuquest Approved successfully.",
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
                    else{
                        console.log(data.message);
                        const wrapper = document.createElement('div');
                        var obj = jQuery.parseJSON(data.message);
                        wrapper.innerHTML += obj.message;
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
                    }
                },
                error: function(err){
                    if(err.responseJSON.errors)
                    {
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
        }
    });
}
