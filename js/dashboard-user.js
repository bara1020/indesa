
$(document).ready(function(){
  $("#response-message").hide();
  $("#message-ok").hide();
//Ejecuci車n del update user
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


$('#insert-asistencia').click(function(){
  var formData = new FormData();
  formData.append('sexo',$("input[name='sexo']:checked").val());
  formData.append('fechaNacimiento',$("#fechaNacimiento").val()); 
  formData.append('nacionalidad',$("#nacionalidad").val());
  formData.append('departamento',$("#departamento").val());
  formData.append('telefono',$("#telefono").val());
  formData.append('direccion',$("#direccion").val());
  formData.append('municipio',$("#municipio").val());
  formData.append('contactoCovid',$("input[name='contactoCovid']:checked").val());
  formData.append('contactoCovidSospechoso',$("input[name='contactoCovidSospechoso']:checked").val());
  formData.append('enfermedades',$("input[name='enfermedades']:checked").val());
  formData.append('embarazada',$("input[name='embarazada']:checked").val());
  formData.append('semanasGestacion',$("#semanasGestacion").val());
  formData.append('tomaMedicamentos',$("#tomaMedicamentos").val());
  formData.append('temperatura',$("#temperatura").val());
  formData.append('observaciones',$("#observaciones").val());
  var selectedLanguage = new Array();
    $('input[name="sintomas"]:checked').each(function() {
    selectedLanguage.push(this.id);
  });
  formData.append('sintomas',selectedLanguage);
  formData.append('tag','saveForm');
  $.ajax({
    url: '../../admin/functions_register_form.php',
    type: "post",
    data: formData,
    contentType: false,
    processData: false,
    beforeSend: function () {
      $('.fa').css('display', 'inline');
    }
  })
    .done(function (res) {// true
      console.log(res);
      $('.help-block').hide();
    let json = JSON.parse(res);
    json.message.forEach(element => {
      $("#" + element.id).text(element.message);
      $("#" + element.id).show();
    });

    if (json.status) {
        
      $('#insertModal').modal('toggle');
      $("#insertModal .close").click();
      $("#asistencia-form")[0].reset();
      $('.help-block').hide();
      $("#alert-row").show();
      setTimeout(function () {
        $('#alert-row').hide();
      }, 5000);
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
  return false;
});


});