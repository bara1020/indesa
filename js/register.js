$(document).ready(function () {
    var schedulerSelected;
    $('#focus').focus();
    $(window).scrollTop(0);
    $('#containersuccessupdate').hide();

    $('input[type="checkbox"]').click(function(){
        if($(this).prop("checked") == true){
            $(this).val("true");
        }
        else if($(this).prop("checked") == false){
            $(this).val("false");
        }
    });

    getConfiguration();


    $('#uploadedFile').inputFileText({
        text: 'Cargar Archivo', // The button will display this text
    });
    var today = new Date();
    var dd = today.getDate();

    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }



    function getDay(number) {
        var d = new Date();
        var weekday = new Array(7);
        weekday[0] = "Sunday";
        weekday[1] = "Monday";
        weekday[2] = "Tuesday";
        weekday[3] = "Wednesday";
        weekday[4] = "Thursday";
        weekday[5] = "Friday";
        weekday[6] = "Saturday";

        return weekday[number];
    }

    $('#dateQuote').attr('min', `${yyyy}-${mm}-${dd}`);
    $('#dateQuoteUpdate').attr('min', `${yyyy}-${mm}-${dd}`);
    

    var data = [];
    var day;
    var date;
    //Begin: Get Date
    $('#dateQuote').change(function () {
        getDate('#dateQuote','');
    });

    $('#dateQuoteUpdate').change(function () {
        getDate('#dateQuoteUpdate','update');
    });


    function getDate(id,update){
        data = [];
        let dateSelected = new Date($(id).val());
        date = `${dateSelected.getFullYear()}-${dateSelected.getMonth() + 1}-${dateSelected.getDate() + 1}`;
        $(`#date${update}`).val(date);//2020-08-24 15:36:04
        day = dateSelected.getDayName().toLowerCase();
        $(`#day${update}`).val( day.toLowerCase());//2020-08-24 15:36:04

        if (day == 'wednesday')
            day = 'webnesday';

        data.push({ name: 'day', value: day });//esto permite saber que funcion del php voy a ejecutar
        data.push({ name: 'date', value: date });//esto permite saber que funcion del php voy a ejecutar
        data.push({ name: 'tag', value: 'getDateScheduler' });//esto permite saber que funcion del php voy a ejecutar
        console.log(data);
        $.ajax({
            url: '../../admin/functions_register.php',
            type: "post",
            data,
            beforeSend: function () {
                $('.fa').css('display', 'inline');
            }
        })
            .done(function (res) {// true
                console.log(res);
                let json = JSON.parse(res);
                $('.list-group' + update).empty();
                $('.list-group' + update).append(`<div href="#" class="list-group-item list-group-item-action bg-primary text-white">
                  <div class="row">
                    <div class="col-md-10 col-sm-11 col-xs-11">
                        Horario
                    </div>
                    <div class="col-md-2 col-sm-11 col-xs-1 text-center">
                          Disponibilidad
                    </div>
                  </div>
              </div>
              `);
                json.forEach((element, index) => {
                    if(element.counter != 0) {
                        $('.list-group' + update).append(`
                        <div id="${index}" class="list-group-item list-group-item-action item-list${update}">
                        <div class="row"> 
                            <div id="${index}-scheduler" class="col-md-10 col-sm-11 col-xs-11"> 
                            ${hourFormater(element.schedulerFrom)} - ${hourFormater(element.schedulerTo)}
                            </div>
                            <div id="${index}-counter" class="col-md-2 col-sm-1 col-xs-1 text-center"> 
                            ${element.counter}
                            </div>
                        </div>
                        </div>
                        `);
                    }
                });
               
                //Begin: Click item
                $('.item-list' + update).click(function () {
                    const id = $(this).attr('id');
                    $('.item-list' + update).removeClass('active');
                    $(`#${id}`).addClass("active");
                    $('#schedulerFrom' + update).val(json[Number(id)].schedulerFrom);
                    $('#schedulerTo' + update).val(json[Number(id)].schedulerTo);
                });

               // console.log(hourFormater(json.schedulerTo));
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
    //End: Get Date

    $('#update').click(function(e){
        $('#nit-error-update').text('');
        $('#date-error-update').text('');
        $('#scheduler-error-update').text('');
        e.preventDefault();
        var data = $('#update-form').serializeArray();
        console.log(data);
        const scheduler = {};
        dataObj = {};

        $(data).each(function(i, field){
        scheduler[field.name] = field.value;
        });

        schedulerSelected = " de " + hourFormater(scheduler['schedulerFromUpdate']) + " hasta las " + hourFormater(scheduler['schedulerToUpdate']);
               
        data.push({ name: "nit", value: $('#inputNit').val() });
        data.push({ name: 'tag', value: 'insertScheduler' });
        $.ajax({
            url: '../../admin/functions_register.php',
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

                if(json.status){
                    $('#containersuccessupdate').show();
                    $("#update-form")[0].reset();
                    $(window).scrollTop(0);
                    $('.list-groupupdate').empty();
                    let labelSchedulerSelected = $('#labelDateQuoteUpdate').text();
                    $('#labelDateQuoteUpdate').text(labelSchedulerSelected + schedulerSelected);
                } else {
                    $('#containersuccessupdate').hide();
                }
               
               // console.log(hourFormater(json.schedulerTo));
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

    function getConfiguration(){
        var data = [];
        data.push({ name: 'tag', value: 'getConfiguration' });
        $.ajax({
            url: '../../admin/functions_register.php',
            type: "post",
            data,
            beforeSend: function () {
                $('.fa').css('display', 'inline');
            }
        })
            .done(function (res) {// true
                let json = JSON.parse(res);
                var now = new Date();
                var day = ("0" + (now.getDate()+1)).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);

                var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
                if(json.booking_type == 'diario'){
                    console.log(date);
                    $('#dateQuoteUpdate').val(today);
                    $('#labelDateQuoteUpdate').text(today);
                    $('#dateQuoteUpdate').hide();
                    getDate('#dateQuoteUpdate','update'); 
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
    }


    (function () {
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        Date.prototype.getDayName = function () {
            return days[this.getDay() + 1];
        };
    })();

    function hourFormater(time) {
        time = time.split(':'); // convert to array

        // fetch
        var hours = Number(time[0]);
        var minutes = Number(time[1]);
        var seconds = Number(time[2]);

        // calculate
        var timeValue;

        if (hours > 0 && hours < 10) {
            timeValue = "0" + hours;
        }else if (hours >= 10 && hours <= 12) {
            timeValue = hours;
        } else if (hours > 12) {
            timeValue = "" + (hours - 12);
        } else if (hours == 0) {
            timeValue = "12";
        }

        timeValue += (minutes < 10) ? ":0" + minutes : ":" + minutes;  // get minutes
        timeValue += (hours >= 12) ? " P.M." : " A.M.";  // get AM/PM
        return timeValue;
    }
});