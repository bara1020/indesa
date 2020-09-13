var table;
var row;
$(document).ready(function () {
  $("#alert-row").hide();
  $("#alert-row-scheduler").hide();
  $("#alert-row-user-limit").hide();

  //Begin: GetUsers
  getUsers();
  //End: GetUsers

  //Begin: getPicoCedula
  getPicoCedula();
  //End: getPicoCedula

  //Begin: getConfiguration
  getConfiguration();
  //End: getConfiguration



  //Ejecución del registro 
  $('#register').click(function (e) {
    e.preventDefault();
    var data = $('#register-form').serializeArray();
    data.push({ name: 'role', value: $('.selectpicker').val() });
    data.push({ name: 'tag', value: 'register' });
    $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {// true
        $('.help-block').hide();
        let json = JSON.parse(res);

        json.message.forEach(element => {
          $("#" + element.id).text(element.message);
          $("#" + element.id).show();
        });

        if (json.status) {
          let table = $('#example').DataTable();
          $('#registerModal').modal('toggle');
          table.row.add(json.message[0].data).draw();
          $("#registerModal .close").click();
          $("#register-form")[0].reset();
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


  //Ejecución del update user
  $('#update').click(function (e) {
    e.preventDefault();

    var data = $('#update-form').serializeArray();
    data.push({ name: "nit", value: $('#inputNit-update').val() });
    data.push({ name: 'idRole', value: $('#selectpicker').val() });
    data.push({ name: 'role', value: $("#selectpicker option:selected").text() });
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
        $('.help-block').hide();
        let json = JSON.parse(res);
        json.message.forEach(element => {
          $("#" + element.id).text(element.message);
          $("#" + element.id).show();
        });

        if (json.status) {
          row.data(json.message[0].data);
          $('#updateModal').modal('toggle');
          $("#updateModal .close").click();
          $("#update-form")[0].reset();
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

  //Ejecución del update pico y cédula
  $('#update-pico-cedula').click(function (e) {
    e.preventDefault();

    var data = $('#update-pico-cedula-form').serializeArray();
    data.push({ name: 'tag', value: 'updatePicoCedula' });//esto permite saber que funcion del php voy a ejecutar
    $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {// true
        if (res == 1)
          $("#alert-row").show();
        setTimeout(function () {
          $('#alert-row').hide();
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

  //Ejecución del update pico y cédula
  $('#update-scheduler').click(function (e) {
    e.preventDefault();

    var data = $('#update-scheduler-form').serializeArray();
    data.push({ name: 'tag', value: 'updateScheduler' });//esto permite saber que funcion del php voy a ejecutar
    $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {// true
        if (res == 1) {
          $("#alert-row-scheduler").show();
        setTimeout(function () {
          $('#alert-row-scheduler').hide();
        }, 5000);
      } else {
        console.log(res);
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

  //Ejecución del update pico y cédula
  $('#update-user-limit').click(function (e) {
    e.preventDefault();

    var data = $('#update-limit-form').serializeArray();
    data.push({ name: 'tag', value: 'updateUserLimit' });//esto permite saber que funcion del php voy a ejecutar
    $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {// true
        if (res == 1)
          $("#alert-row-user-limit").show();
        setTimeout(function () {
          $('#alert-row-user-limit').hide();
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

  //Ejecución del update 
  $('#delete').click(function (e) {
    e.preventDefault();
    let data = [];
    data.push({ name: "id", value: row.data().id });
    data.push({ name: 'tag', value: 'delete' });//esto permite saber que funcion del php voy a ejecutar

    $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {// true
        console.log(res);
        row.remove().draw();
        $('#deleteModal').modal('toggle');
        $("#deleteModal .close").click();
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


  //Ejecución del update 
  $('#delete').click(function (e) {
    e.preventDefault();
    let data = [];
    data.push({ name: "id", value: row.data().id });
    data.push({ name: 'tag', value: 'delete' });//esto permite saber que funcion del php voy a ejecutar

    $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {// true
        console.log(res);
        row.remove().draw();
        $('#deleteModal').modal('toggle');
        $("#deleteModal .close").click();
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


  $('#ver').click(() =>{
    let data = [];
    data.push({ name: 'tag', value: 'getFile' });//esto permite saber que funcion del php voy a ejecutar

    $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {// true
        console.log(res);
        var arrayBuffer = res;

        // if you want to access the bytes:
        var byteArray = new Uint8Array(arrayBuffer);
        var blob = new Blob([res], {type: "application/pdf"});
        var objectUrl = URL.createObjectURL(blob);

        $('#frame1').prop('src', res);
        //window.open(objectUrl);
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

  function getUsers() {
    let data = [{ name: 'tag', value: 'getUsers' }];//esto permite saber que funcion del php voy a ejecutar
    var dataSet = $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {// true
        //Begin: datatable User
        var table = $('#example').DataTable({
          data: JSON.parse(res),
          retrieve: true,
          responsive: true,
          columns: [
            { title: "id", data: "id", visible: false },
            { title: "id_role", data: "id_role", visible: false },
            { title: "Cédula", data: "nit" },
            { title: "Nombre", data: "username" },
            { title: "Apellidos", data: "lastname" },
            { title: "Email", data: "email" },
            { title: "Teléfono", data: "phonenumber" },
            { title: "Role", data: "role" },

            { title: "Acción" },
          ],
          "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": `
                            <button class='btn btn-warning edit'><i class="fas fa-edit"></i></button>
                            <button class='btn btn-danger delete'><i class="far fa-trash-alt"></i></button>
                            `
          }],
          "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
              "sFirst": "Primero",
              "sLast": "Último",
              "sNext": "Siguiente",
              "sPrevious": "Anterior"
            },
            "oAria": {
              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
          }
        });

        $('#example tbody').on('click', '.edit', function () {
          let data = table.row($(this).parents('tr')).data();
          row = table.row($(this).parents('tr'));
          $('#inputName-update').val(data.username);
          $('#inputNit-update').val(data.nit);
          $('#inputLastName-update').val(data.lastname);
          $('#inputPhoneNumber-update').val(data.phonenumber);
          $('#inputEmail-update').val(data.email);
          $(".selectpicker").val(data.id_role);

          $('#update').show();
          $('#updateModal').modal('show');
        });

        $('#example tbody').on('click', '.delete', function () {
          row = table.row($(this).parents('tr'));
          //table.row( $(this).parents('tr')).remove().draw();
          $('#deleteModal').modal('show');

        });
        //End: datatable User

        return res.responseText;
      })
      .fail(function (e) {// false
        console.log("Error" + e.responseText);
      })
      .always(function () { // seria como un finally
        setTimeout(function () {
          $('.fa').hide();
        }, 1000);
      });
    return dataSet;
  }


  /**
   * Get pico y cedula
   */
  function getPicoCedula() {
    let data = [{ name: 'tag', value: 'getPicoCedula' }];//esto permite saber que funcion del php voy a ejecutar
    $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {
        let json = JSON.parse(res);
        json.forEach(item => {
          $(`#${item.day}PicoCedula`).val(item.restriction);
          $(`#to-${item.day}`).val(item.schedulerTo);
          $(`#from-${item.day}`).val(item.schedulerFrom);
          $(`#to-${item.day}-after`).val(item.schedulerToAfter);
          $(`#from-${item.day}-after`).val(item.schedulerFromAfter);
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
  }


    /**
   * Get configuration
   */
  function getConfiguration() {
    let data = [{ name: 'tag', value: 'getConfiguration' }];//esto permite saber que funcion del php voy a ejecutar
    $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {
        let json = JSON.parse(res);
        console.log(json);
        $('#userLimit').val(json.user_limit);
      })
      .fail(function (e) {// false
        console.log("Error" + e.responseText);
      })
      .always(function () { // seria como un finally
        setTimeout(function () {
          $('.fa').hide();
        }, 1000);
      });
  }



});





