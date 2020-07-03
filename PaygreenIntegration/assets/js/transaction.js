$(document).ready(function () {
    //Validate transaction
    $('#validate-create-cash').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: $('#create-cash').attr('action'),
            type: 'post',
            dataType: 'json',
            data: {
                'firstName': $('#firstName').val(),
                'lastName': $('#lastName').val(),
                'email': $('#email').val(),
                'country': $('#country').val(),
                'amount': $('#amount').val()
            },
            success: function (data) {
                if (data.error === '') {
                    $('#firstName').val('');
                    $('#lastName').val('');
                    $('#email').val('');
                    $('#amount').val('');
                    $('.notif-transaction').text('Transaction bien effectuée');
                    $('.notif-transaction').removeClass('d-none');
                    setTimeout(function () {
                        $('.notif-transaction').addClass('d-none');
                    }, 3000);
                }

                $('#amountHelp').text(data.error);
            }
        });
    });

//Get transaction info
    $('#validate-pid').on('click', function (e) {
        e.preventDefault();
        $('#pid').removeClass('is-invalid');
        $.ajax({
            url: $('#get-details').attr('action'),
            type: 'post',
            dataType: 'json',
            data: {
                'pid': $('#pid').val()
            },
            success: function (data) {
                console.log(data);
                if (data.success) {
                    let result = [];
                    
                } else {
                    $('#pid').addClass('is-invalid');
                }
            }
        });
    });
})
;