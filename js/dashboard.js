var table;
var userTable;
var row;
var idSelected;
var role;
$(document).ready(function () {
  $("#alert-row").hide();
  $("#alert-row-scheduler").hide();
  $("#alert-row-user-limit").hide();
  $('#show-document').hide();
  role = $('#roleuser').val();

  

  

  //Begin: GetUsers
  getUsers();
  //End: GetUsers

  //Begin: getPicoCedula
  getPicoCedula();
  //End: getPicoCedula

  //Begin: getConfiguration
  getConfiguration();
  //End: getConfiguration

  //Begin: getTiqueteras
  getTiqueteras();
  //End: getTiqueteras
  
  //Begin: getBooking
  getBooking();
  //End: getBooking


  //Ejecución del registro 
  $('#register').click(function (e) {
    e.preventDefault();
    var data = $('#register-form').serializeArray();
    data.push({ name: 'origin', value: 'principal'});
    data.push({ name: 'role', value: $('.selectpicker').val() });
    data.push({ name: 'id_tiquetera', value: $('.selectpickerPlan').val() });
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
        console.log(res);
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

  var file;
  $('#uploadedFile').change(function(e){
    file = e.target.files[0];
});


//Ejecución del update user
  $("#update").on('click', function(e){
    var formData = new FormData();
    var files = $('#uploadedFile')[0].files[0];
    formData.append('file',files);
    formData.append('username',$('#inputName-update').val());
    formData.append('nit',$('#inputNit-update').val());
    formData.append('lastname',$('#inputLastName-update').val());
    formData.append('email',$('#inputEmail-update').val());
    formData.append('phonenumber',$('#inputPhoneNumber-update').val());
    formData.append('estado',$('#selectpickerState').val());
    formData.append('idRole',$('#selectpicker').val());
    formData.append('id_tiquetera',$('#selectpickerPlanUpdate').val());
    formData.append('id',idSelected);
    formData.append('role',$("#selectpicker option:selected").text());
    $.ajax({
        url: '../../admin/update.php',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          console.log(response);
          $('.help-block').hide();
          let json = JSON.parse(response);
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
        }
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
        var table;
        if(role != 'Administrador'){
          table = $('#example').DataTable({
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
              { title: "Estado", data: "estado" },
              { title: "Acción" , className: "actions-class" },
            ],
            "columnDefs": [{
              "targets": -1,
              "data": null,
              "className": "actions-class",
              "defaultContent": `
                              <button class='btn btn-warning edit'><i class="fas fa-edit"></i></button>
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
        } else {
            table = $('#example').DataTable({
            data: JSON.parse(res),
            retrieve: true,
            responsive: true,
            columns: [
              { title: "id", data: "id", visible: false },
              { title: "id_role", data: "id_role", visible: false },
              { title: "consent", data: "consent", visible: false },
              { title: "Cédula", data: "nit" },
              { title: "Nombre", data: "username" },
              { title: "Apellidos", data: "lastname" },
              { title: "Email", data: "email" },
              { title: "Teléfono", data: "phonenumber" },
              { title: "Role", data: "role" },
              { title: "Estado", data: "estado" },
              { title: "Acción" , className: "actions-class" },
            ],
            "columnDefs": [{
              "targets": -1,
              "data": null,
              "className": "actions-class",
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
        }

        $('#example tbody').on('click', '.edit', function () {
          $('#show-document').hide();
          let data = table.row($(this).parents('tr')).data();
          row = table.row($(this).parents('tr'));
          idSelected = data.id;
          $('#id-update').val(data.id);
          $('#inputName-update').val(data.username);
          $('#inputNit-update').val(data.nit);
          $('#inputLastName-update').val(data.lastname);
          $('#inputPhoneNumber-update').val(data.phonenumber);
          $('#inputEmail-update').val(data.email);
          $(".selectpicker").val(data.id_role);
          $(".selectpickerState").val(data.estado);
          $(".selectpickerPlan").val(data.id_tiquetera);
          if(data.consent != "" && data.consent != null){
            $('#show-document').show();
            $('#btn-show-document').attr('href','../../admin/download.php?file=' + data.consent.split('/')[2] + "&process=consent");
          }
          $('#update').show();
          $('#updateModal').modal('show');
        });

        $('#example tbody').on('click', '.delete', function () {
          row = table.row($(this).parents('tr'));
          //table.row( $(this).parents('tr')).remove().draw();
          $('#deleteModal').modal('show');

        });
        //End: datatable User
        userTable = table;
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
   * Optiene la lista de tiqueteras
   */
  function getTiqueteras() {
    let data = [{ name: 'tag', value: 'getTiqueteras' }];//esto permite saber que funcion del php voy a ejecutar
    var dataSet = $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {
        var json = JSON.parse(res);
        var table;
        if(role == 'Administrador'){
            table = $('#tiqueteras').DataTable({
            data: json,
            retrieve: true,
            responsive: true,
            columns: [
              { title: "id", data: "id", visible: false },
              { title: "Descripción", data: "description"},
              { title: "Días", data: "days" },
              { title: "Acción" , className: "actions-class" },
            ],
            "columnDefs": [{
              "targets": -1,
              "data": null,
              "className": "actions-class",
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
        }
        
        $('#tiqueteras tbody').on('click', '.edit', function () {
          $('#show-document').hide();
          let data = table.row($(this).parents('tr')).data();
          row = table.row($(this).parents('tr'));
          idSelected = data.id;
          $('#id-update').val(data.id);
          $('#descriptionTiqueterasUpdate').val(data.description);
          $('#daysTiqueterasUpdate').val(data.days);
          $('#update').show();
          $('#updateModal').modal('show');
        });

        $('#tiqueteras tbody').on('click', '.delete', function () {
          row = table.row($(this).parents('tr'));
          $('#deleteModal').modal('show');

        });
        //End: datatable User


        json.forEach(element => {
           $('.selectpickerPlan').append(`<option value=${element.id}>${element.description}</option>`);
        });

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
   * Optiene la lista de tiqueteras
   */
  function getBooking() {
    let data = [{ name: 'tag', value: 'getBooking' }];//esto permite saber que funcion del php voy a ejecutar
    var dataSet = $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {
        var json = JSON.parse(res);

        json.forEach((element,index) => {
          json[index].date = element.date.split(' ')[0];
        });

        
            table = $('#booking').DataTable({
            data: json,
            retrieve: true,
            responsive: true,
            columns: [
              { title: "id", data: "id", visible: false },
              { title: "Cédula", data: "nit"},
              { title: "Nombres", data: "username"},
              { title: "Apellidos", data: "lastname" },
              { title: "Fecha", data: "date"},
              { title: "Hora desde", data: "schedulerFrom"},
              { title: "Hora Hasta", data: "schedulerTo" },
            ],
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

$('#btn-export').click(function(e){
  var createXLSLFormatObj = [];
  var xlsHeader = ["Cedula","Nombres","Apellidos", "Fecha", "Hora Desde", "Hora Hasta" ];
  var xlsRows = [];
  let data = table.rows({"filter":"applied"}).data().toArray();

  data.forEach(element => {
     let row = {"Cedula":null,"Nombres":null,"Apellidos":null, "Fecha":null, "Hora Desde":null, "Hora Hasta":null,};
     row.Cedula = element.nit;
     row.Nombres = element.username ;
     row.Apellidos = element.lastname;
     row.Fecha = element.date;
     row["Hora Desde"] = element.schedulerFrom;
     row["Hora Hasta"] = element.schedulerTo;
     xlsRows.push(row);
  });


 createXLSLFormatObj.push(xlsHeader);
        $.each(xlsRows, function(index, value) {
            var innerRowData = [];
           // $("tbody").append('<tr><td>' + value.EmployeeID + '</td><td>' + value.FullName + '</td></tr>');
            $.each(value, function(ind, val) {

                innerRowData.push(val);
            });
            createXLSLFormatObj.push(innerRowData);
        });


        var filename = "Reporte_Reservas.xlsx";

        var ws_name = "Reporte_Reservas";

        if (typeof console !== 'undefined') console.log(new Date());
        var wb = XLSX.utils.book_new(),
            ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);

        XLSX.utils.book_append_sheet(wb, ws, ws_name);

        if (typeof console !== 'undefined') console.log(new Date());
        XLSX.writeFile(wb, filename);
        if (typeof console !== 'undefined') console.log(new Date());
})



$('#btn-export-users').click(function(e){
  var createXLSLFormatObj = [];
  var xlsHeader = ["Cedula","Nombres","Apellidos", "Email", "Teléfono", "Rol", "Estado" ];
  var xlsRows = [];
  let data = userTable.rows({"filter":"applied"}).data().toArray();

  data.forEach(element => {
     let row = {"Cedula":null,"Nombres":null,"Apellidos":null, "Email":null, "Telefono":null, "Rol":null, "Estado":null,};
     row.Cedula = element.nit;
     row.Nombres = element.username ;
     row.Apellidos = element.lastname;
     row.Email = element.lastname;
     row.Telefono = element.lastname;
     row.Rol = element.lastname;
     row.Estado = element.lastname;
     xlsRows.push(row);
  });


 createXLSLFormatObj.push(xlsHeader);
        $.each(xlsRows, function(index, value) {
            var innerRowData = [];
           // $("tbody").append('<tr><td>' + value.EmployeeID + '</td><td>' + value.FullName + '</td></tr>');
            $.each(value, function(ind, val) {

                innerRowData.push(val);
            });
            createXLSLFormatObj.push(innerRowData);
        });


        var filename = "Reporte_Usuarios.xlsx";

        var ws_name = "Reporte_Usuarios";

        if (typeof console !== 'undefined') console.log(new Date());
        var wb = XLSX.utils.book_new(),
            ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);

        XLSX.utils.book_append_sheet(wb, ws, ws_name);

        if (typeof console !== 'undefined') console.log(new Date());
        XLSX.writeFile(wb, filename);
        if (typeof console !== 'undefined') console.log(new Date());
})



  //Ejecución del registro 
  $('#register-tiquetera').click(function (e) {
    e.preventDefault();
    var data = $('#register-form').serializeArray();
    data.push({ name: 'tag', value: 'registerTiquetera' });
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
          let table = $('#tiqueteras').DataTable();
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
  $("#update-tiquetera").on('click', function(e){
    var formData = new FormData();
    formData.append('descriptionTiqueteras',$('#descriptionTiqueterasUpdate').val());
    formData.append('daysTiqueteras',$('#daysTiqueterasUpdate').val());
    formData.append('id',idSelected);
    formData.append('tag','updateTiquetera');
    $.ajax({
        url: '../../admin/functions.php',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          $('.help-block').hide();
          let json = JSON.parse(response);
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
        }
    });
    return false;
});


  //Elimina una tiquetera
  $('#delete-tiquetera').click(function (e) {
    e.preventDefault();
    let data = [];
    data.push({ name: "id", value: row.data().id });
    data.push({ name: 'tag', value: 'deleteTiquetera' });//esto permite saber que funcion del php voy a ejecutar

    $.ajax({
      url: '../../admin/functions.php',
      type: "post",
      data,
      beforeSend: function () {
        $('.fa').css('display', 'inline');
      }
    })
      .done(function (res) {// true
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





