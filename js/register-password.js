$(document).ready(function(){

    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
    
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
    
            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };

$('#registerPassword').click(function(e){
    $('#confirm-password-error').hide();
    $('#password-error').hide();
    $('#nit-password-error').hide();
    e.preventDefault();
    var data = $('#register-form').serializeArray();
    data.push({ name: 'token', value: getUrlParameter('token') });
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
                console.log(json.status);
           if(json.status){
              window.location.href = "http://registro.indesa.gov.co/views/user/login";
           } else {
               json.message.forEach(element => {
                $(`#${element.id}`).text(element.message);
            
                });
           }
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