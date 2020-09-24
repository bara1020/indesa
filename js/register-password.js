$(document).ready(function(){

    $('#registerPassword').click(function(e){
        $('#confirm-password-error').hide();
        $('#password-error').hide();
        $('#nit-password-error').hide();
        e.preventDefault();
        var data = $('#register-form').serializeArray();
        data.push({ name: 'tag', value: 'registerPassword' });
        $.ajax({
            url: '../../admin/register-password.php',
            type: "post",
            data,
            beforeSend: function () {
                $('.fa').css('display', 'inline');
            }
        })
            .done(function (res) {// true
               console.log(res);
               let json = JSON.parse(res);

               json.message.forEach(element => {
                $(`#${element.id}`).text(element.message);
            });
            })
            .fail(function (e) {// false
                console.log("Error" + e.responseText);
            })
            .always(function () { // seria como un finally
                setTimeout(function () {
                    $('.fa').hide();
                }, 1000);

            });
    });

});