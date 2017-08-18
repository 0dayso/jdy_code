/* JS */


/* Widget close */

 

/* Calendar */
  $(document).ready(function() {
  
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    
    $('#calendar').fullCalendar({
      header: {
        left: 'prev',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,next'
      },
      editable: true,
      events: [
        {
          title: 'All Day Event',
          start: new Date(y, m, 1)
        },
        {
          title: 'Long Event',
          start: new Date(y, m, d-5),
          end: new Date(y, m, d-2)
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: new Date(y, m, d-3, 16, 0),
          allDay: false
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: new Date(y, m, d+4, 16, 0),
          allDay: false
        },
        {
          title: 'Meeting',
          start: new Date(y, m, d, 10, 30),
          allDay: false
        },
        {
          title: 'Lunch',
          start: new Date(y, m, d, 12, 0),
          end: new Date(y, m, d, 14, 0),
          allDay: false
        },
        {
          title: 'Birthday Party',
          start: new Date(y, m, d+1, 19, 0),
          end: new Date(y, m, d+1, 22, 30),
          allDay: false
        },
        {
          title: 'Click for Google',
          start: new Date(y, m, 28),
          end: new Date(y, m, 29),
          url: 'http://google.com/'
        }
      ]
    });
    
  });

/* Progressbar animation */

    setTimeout(function(){

        $('.progress-animated .progress-bar').each(function() {
            var me = $(this);
            var perc = me.attr("data-percentage");

            //TODO: left and right text handling

            var current_perc = 0;

            var progress = setInterval(function() {
                if (current_perc>=perc) {
                    clearInterval(progress);
                } else {
                    current_perc +=1;
                    me.css('width', (current_perc)+'%');
                }

                me.text((current_perc)+'%');

            }, 600);

        });

    },600);

/* Slider */

    $(function() {
        // Horizontal slider
        $( "#master1, #master2" ).slider({
            value: 60,
            orientation: "horizontal",
            range: "min",
            animate: true
        });

        $( "#master4, #master3" ).slider({
            value: 80,
            orientation: "horizontal",
            range: "min",
            animate: true
        });        

        $("#master5, #master6").slider({
            range: true,
            min: 0,
            max: 400,
            values: [ 75, 200 ],
            slide: function( event, ui ) {
                $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
            }
        });


        // Vertical slider 
        $( "#eq > span" ).each(function() {
            // read initial values from markup and remove that
            var value = parseInt( $( this ).text(), 10 );
            $( this ).empty().slider({
                value: value,
                range: "min",
                animate: true,
                orientation: "vertical"
            });
        });
    });



/* Support */

$(document).ready(function(){
  $("#slist a").click(function(e){
     e.preventDefault();
     $(this).next('p').toggle(200);
  });
});



/* jQuery Notification */

$(document).ready(function(){

  //setTimeout(function() {noty({text: '<strong>Howdy! Hope you are doing good...</strong>',layout:'topRight',type:'information',timeout:15000});}, 7000);

  //setTimeout(function() {noty({text: 'This is an all in one theme which includes Front End, Admin & E-Commerce. Dont miss it. Grab it now',layout:'topRight',type:'alert',timeout:13000});}, 9000);

});


$(document).ready(function() {

  $('.noty-alert').click(function (e) {
      e.preventDefault();
      noty({text: 'Some notifications goes here...',layout:'topRight',type:'alert',timeout:2000});
  });

  $('.noty-success').click(function (e) {
      e.preventDefault();
      noty({text: 'Some notifications goes here...',layout:'top',type:'success',timeout:2000});
  });

  $('.noty-error').click(function (e) {
      e.preventDefault();
      noty({text: 'Some notifications goes here...',layout:'topRight',type:'error',timeout:2000});
  });

  $('.noty-warning').click(function (e) {
      e.preventDefault();
      noty({text: 'Some notifications goes here...',layout:'bottom',type:'warning',timeout:2000});
  });

  $('.noty-information').click(function (e) {
      e.preventDefault();
      noty({text: 'Some notifications goes here...',layout:'topRight',type:'information',timeout:2000});
  });

});


/* Date picker */

  $(function() {
    $('#datetimepicker1').datetimepicker({
      pickTime: false
    });
  });

   $(function() {
    $('#datetimepicker2').datetimepicker({
      pickDate: false
    });
  });

/* CL Editor */

$(".cleditor").cleditor({
    width: "auto",
    height: "auto"
});

/* Modal fix */

$('.modal').appendTo($('body'));

/* Pretty Photo for Gallery*/

jQuery("a[class^='prettyPhoto']").prettyPhoto({
overlay_gallery: false, social_tools: false
});