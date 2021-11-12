$(function () {
	$(".buttonmenu").click(function(){
	 $("#wrapper").toggleClass("toggled");
	 $(".mainmenubtn").addClass("btn");

	});

	$(".mainmenubtn").click(function(){
		$(".mainmenubtn").removeClass("btn");
	 	$("#wrapper").removeClass("toggled");
	});

	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
	 	$(".list-group-item.list-group-item-action").click(function(){
			$("#wrapper").removeClass("toggled");
		});
	}

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	//profile image upload
	//open profile image modal
  	$(document).on("click", ".choose-file-btn", function() { 
        $("#upload").trigger('click');
    });
	var $uploadCrop = "";
	$(document).on("change", '#upload', function() {

	      var value = $(this).val(),
	          file = value.toLowerCase(),
	          extension = file.substring(file.lastIndexOf('.') + 1);

	      $(".err").html("")
	      let allowedExtensions = ['jpg', 'jpeg', 'png']
	      if ($.inArray(extension, allowedExtensions) == -1) {
	          $(".err").html("<p style='color:red;'>Please select only: jpg, jpeg, png format.</p>");
	          return false;
	      }

	      $('#upload-demo').croppie('destroy');
	      $('.upload-result').show();
	      $uploadCrop = $('#upload-demo').croppie({
	          enableExif: true,
	          enableOrientation: true,
	          viewport: {
	              width: 200,
	              height: 200
	          },
	          boundary: {
	              width: 300,
	              height: 300
	          }
	      });

	      var reader = new FileReader();
	      reader.onload = function(e) {
	          $uploadCrop.croppie('bind', {
	              url: e.target.result
	          }).then(function() {
	            $('.vanilla-rotate').show();
	          });
	      }
	      reader.readAsDataURL(this.files[0]);
	  });
	  $(document).on('click', '.vanilla-rotate', function(ev) {
	    $uploadCrop.croppie('rotate', parseInt($(this).data('deg')));
	  });
	  $(document).on('click', '.upload-result', function(ev) {
	      $uploadCrop.croppie('result', {
	          type: 'canvas',
	          size: 'viewport'
	      }).then(function(resp) {
	          var userType  = $('#userType').val();
	          $.ajax({
	              url: "/"+ userType +"/upload/profile",
	              type: "POST",
	              data: {
	                  "image": resp
	              },
	              dataType: 'JSON',
	              success: function(data) {
	                  $("#edit-photo").modal("hide");
	                  $(".profile-img").attr('src', resp)
	                  $('#upload-demo').croppie('destroy');
	                  $('.upload-result').hide();
	                  $('#edit-photo').modal('hide');
	                  if(data.success) {
	                    swal('Profile Updated', 'Your profile picture has been updated.', "success");
	                  } else {
	                    swal('Error', data.message, "error");
	                  }
	              },
	              error: function(err) {
	                  swal("Error!", "Please try again", "error");
	              }
	          });
	      });
	  });

	//calendar
	$('#calendar').fullCalendar({
	    header: {
	      left: 'prev,next,today',
	      center: 'title',
	      right: 'month,agendaWeek,agendaDay'
	      },
  	});

  	if($('.alert.alert-success').length > 0) {
	    setTimeout(function() {
	      $('.alert.alert-success').fadeOut('slow');
	    },5000);
	}

	if($('.alert.alert-danger').length > 0) {
	    setTimeout(function() {
	      $('.alert.alert-danger').fadeOut('slow');
	    },5000);
	}

	// $(document).on('click' , '#importStudentsBtn', function () {
	// 	if($('#file-upload').val()) {
	// 		//$('input[type=submit]').click(function() {
	// 		    $(this).attr('disabled', 'disabled');
	// 		    $(this).parents('form').submit();
	// 		//});
	// 	}
	// })
	// $('form#importSudentsForm').submit(function(e){
	//   if( $(this).hasClass('form-submitted') ){
	//     e.preventDefault();
	//     return;
	//   }
	//   $(this).addClass('form-submitted');
	// });
});