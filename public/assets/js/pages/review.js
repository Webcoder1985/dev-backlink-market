function declinereview(params) {
    swal({
        title: "Are you sure?",
        icon: "warning",
        text: "Review Reuquest will be decline",
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
                url: base_url + '/declinereview/' + params,
                success: function(data) {
                    swal({
                        title: "Success!",
                        icon: "success",
                        text: "Review Reuquest Decline successfully.",
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

function approvereview(params) {
    swal({
        title: "Are you sure?",
        icon: "warning",
        text: "Review Reuquest will be Approved",
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
                url: base_url + '/approvereview/' + params,
                success: function(data) {
                    console.log(data.status);
                    if(data.status == 'success'){
                        swal({
                            title: "Success!",
                            icon: "success",
                            text: "Review Reuquest Approved successfully.",
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

function deletereview(params) {
    swal({
        title: "Are you sure?",
        icon: "warning",
        text: "Review Reuquest will be Delete",
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
                url: base_url + '/deletereview/' + params,
                success: function(data) {
                    console.log(data.status);
                    if(data.status == 'success'){
                        swal({
                            title: "Success!",
                            icon: "success",
                            text: "Review Reuquest Delete successfully.",
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
