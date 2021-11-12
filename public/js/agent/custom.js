var totalWithdrawAmt = 0;
var totalEarnedAmt = 0;
var selectedIdsF = [];
var selectedIdsO = [];
var totalEarnings = parseFloat($('#totalEarnings').html());
function copyToClip(el) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($('#' + el).val()).select();
    document.execCommand("copy");
    $temp.remove();

    $('#' + el + '-tag').html("Use ctrl + v to paste the widget code.<i data-feather='check-circle'></i>");
    $('#' + el + '-tag').addClass('copied');
}

/*=========================================================================================
  File Name: form-validation.js
  Description: jquery bootstrap validation js
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: PIXINVENT
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
var direction = 'ltr';
$(function () {
  'use strict';

  var formValidation = $('.formValidation');

  // Bootstrap Validation
  // --------------------------------------------------------------------
  if (formValidation.length) {
    Array.prototype.filter.call(formValidation, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          form.classList.add('invalid');
        } else {
        	submitForm(formValidation);
        }
        form.classList.add('was-validated');
        event.preventDefault();
      });
    });
  }

  var profileGeneral = $('#profileGeneral');

  // Bootstrap Validation
  // --------------------------------------------------------------------
  if (profileGeneral.length) {
    Array.prototype.filter.call(profileGeneral, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          form.classList.add('invalid');
        } else {
          submitProfileForm(profileGeneral);
        }
        form.classList.add('was-validated');
        event.preventDefault();
      });
    });
  }

  var profileInformation = $('#profileInformation');

  // Bootstrap Validation
  // --------------------------------------------------------------------
  if (profileInformation.length) {
    Array.prototype.filter.call(profileInformation, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          form.classList.add('invalid');
        } else {
          submitProfileInfoForm(profileInformation);
        }
        form.classList.add('was-validated');
        event.preventDefault();
      });
    });
  }

	  var accountUploadImg = $('.profile-pic-preview'),
	    accountUploadBtn = $('#account-upload');

	  // Update user photo on click of button
	  if (accountUploadBtn) {
	    accountUploadBtn.on('change', function (e) {
	      var reader = new FileReader(),
	        files = e.target.files;
	      reader.onload = function () {
	        if (accountUploadImg) {
	          accountUploadImg.attr('src', reader.result);
	          $('#acount-image-src').val(reader.result);
	        }
	      };
	      reader.readAsDataURL(files[0]);
	    });
	  }

	var postMediaPre = $('.post_media-preview'),
	    postMediaBtn = $('#post_media');

	  // Update user photo on click of button
	  if (postMediaBtn) {
	    postMediaBtn.on('change', function (e) {
	      var reader = new FileReader(),
	        files = e.target.files;
	      reader.onload = function () {
	        if (postMediaPre) {
	          postMediaPre.attr('src', reader.result);
	          $('#post_media_hidden').val(reader.result);
	          if($('#post_type option:selected').val() == 'article_image') {
	          	
	          	$('#post_media-preview-img').show();
	          	$('#post_media-preview-vid').hide()

	          } else if ($('#post_type option:selected').val() == 'article_video') {

	          	$('#post_media-preview-img').hide();
	          	$('#post_media-preview-vid').show()

	          }
	        }
	      };
	      reader.readAsDataURL(files[0]);
	    });
	  }

	$('#post_type').change(function() {
		if($(this).val() == 'article_image') {

			$('#post_media').attr('required', 'true');
			$('#post_media_link').removeAttr('required');

			$('#post-image').show();
			$('#post-video').hide();

		} else if($(this).val() == 'article_video'){

			$('#post_media_link').attr('required', 'true');
			$('#post_media').removeAttr('required');

			$('#post-video').show();
			$('#post-image').hide();

		} else {

			$('#post_media').removeAttr('required');
			$('#post_media_link').removeAttr('required');

			$('#post-image').hide();
			$('#post-video').hide();
		}
	});

  $("#hb_all").click(function() {
    selectAllPayments('hotel', $(this).prop('checked'));
  });
  $("#fb_all").click(function() {
    selectAllPayments('flight', $(this).prop('checked'));
  });
  $("#ab_all").click(function() {
    selectAllPayments('act', $(this).prop('checked'));
  });
  $("#cb_all").click(function() {
    selectAllPayments('cab', $(this).prop('checked'));
  });

  $(".hb-select").click(function() {
    selectPayment('hotel', $(this));
  });
  $(".fb-select").click(function() {
    selectPayment('flight', $(this));
  });
  $(".ab-select").click(function() {
    selectPayment('act', $(this));
  });
  $(".cb-select").click(function() {
    selectPayment('cab', $(this));
  });

});

function submitForm(formElement) {
  $.ajax({
		url: formElement.attr('action'),
		type: 'POST',
		dataType: 'JSON',
		data: $('#' + formElement.attr('id')).serialize(),
		success: function(response) {

			if(response.success) {
				
				toastr['success'](response.message, {
			        closeButton: true,
			        tapToDismiss: false,
			        rtl: direction
		      	});

            setTimeout(function() {
		      	  if($('#' + formElement.attr('id')).find('#reload').val() == 'true') {
		      		  window.location.href = window.location.href;
		      	  }
            },1000);

            if($('#' + formElement.attr('id')).find('#clear_form_' +  formElement.attr('id')).val() == 'true') {
              if($('#commentForm_' + formElement.attr('id')).length) {
                $('#commentForm_' + formElement.attr('id'))[0].reset();
              }
              if($('#' + formElement.attr('id')).length) {
                $('#' + formElement.attr('id'))[0].reset();
              }
            }

			} else {

				toastr['error'](response.message, {
			        closeButton: true,
			        tapToDismiss: false,
			        rtl: direction
		      	});
			}	      	
		},
		error: function(error, status) {
			
			toastr['error'](error.responseJSON.message, {
		        closeButton: true,
		        tapToDismiss: false,
		        rtl: direction
	      	});
		}
	})
}

function submitProfileForm(formElement) {
  $.ajax({
    url: formElement.attr('action'),
    type: 'POST',
    dataType: 'JSON',
    data: $('#' + formElement.attr('id')).serialize(),
    success: function(response) {

      if(response.success) {
        
        toastr['success'](response.message, {
              closeButton: true,
              tapToDismiss: false,
              rtl: direction
            });

            setTimeout(function() {
              if($('#' + formElement.attr('id')).find('#reload').val() == 'true') {
                window.location.href = window.location.href;
              }
            },1000);

            if($('#' + formElement.attr('id')).find('#clear_form_' +  formElement.attr('id')).val() == 'true') {
              if($('#commentForm_' + formElement.attr('id')).length) {
                $('#commentForm_' + formElement.attr('id'))[0].reset();
              }
              if($('#' + formElement.attr('id')).length) {
                $('#' + formElement.attr('id'))[0].reset();
              }
            }

      } else {

        toastr['error'](response.message, {
              closeButton: true,
              tapToDismiss: false,
              rtl: direction
            });
      }         
    },
    error: function(error, status) {
      
      toastr['error'](error.responseJSON.message, {
            closeButton: true,
            tapToDismiss: false,
            rtl: direction
          });
    }
  })
}

function submitProfileInfoForm(formElement) {
  $.ajax({
    url: formElement.attr('action'),
    type: 'POST',
    dataType: 'JSON',
    data: $('#' + formElement.attr('id')).serialize(),
    success: function(response) {

      if(response.success) {
        
        toastr['success'](response.message, {
              closeButton: true,
              tapToDismiss: false,
              rtl: direction
            });

            setTimeout(function() {
              if($('#' + formElement.attr('id')).find('#reload').val() == 'true') {
                window.location.href = window.location.href;
              }
            },1000);

            if($('#' + formElement.attr('id')).find('#clear_form_' +  formElement.attr('id')).val() == 'true') {
              if($('#commentForm_' + formElement.attr('id')).length) {
                $('#commentForm_' + formElement.attr('id'))[0].reset();
              }
              if($('#' + formElement.attr('id')).length) {
                $('#' + formElement.attr('id'))[0].reset();
              }
            }

      } else {

        toastr['error'](response.message, {
              closeButton: true,
              tapToDismiss: false,
              rtl: direction
            });
      }         
    },
    error: function(error, status) {
      
      toastr['error'](error.responseJSON.message, {
            closeButton: true,
            tapToDismiss: false,
            rtl: direction
          });
    }
  })
}


 // var commentsForm = $('.commentsForm');
 //  if (commentsForm.length) {
 //    Array.prototype.filter.call(commentsForm, function (form) {
 //      form.addEventListener('submit', function (event) {
 //        if (form.checkValidity() === false) {
 //          form.classList.add('invalid');
 //        } else {
 //          submitCommentsForm(commentsForm);
 //        }
 //        form.classList.add('was-validated');
 //        event.preventDefault();
 //      });
 //    });
 //  }

$('.comment-text').keyup(function() {
  if($(this).val()) {

    $('#submit_form_' + $(this).attr('data-id')).removeAttr('disabled');

  } else {
    $('#submit_form_' + $(this).attr('data-id')).attr('disabled', 'true');
  }
});

$('.comment-submit').click(function(e) {
    e.preventDefault();
    var postId = $(this).attr('data-id');
    var commentsForm = $('#comment_' + postId);
    submitCommentsForm(postId);
});

function submitCommentsForm(postId) {
 
  $.ajax({
    url: $('#commentForm_' + postId).attr('action'),
    type: 'POST',
    dataType: 'JSON',
    data: {comment: $('#comment_' + postId).val(), 'post_id': $('#post_id_' + postId).val()},
    success: function(response) {

      if(response.success) {
        
        toastr['success'](response.message, {
              closeButton: true,
              tapToDismiss: false,
              rtl: direction
            });

            

        var picture = $('#user_picture').val();
        var name = $('#user_name').val();
        var html = '<div class="d-flex align-items-start mb-1"><div class="avatar mt-25 mr-75"><img src="/uploads/profiles/' + picture +'" alt="Avatar" height="34" width="34" /></div><div class="profile-user-info w-100"><div class="d-flex align-items-center justify-content-between"><h6 class="mb-0">'+ name +'</h6><a href="javascript:void(0)"><i class="fa fa-heart-o text-body font-medium-3"></i><span class="align-middle text-muted">34</span></a></div><small>'+ $('#comment_' + postId).val() +'</small></div></div>';

        if($('#comments_' + postId).find('.single-comment').length) {

          $('#comments_' + postId).find('.single-comment').first().before(html);

        } else {

          $('#comments_' + postId).html(html);
        }

        if($('#clear_form_' +  postId).val() == 'true') {
          $('#commentForm_' + postId)[0].reset();
        }

        $('#submit_form_' + postId).attr('disabled', 'true');

        $('#comment_count_' + postId).html(parseInt(parseInt($('#comment_count_' + postId).html()) + 1));

      } else {

        toastr['error'](response.message, {
              closeButton: true,
              tapToDismiss: false,
              rtl: direction
            });
      }         
    },
    error: function(error, status) {
      
      toastr['error'](error.responseJSON.message, {
            closeButton: true,
            tapToDismiss: false,
            rtl: direction
          });
    }
  })
}

$('.profile-likes').click(function() {
  
  var action = 'like';
  var postId = $(this).attr('data-id');
  var likes = $(this).attr('data-likes');
  var btn = $(this);

  if($(this).hasClass('fa-heart-o')) {

    $(this).removeClass('fa-heart-o');
    $(this).addClass('fa-heart');

    action = 'like';

  } else {

    $(this).addClass('fa-heart-o');
    $(this).removeClass('fa-heart');

    action = 'unlike';
  }

  $.ajax({
    url: '/api/agent/post/like',
    type: 'POST',
    dataType: 'JSON',
    data: {action: action, 'post_id': postId},
    success: function(response) {

      if(response.success) {
        
        toastr['success'](response.message, {
              closeButton: true,
              tapToDismiss: false,
              rtl: direction
            });

        if(action == 'like') {

          $('#like_count_' + postId).html(parseInt(likes + 1));
          btn.attr('data-likes', parseInt(likes + 1));

        } else {
          
          $('#like_count_' + postId).html(parseInt(likes - 1));
          btn.attr('data-likes', parseInt(likes - 1));

        }

        if(parseInt(likes - 1) == 0) {
          
          $('#post_likes_avatar_' + postId).addClass('hide-likes');

        } else {

          $('#post_likes_avatar_' + postId).removeClass('hide-likes');
        }

      } else {

        toastr['error'](response.message, {
              closeButton: true,
              tapToDismiss: false,
              rtl: direction
            });

      }

    }, function(error, status) {

      toastr['error'](error.responseJSON.message, {
            closeButton: true,
            tapToDismiss: false,
            rtl: direction
          });
    }

  });

});

$('.post-share').click(function() {
  var postId = $(this).attr('data-id');
  $('.share-post-icons').hide();
  $('#post_share_icon_' + postId).slideToggle();
});

$('.share-to').click(function() {
  var postId = $(this).attr('data-id');
  var sharePlatform = $(this).attr('data-type');
  var siteURL = $('#siteURL').val();
  if(sharePlatform == 'wp') {
   
    window.open('whatsapp://send?text=' + $('#post_content_' + postId).html());  

  } else if(sharePlatform == 'fb') {
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + siteURL + '/post/' + postId + '&quote=Sharing Post on facebook.');      
  } else if(sharePlatform == 'tw') {
    //http://twitter.com/share?text=My%20text&url=https://tripheist.com/&hashtags=triphesit,travel
  }
});

var withdrawBtn = $('#withdrawPayment');
if (withdrawBtn.length) {
    withdrawBtn.on('click', function () {
      Swal.fire({
        title: 'Withdraw Payment?',
        text: "You are making withdraw request for $" + $('#totalWithdraw').html(),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Confirmed!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.value) {
        
          $.ajax({
            url: '/api/agent/withdraw',
            type: 'POST',
            data: {flight: selectedIdsF, others: selectedIdsO, amount: $('#totalWithdraw').html()},
            success: function(response) {
              // toastr['success'](response.message, {
              //   closeButton: true,
              //   tapToDismiss: false,
              //   rtl: direction
              // });
              Swal.fire({
                icon: 'success',
                title: 'Request Sent!',
                text: response.message,
                customClass: {
                  confirmButton: 'btn btn-success'
                }
              });

              $('#withdrawPayment').attr('disabled', true);
              $('#totalWithdraw').html('0');
              setTimeout(function(){
                window.location.reload();
              },1500);
            },
            error: function(error, status) {
      
              toastr['error'](error.responseJSON.message, {
                    closeButton: true,
                    tapToDismiss: false,
                    rtl: direction
                  });
            }
          })
        }
      });
    });
}

function selectAllPayments(type, isChecked) {

  if(type === 'hotel') {
    $('.hb-select').each(function() {
      if(isChecked) {
        if($(this).prop('checked')) {
          
        } else {
          totalWithdrawAmt = totalWithdrawAmt + $(this).data('amount');
          $(this).prop('checked', true);
          selectedIdsO.push($(this).attr('id'));
        }
      } else {
        if(!$(this).prop('checked')) {
        } else {
          totalWithdrawAmt = totalWithdrawAmt - $(this).data('amount');
          $(this).prop('checked', false);
          selectedIdsO = removeFromArr(selectedIdsO, $(this).attr('id'));
        }
      }
    });
  }

  if(type === 'flight') {
    $('.fb-select').each(function() {
      if(isChecked) {
        if($(this).prop('checked')) {
          
        } else {
          totalWithdrawAmt = totalWithdrawAmt + $(this).data('amount');
          $(this).prop('checked', true);
          selectedIdsF.push($(this).attr('id'));
        }
      } else {
        if(!$(this).prop('checked')) {
        } else {
          totalWithdrawAmt = totalWithdrawAmt - $(this).data('amount');
          $(this).prop('checked', false);
          selectedIdsF = removeFromArr(selectedIdsF, $(this).attr('id'));
        }
      }
    });
  }

  if(type === 'act') {
    $('.ab-select').each(function() {
      if(isChecked) {
        if($(this).prop('checked')) {
          
        } else {
          totalWithdrawAmt = totalWithdrawAmt + $(this).data('amount');
          $(this).prop('checked', true);
          selectedIdsO.push($(this).attr('id'));
        }
      } else {
        if(!$(this).prop('checked')) {
        } else {
          totalWithdrawAmt = totalWithdrawAmt - $(this).data('amount');
          $(this).prop('checked', false);
          selectedIdsO = removeFromArr(selectedIdsO, $(this).attr('id'));
        }
      }
    });
  }

  if(type === 'cab') {
    $('.cb-select').each(function() {
      if(isChecked) {
        if($(this).prop('checked')) {
          
        } else {
          totalWithdrawAmt = totalWithdrawAmt + $(this).data('amount');
          $(this).prop('checked', true);
          selectedIdsO.push($(this).attr('id'));
        }
      } else {
        if(!$(this).prop('checked')) {
        } else {
          totalWithdrawAmt = totalWithdrawAmt - $(this).data('amount');
          $(this).prop('checked', false);
          selectedIdsO = removeFromArr(selectedIdsO, $(this).attr('id'));
        }
      }
    });
  }

  // totalEarnedAmt = totalWithdrawAmt;
  if((totalWithdrawAmt - 500) < 0) {
    $('#withdrawPayment').attr('disabled', true);
  } else {
    $('#withdrawPayment').removeAttr('disabled');
  }

  
  var totalWithdrawAmtShow = number_format((totalWithdrawAmt - 500) < 0 ? totalWithdrawAmt : (totalWithdrawAmt - 500), 2, '.', ',');
  var totalEarnedAmtShow = number_format((totalWithdrawAmt - 500) < 0 ? totalWithdrawAmt : (totalWithdrawAmt - 500), 2, '.', ',');
  $('#totalWithdraw').html(totalWithdrawAmtShow);
  $('#totalWithdraw').attr('data', (totalWithdrawAmt - 500) < 0 ? totalWithdrawAmt : (totalWithdrawAmt - 500));
}

function selectPayment(type, el) {

  if(el.prop('checked')) {
    totalWithdrawAmt = totalWithdrawAmt + (el.data('amount') + el.data('markup'));
    if(type == 'flight') {
      selectedIdsF.push(el.attr('id'));
    } else {
      selectedIdsO.push(el.attr('id'));
    }
  } else {
    totalWithdrawAmt = totalWithdrawAmt - (el.data('amount') + el.data('markup'));
    if(type == 'flight') {
      selectedIdsF = removeFromArr(selectedIdsF, el.attr('id'));
    } else {
      selectedIdsO = removeFromArr(selectedIdsO, el.attr('id'));
    }
  }

  if((totalWithdrawAmt - 500) < 0) {
    $('#withdrawPayment').attr('disabled', true);
  } else {
    $('#withdrawPayment').removeAttr('disabled');
  }

  
  var totalWithdrawAmtShow = number_format((totalWithdrawAmt - 500) < 0 ? totalWithdrawAmt : (totalWithdrawAmt - 500), 2, '.', ',');
  var totalEarnedAmtShow = number_format((totalWithdrawAmt - 500) < 0 ? totalWithdrawAmt : (totalWithdrawAmt - 500), 2, '.', ',');
  $('#totalWithdraw').html(totalWithdrawAmtShow);
  $('#totalWithdraw').attr('data', (totalWithdrawAmt - 500) < 0 ? totalWithdrawAmt : (totalWithdrawAmt - 500));
}

function searcVideos() {
  var input, filter, ul, title, a, i, txtValue;
    input = document.getElementById("shop-search");
    filter = input.value.toUpperCase();

    $('.ecommerce-card').each(function() {
      if ($(this).data('title').toUpperCase().indexOf(filter) > -1) {
          $(this).show();
      } else {
          $(this).hide();
      }
    });

    if(filter == '') {
      $('.ecommerce-card').show();
    }
}

function number_format(number, decimals, dec_point, thousands_point) {

    if (number == null || !isFinite(number)) {
        throw new TypeError("number is not valid");
    }

    if (!decimals) {
        var len = number.toString().split('.').length;
        decimals = len > 1 ? len : 0;
    }

    if (!dec_point) {
        dec_point = '.';
    }

    if (!thousands_point) {
        thousands_point = ',';
    }

    number = parseFloat(number).toFixed(decimals);

    number = number.replace(".", dec_point);

    var splitNum = number.split(dec_point);
    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
    number = splitNum.join(dec_point);

    return number;
}

function removeFromArr(arr, val) {
  arr.map(function(v, i) {
    if(v === val) {
      arr.splice(i, 1);
    }
  });
  return arr;
}

$('#select-files').click(function() {
  $('#fileinput').trigger('click'); 
});

var coverPreview = $('.card-img-top'),
    uploadCoverBtn = $('#fileinput');

// Update user photo on click of button
if (uploadCoverBtn) {
  uploadCoverBtn.on('change', function (e) {
    var reader = new FileReader(),
      files = e.target.files;
    reader.onload = function () {
      if (coverPreview) {
        coverPreview.attr('src', reader.result);
        $('#selectedCoverImage').val(reader.result);
        uploadCoverImage();
      }
    };
    reader.readAsDataURL(files[0]);
  });
}

function uploadCoverImage() {
  $.ajax({
    url: '/api/agent/upload/cover',
    type: 'POST',
    dataType: 'JSON',
    data: {source: $('#selectedCoverImage').val()},
    success: function(response) {
      if(response.success) {
        toastr['success'](response.message, {
              closeButton: true,
              tapToDismiss: false,
              rtl: direction
            });
      } else {
        toastr['error'](response.message, {
            closeButton: true,
            tapToDismiss: false,
            rtl: direction
          });
      }
    },
    error: function(error, status) {
      
      toastr['error'](error.responseJSON.message, {
            closeButton: true,
            tapToDismiss: false,
            rtl: direction
          });
    }
  })
}

$('#pay_receipt_select').on('change', function (e) {
    var reader = new FileReader(),
      files = e.target.files;
    reader.onload = function () {
      $('#pay_receipt').val(reader.result);
    };
    reader.readAsDataURL(files[0]);
});