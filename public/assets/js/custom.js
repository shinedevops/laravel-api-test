jQuery( document ).ready(function() {
	/* Admin User DataTable Js
    jQuery('#dataTableusers').DataTable({
	"columnDefs": [{orderable: false, targets: [5]},],
	}); 
	jQuery('#userlisting-datatable').DataTable({
	"columnDefs": [{orderable: false, targets: [5]},],
	});
	jQuery('#artistlisting-datatable').DataTable();
 */
	jQuery('#radioBtn a').on('click', function(){
    var sel = jQuery(this).data('title');
    var tog = jQuery(this).data('toggle');
    jQuery('#'+tog).prop('value', sel);
   
    jQuery('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
    jQuery('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');
	});
   	/* Profile Post Request Ajax Code*/
	jQuery("form[id='modalProfileSubmit']").submit(function(e) {
		e.preventDefault();
	}).validate({
		ignore: '',
		rules: {
			adminemail: {
				required: true,
				email: true
			},
			name: {
				required: true,
			}
		},
		// Specify validation error messages
		messages: {
			adminemail: {
				required: 'email address is required',
				required: 'provide an valid email address',
			},
			name: {
				required: 'name field is required',
			}
		},
		submitHandler: function(form) {
			jQuery("#successMsgP").html('');
			jQuery("#errorsDeprtP").html('');
			var btnText = jQuery("#savedBtnP").html();
			jQuery("#savedBtnP").html(btnText + '<i class="fa fa-spinner fa-spin"></i>');
			jQuery("#savedBtnP").attr("disabled", true);
			var formData = jQuery(form);
			var urls = formData.prop('action');
			jQuery.ajax({
				type: "POST",
				url: urls,
				data: formData.serialize(),
				dataType: 'json',
				success: function(data) {
				if (data.success == true) {
					jQuery("#successMsgP").html('<p class="inputsuccess">' + data.msg + '</p>');
					jQuery("#successMsgP").removeClass("hidden");
					jQuery("#errorsDeprtP").addClass("hidden");
					setTimeout(function() {
						location.reload(true);
					}, 1000);

				} else if(data.success == false){
					jQuery("#errorsDeprtP").html('<p class="inputerror">' + data.msg + '</p>');
					jQuery("#errorsDeprtP").removeClass("hidden");
					jQuery("#successMsgP").addClass("hidden");
					jQuery("#savedBtnP").html('Update');
					jQuery("#savedBtnP").attr("disabled", false);
				}
				},
				error: function(data) {
					var errors = data.responseJSON;
					var erro = '';
					jQuery.each(errors['errors'], function(n, v) {
						erro += '<p class="inputerror">' + v + '</p>';
					});
					jQuery("#errorsDeprtP").html(erro);
					jQuery("#errorsDeprtP").removeClass("hidden");
					jQuery("#successMsgP").addClass("hidden");
					jQuery("#savedBtnP").html('Update');
					jQuery("#savedBtnP").attr("disabled", false);
				},
			});
		}
	});

	jQuery("form[id='acceptRequestSubmit']").submit(function(e) {
		e.preventDefault();
	}).validate({
		ignore: '',
		rules: {
			expiry: {
				required: true,
			}
		},
		// Specify validation error messages
		messages: {
			expiry: {
				required: 'expiry field is required',
			}
		},
		submitHandler: function(form) {
			jQuery("#successMsgexpiry").html('');
			jQuery("#errorsDeprtexpiry").html('');
			var btnText = jQuery("#savedBtnexpiry").html();
			jQuery("#savedBtnexpiry").html(btnText + '<i class="fa fa-spinner fa-spin"></i>');
			jQuery("#savedBtnexpiry").attr("disabled", true);
			var formData = jQuery(form);
			var urls = formData.prop('action');
			jQuery.ajax({
				type: "POST",
				url: urls,
				data: formData.serialize(),
				dataType: 'json',
				success: function(data) {
				if (data.success == true) {
					jQuery("#successMsgexpiry").html('<p class="inputsuccess">' + data.message + '</p>');
					jQuery("#successMsgexpiry").removeClass("hidden");
					jQuery("#errorsDeprtexpiry").addClass("hidden");
					setTimeout(function() {
						location.reload(true);
					}, 1000);

				} else if(data.success == false){
					jQuery("#errorsDeprtexpiry").html('<p class="inputerror">' + data.message + '</p>');
					jQuery("#errorsDeprtexpiry").removeClass("hidden");
					jQuery("#successMsgexpiry").addClass("hidden");
					jQuery("#savedBtnexpiry").html('Update');
					jQuery("#savedBtnexpiry").attr("disabled", false);
				}
				},
				error: function(data) {
					var errors = data.responseJSON;
					var erro = '';
					jQuery.each(errors['errors'], function(n, v) {
						erro += '<p class="inputerror">' + v + '</p>';
					});
					jQuery("#errorsDeprtexpiry").html(erro);
					jQuery("#errorsDeprtexpiry").removeClass("hidden");
					jQuery("#successMsgexpiry").addClass("hidden");
					jQuery("#savedBtnexpiry").html('Update');
					jQuery("#savedBtnexpiry").attr("disabled", false);
				},
			});
		}
	});
	//Common Delete confirmation
	jQuery('.delete-confirm').on('click', function (event) {
		event.preventDefault();
		const url = jQuery(this).attr('href');
		swal({
			title: 'Are you sure?',
			text: 'This record and it`s details will be permanantly deleted!',
			icon: 'warning',
			buttons: ["Cancel", "Yes!"],
		}).then(function(value) {
			if (value) {
				window.location.href = url;
			}
		});
	});
	
	jQuery('.accept-request').on('click', function (event) {
		event.preventDefault();
		const rowId = jQuery(this).attr('data-id');
		jQuery('#rowId').val(rowId);
		$('#acceptRequest').modal("show");
	});
	

	// Password Change Code

	jQuery("form[id='modalchangepassSubmit']").validate({
		ignore: '',
		rules: {
			oldpassword: {
				required: true,
			},
			newpassword: {
				required: true,
			}
		},
		// Specify validation error messages
		messages: {
			oldpassword: {
				required: 'old password is required',
			},
			newpassword: {
				required: 'new password field is required',
			}
		},
		submitHandler: function(form) {
			jQuery("#successMsgPass").html('');
			jQuery("#errorsDeprtPass").html('');
			var btnText = jQuery("#savedBtnPass").html();
			jQuery("#savedBtnPass").html(btnText + '<i class="fa fa-spinner fa-spin"></i>');
			jQuery("#savedBtnPass").attr("disabled", true);
			var formData = jQuery(form);
			var urls = formData.prop('action');
			jQuery.ajax({
				type: "POST",
				url: urls,
				data: formData.serialize(),
				dataType: 'json',
				success: function(data) {
					if (data.success == true) {
						jQuery("#successMsgPass").html('<p class="inputsuccess">' + data.msg + '</p>');
						jQuery("#successMsgPass").removeClass("hidden");
						jQuery("#errorsDeprtPass").addClass("hidden");
						setTimeout(function() {
							location.reload(true);
						}, 1000);
			
					} else if(data.success == false){
						jQuery("#errorsDeprtPass").html('<p class="inputerror">' + data.msg + '</p>');
						jQuery("#errorsDeprtPass").removeClass("hidden");
						jQuery("#successMsgPass").addClass("hidden");
						jQuery("#savedBtnPass").html('Update');
						jQuery("#savedBtnPass").attr("disabled", false);
					}
				},
				error: function(data) {
					var errors = data.responseJSON;
					var erro = '';
					jQuery.each(errors['errors'], function(n, v) {
						erro += '<p class="inputerror">' + v + '</p>';
					});
					jQuery("#errorsDeprtPass").html(erro);
					jQuery("#errorsDeprtPass").removeClass("hidden");
					jQuery("#successMsgPass").addClass("hidden");
					jQuery("#savedBtnPass").html('Update');
					jQuery("#savedBtnPass").attr("disabled", false);
				},
			});
		}
	});
});
