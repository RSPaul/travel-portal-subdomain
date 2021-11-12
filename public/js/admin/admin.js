$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#hotel_name_static').keyup(function() {
      //console.log('==> ', $('#CityId').val(), $('#CityId option:selected').val());
      $('#hotel_name_static').autocomplete({
          source: '/api/admin/hotels/' + $('#CityId').val() + '/' + $('#hotel_name_static').val(),
          select: function (event, ui) {
              //console.log('You selected: ' + ui.item.value + ', ' + ui.item.id);
              $('#prefredHotel').val(ui.item.id);
          }
      });
    });

    $('#searchFotelForm').submit(function(e) {
      e.preventDefault();
      $('#dataTable').DataTable().clear().destroy();
      $('#hotelSearchError').hide();
      $('#searchHotelBtn').html('Please wait&nbsp;<i class="fa fa-spinner" aria-hidden="true"></i>');
      $.ajax({
        url: '/api/admin/hotel/search/'+ $('#hotel_name').val(),
        method: 'POST',
        dataType: 'JSON',
        success: function(response) {
          $('#searchHotelBtn').html('Search Hotels');
          var tr = '';
          if(response && response.length) {
            for(var i = 0; i < response.length; i++) {
              tr = tr + '<tr>';
              tr = tr + '<td>' + response[i].hotel_name + '</td>';
              tr = tr + '<td>' + response[i].CityName + '</td>';
              tr = tr + '<td>' + response[i].Country + '</td>';
              tr = tr + '<td><a href="/admin/hotel-room-images/'+ response[i].hotel_code +'" target="_blank">Show Rooms</a></td>';
              tr = tr + '</tr>';
            }
            $('#hotelListBody').html(tr);
            
            $('#dataTable').DataTable();
            $("#hotelSearchError").html('');
            $('#hotelSearchError').hide();
          } else {
            $('#hotelListBody').html('');
            $('#hotelSearchError').show();
            $("#hotelSearchError").html('No hotels found for your serach.');
          }
        },
        error: function(xhr, error) {
          console.log('error ',xhr, error );
          $('#hotelSearchError').show();
          $('#searchHotelBtn').html('Search Hotels');
          $("#hotelSearchError").html(xhr);
        }
      });
    });

    // var table = $('#roomImagesTable').DataTable({
    //    dom: 'lr<"table-filter-container">tip',
    //    initComplete: function(settings){
    //       var api = new $.fn.dataTable.Api( settings );
    //       $('.table-filter-container', api.table().container()).append(
    //          $('#table-filter').detach().show()
    //       );
          
    //       $('#table-filter select').on('change', function(){
    //          table.search(this.value).draw();   
    //       });       
    //    }
    // });
    
    $('.datepicker').datepicker({
       autoclose: true, 
       todayHighlight: true,
       minDate: new Date(),
       startDate: "dateToday",
       format: 'dd-mm-yyyy'
    });

    $('.start-date').daterangepicker({
        ranges: true,
        autoApply: true,
        maxDate: moment().startOf('hour').add(72, 'hour'),
        applyButtonClasses: false,
        autoUpdateInput: false
    },function (start, end) {

        var startDay = start.format('DD/MMM/YYYY');
        var endDay = end.format('DD/MMM/YYYY');

        $('.start-date').val(startDay.replace(/\//g, ' '));
        $('.end-date').val(endDay.replace(/\//g, ' '));
    });

    $('.end-date').daterangepicker({
        ranges: true,
        autoApply: true,
        applyButtonClasses: false,
        autoUpdateInput: false
    },function (start, end) {
        $('.start-date').val(start.format('DD/MM/YYYY'));
        $('.end-date').val(end.format('DD/MM/YYYY'));
    });

});

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('earningsCalendar');
  if(calendarEl) {
  var earningsCalendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: 'UTC',
    themeSystem: 'bootstrap',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    weekNumbers: true,
    dayMaxEvents: true, // allow "more" link when too many events
    events: [{
          title: '$0.00',
          start: '2020-08-01'
        },
        {
          title: '$0.00',
          start: '2020-08-02'
        },
        {
          title: '$0.00',
          start: '2020-08-03'
        },
        {
          title: '$0.00',
          start: '2020-08-04'
        },
        {
          title: '$0.00',
          start: '2020-08-05'
        },
        {
          title: '$0.00',
          start: '2020-08-06'
        },
        {
          title: '$0.00',
          start: '2020-08-07'
        },
        {
          title: '$0.00',
          start: '2020-08-08'
        },
        {
          title: '$0.00',
          start: '2020-08-09'
        },
        {
          title: '$0.00',
          start: '2020-08-10'
        },
        {
          title: '$0.00',
          start: '2020-08-11'
        },
        {
          title: '$0.00',
          start: '2020-08-12'
        },
        {
          title: '$0.00',
          start: '2020-08-13'
        },
        {
          title: '$0.00',
          start: '2020-08-14'
        },
        {
          title: '$0.00',
          start: '2020-08-15'
        },
        {
          title: '$0.00',
          start: '2020-08-16'
        },
        {
          title: '$0.00',
          start: '2020-08-17'
        },
        {
          title: '$0.00',
          start: '2020-08-18'
        },
        {
          title: '$0.00',
          start: '2020-08-19'
        },
        {
          title: '$0.00',
          start: '2020-08-20'
        },
        {
          title: '$0.00',
          start: '2020-08-21'
        },
        {
          title: '$0.00',
          start: '2020-08-22'
        },
        {
          title: '$0.00',
          start: '2020-08-23'
        },
        {
          title: '$0.00',
          start: '2020-08-24'
        },
        {
          title: '$0.00',
          start: '2020-08-25'
        },
        {
          title: '$0.00',
          start: '2020-08-26'
        },
        {
          title: '$0.00',
          start: '2020-08-27'
        },
        {
          title: '$0.00',
          start: '2020-08-28'
        },
        {
          title: '$0.00',
          start: '2020-08-29'
        },
        {
          title: '$0.00',
          start: '2020-08-30'
        },
        {
          title: '$0.00',
          start: '2020-08-31'
        }],//'https://fullcalendar.io/demo-events.json'
  });

  earningsCalendar.render();
  }
});

$(document).ready(function(){
  $('.delete-btn').click(function(e){
    e.preventDefault();
    var btn = $(this);
    swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this data!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $('#item_delete_' + btn.data('id')).submit();
      }
    });
  });

  $('#dataTable').DataTable();
});

$(document).ready(function(){
  $('.delete-btn-lottery').click(function(e){
    e.preventDefault();
    var btn = $(this);
    console.log('==> ', btn.data('status'));
    if(btn.data('status') == 'active') {

      swal({
        title: "Wait!",
        text: "You can not delete an active lottery.",
        icon: "warning",
      });

    } else {

      swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this data!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $('#item_delete_' + btn.data('id')).submit();
        }
      });
    }
  });

  $('#dataTable').DataTable();
});

$(function () {
    var opts = $('#CityId option').map(function () {
        return [[this.value, $(this).text()]];
    });
    $('#someinput').keyup(function () {
        var rxp = new RegExp($('#someinput').val(), 'i');
        var optlist = $('#CityId').empty();
        opts.each(function (value ,index) {
            if (rxp.test(this[1])) {
            //  console.log(value, index);
                optlist.append($('<option/>').attr('value', this[0]).text(this[1]));
            }
        });

    });

    $('.approve-account').click(function() {
      var el = $(this);
      swal({
        title: "Are you sure?",
        text: "You will be approving this account for payment withdrawals!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url: '/admin/approve/bank-account',
            type: 'POST',
            dataType: 'JSON',
            data: {id: el.data('id'), user: el.data('user')},
            success: function(response) {
              swal(response.message, {
                icon: "success",
              });

              setTimeout(function(){
                window.location.reload();
              },2000);
            },
            error: function(error, status) {
              console.log(error, status);
              swal("Error", error.responseJSON.message, "error");
            }
          })
        }
      });

    });

    $('.approve-payment').click(function() {
      var el = $(this);
      swal({
        title: "Are you sure?",
        text: "You will be approving the payment for $" + el.data('amount'),
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url: '/admin/approve/payment',
            type: 'POST',
            dataType: 'JSON',
            data: {user: el.data('user'), amount: el.data('amount')},
            success: function(response) {
              swal(response.message, {
                icon: "success",
              });

              setTimeout(function(){
                window.location.reload();
              },2000);
            },
            error: function(error, status) {
              console.log(error, status);
              swal("Error", error.responseJSON.message, "error");
            }
          })
        }
      });

    });

    $('.approve-payment-req').click(function() {
      var el = $(this);
      swal({
        title: "Enter Amount",
        text: "Enter the amount you want to add to " +  el.data('name') + "\'s wallet." ,
        icon: "warning",
        buttons: true,
        dangerMode: true,
        content: "input",
        inputPlaceholder: "Write something"
      })
      .then((willDelete) => {
        if (willDelete && willDelete!='') {
          $.ajax({
            url: '/admin/approve/wallet-payment',
            type: 'POST',
            dataType: 'JSON',
            data: {name: el.data('name'), amount: willDelete, id: el.data('id'), user: el.data('user')},
            success: function(response) {
              swal(response.message, {
                icon: "success",
              });

              setTimeout(function(){
                window.location.reload();
              },2000);
            },
            error: function(error, status) {
              console.log(error, status);
              swal("Error", error.responseJSON.message, "error");
            }
          })
        }
      });

    });

    $('#media_link').keyup(function() {
      $('.media_link_preview').attr('src', $(this).val());
      $('.media_link_preview').show();
      if($(this).val() == '') {
        $('.media_link_preview').hide();
      }
    });
});
/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
  this.classList.toggle("active");
  var dropdownContent = this.nextElementSibling;
  if (dropdownContent.style.display === "block") {
  dropdownContent.style.display = "none";
  } else {
  dropdownContent.style.display = "block";
  }
  });
}