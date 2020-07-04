$(document).ready(function () {

    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    //Validate transaction
    $('#validate-create-cash').on('click', (e) => {
        e.preventDefault();
        let validForm = true;

        if ($('#amount').val().trim() == '') {
            $('#amount').addClass('is-invalid');
            validForm = false;
        } else {
            $('#amount').removeClass('is-invalid');
        }

        if ($('#lastName').val().trim() == '') {
            $('#lastName').addClass('is-invalid');
            validForm = false;
        } else {
            $('#lastName').removeClass('is-invalid');
        }

        if (!validateEmail(($('#email').val().trim()))) {
            $('#email').addClass('is-invalid');
            validForm = false;
        } else {
            $('#email').removeClass('is-invalid');
        }

        if (validForm) {
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
                        $('#message').text('Transaction (ID : ' + data.data.data.id + ') bien effectuée');
                        $('.notif-transaction').removeClass('d-none');
                    }
                    $('#amount').removeClass('is-invalid');
                    $('#amountHelp').text(data.error);
                }
            });
        }
    });

    //remove alert notification
    $('button.close').on('click', () => {
        $('.notif-transaction').addClass('d-none');
    });

//Get transaction info
    $('#validate-pid').on('click', (e) => {
        e.preventDefault();
        $('table').html('');
        $('#pidHelp').text('')
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
                        '<td>' + data.data.buyer.companyName + ' (id: ' + data.data.buyer.id + ' ' + data.data.buyer.lastName + ')</td>' +
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
                    $('#pidHelp').text('ID invalide')
                }
            }
        });
    });
})
;