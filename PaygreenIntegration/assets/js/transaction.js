$(document).ready(function () {
    //Validate transaction
    $('#validate-create-cash').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: $('#create-cash').attr('action'),
            type: 'post',
            dataType: 'json',
            data: {
                'companyName': $('#companyName').val(),
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

                if (data.success) {
                    let createdAt = new Date(data.data.createdAt);
                    let valuedAt = new Date(data.data.valueAt);
                    let result = '<tbody>' +
                        '<tr>' +
                        '<th scope="row">ID</th>' +
                        '<td>' + data.data.id + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<th scope="row">AMOUNT</th>' +
                        '<td>' + data.data.amount / 100 + ' ' + data.data.currency + ' </td>' +
                        '</tr>' +
                        '<tr><th scope="row">TYPE</th>' +
                        '<td>' + data.data.type + ' </td>' +
                        '</tr>' +
                        '<tr><th scope="row">STATUS</th>' +
                        '<td>' + data.data.result.status + ' </td>' +
                        '</tr>' +
                        '<tr><th scope="row">BUYER</th>' +
                        '<td>' + data.data.buyer.companyName + ' (' + data.data.buyer.id + ' ' + data.data.buyer.lastName + ')</td>' +
                        '</tr>' +
                        '<tr><th scope="row">CREATED AT</th>' +
                        '<td>' + createdAt.toLocaleDateString() + ' </td>' +
                        '</tr>' +
                        '<tr><th scope="row">VALUE AT</th>' +
                        '<td>' + valuedAt.toLocaleDateString() + ' </td>' +
                        '</tr>' +
                        '<tbody>';
                    $('table').html(result);
                    $('#pid').val('');

                } else {
                    $('#pid').addClass('is-invalid');
                }
            }
        });
    });
})
;