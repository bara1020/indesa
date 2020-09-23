
$(document).ready(function(){
        $("#response-message").hide();
        $("#message-ok").hide();
    //Ejecución del update user
      $('#update-perfil').click(function (e) {
        e.preventDefault();
        var data = $('#update-form').serializeArray();
        data.push({ name: "nit", value: $('#inputNit').val() });
        data.push({ name: 'tag', value: 'update' });//esto permite saber que funcion del php voy a ejecutar
        $.ajax({
          url: '../../admin/functions.php',
          type: "post",
          data,
          beforeSend: function () {
            $('.fa').css('display', 'inline');
          }
        })
          .done(function (res) {// true
            let json = JSON.parse(res);
            json.message.forEach(element => {
                $("#" + element.id).text(element.message);
                $("#" + element.id).show();
              });

              setTimeout(function () {
                $('#response-message').hide();
              }, 5000);
          })
          .fail(function (e) {// false
            console.log("Error" + e.responseText);
          })
          .always(function () { // seria como un finally
            setTimeout(function () {
              $('.fa').hide();
            }, 1000);
    
          });
        return false;
      });

      $('#updateconsent').click(function (e) {
        var formData = new FormData();
        var files = $('#uploadedFile')[0].files[0];
        formData.append('file',files);
        formData.append('nit', $('#inputNit').val());

        $.ajax({
          url: '../../admin/updateFile.php',
          type: "post",
          data:formData,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $('.fa').css('display', 'inline');
          }
        })
          .done(function (res) {// true
            console.log(res);
            let json = JSON.parse(res);
            
            json.message.forEach(element => {
                $("#" + element.id).text(element.message);
                $("#" + element.id).show();
              });

              setTimeout(function () {
                $('#message-ok').hide();
              }, 5000);
           
          })
          .fail(function (e) {// false
            console.log("Error" + e.responseText);
          })
          .always(function () { // seria como un finally
            setTimeout(function () {
              $('.fa').hide();
            }, 1000);
    
          });
        return false;
      });


});