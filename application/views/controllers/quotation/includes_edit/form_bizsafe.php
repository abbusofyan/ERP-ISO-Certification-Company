<input type="hidden" name="type" class="type">
<div class="form-bizsafe">
	<?php include 'includes_bizsafe/quote_detail_form.php'; ?>
	<?php include 'includes_bizsafe/accreditation_form.php'; ?>
	<?php include 'includes_bizsafe/fee_form.php'; ?>
</div>

<script>

$(document).on('keyup', '#bizsafe_referred_by', function(e) {
	var referrer = $(this).val()
	$.ajax({
		beforeSend  : function(request) {
			request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
		},
		url: <?php echo json_encode(site_url("api/referrer/get_like")); ?>,
		type: "POST",
		data: {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			name:referrer,
		}
	}).done(function(res) {
		$("#bizsafe_referred_by").autocomplete({
			source: res
		});
	})
})

// $(document).on('change', '#select-group-of-companies', function(e) {
// 	var value = $(this).select2('val')
// 	$('#group-of-companies-bizsafe').val(value)
// })

$(document).on('click', '.btn-add-accreditation-bizsafe', function(e) {
	var el = $('.accreditation-bizsafe-template').children().clone()
			.find('.certification-scheme-bizsafe').select2().prop('required', true).end()
			.find('.accreditation-bizsafe').select2().prop('required', true).end()
			.appendTo('.new-accreditation-bizsafe-section:last');
})


$(document).on('click', '.btn-delete-accreditation', function(e) {
	var $theRow = $(e.target).closest("div.row");
	if (confirm("Are you sure your want to delete this certification scheme ?")) {
		$theRow.remove();
	}
})

$(document).on('change', '.accreditation-bizsafe', function(e) {
	var $theRow = $(e.target).closest("div.row");
	var certification_scheme_field = $theRow.find('.certification-scheme-bizsafe')
	var certification_scheme = certification_scheme_field.val()
	var accreditation_field = $(this)
	var accreditation = accreditation_field.val()
	var client_history_id = $('.client_history_id').val()

	if (this.value) {
		if (!client_history_id) {
			$(accreditation_field).select2('val', '')
			return Swal.fire({
				icon: 'error',
				title: 'Error!',
				text: 'Please create or select a client first!',
				customClass: {
					confirmButton: 'btn btn-danger'
				}
			})
		}

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/quotation/client_with_accreditation_exists")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_history_id:client_history_id,
				certification_scheme: certification_scheme,
				accreditation: accreditation
			}
		}).done(function(quotation) {
			var error_message = JSON.parse(quotation).message;
			if (error_message) {
				$(accreditation_field).select2('val', '')

				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: error_message,
					customClass: {
						confirmButton: 'btn btn-danger'
					}
				})
			}
		})
	}
})


var selected_bizsafe_scheme = '';
$(document).on('change', '.certification-scheme-bizsafe', function() {
	var certification_scheme = $(this).val()
	var selected_certification_scheme_field = $(this)

	var accreditation_field = $('.accreditation-bizsafe')
	var accreditation = accreditation_field.val()

	var client_history_id = $('.client_history_id').val()

	if (this.value) {
		if (!client_history_id) {
			$(selected_certification_scheme_field).select2('val', '')
			return Swal.fire({
				icon: 'error',
				title: 'Error!',
				text: 'Please create or select a client first!',
				customClass: {
					confirmButton: 'btn btn-danger'
				}
			})
		}

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/quotation/client_with_certification_scheme_exists")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_history_id:client_history_id,
				certification_scheme: certification_scheme,
				accreditation: accreditation
			}
		}).done(function(quotation) {
			var error_message = JSON.parse(quotation).message;
			if (error_message) {
				$(selected_certification_scheme_field).select2('val', '')

				return Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: error_message,
					customClass: {
						confirmButton: 'btn btn-danger'
					}
				})
			}
		})

		var selected_certification_scheme = [];
		var selected_bizsafe_scheme = [];

		$('.certification-scheme-bizsafe').each(function() {
			if (selected_certification_scheme.includes(parseInt(this.value))) {
				$(this).select2('val', '')
				return Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: "Cannot select the same certification scheme",
					customClass: {
						confirmButton: 'btn btn-danger'
					}
				})
			} else {
				selected_certification_scheme.push(parseInt(this.value))
				selected_certification_scheme.join('').split('')
			}

			var certification_scheme_label = $(this).find('option:selected').text();
			if (certification_scheme_label == 'Bizsafe Level 3' || certification_scheme_label == 'Bizsafe Level Star') {
				if(selected_bizsafe_scheme.includes('Bizsafe Level 3') || selected_bizsafe_scheme.includes('Bizsafe Level Star')) {
					$(this).select2('val', '')
					return Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: "Cannot select more than 1 bizsafe certification scheme",
						customClass: {
							confirmButton: 'btn btn-danger'
						}
					})
				}  else {
					selected_bizsafe_scheme.push(certification_scheme_label)
					selected_bizsafe_scheme.join('').split('')
				}
			}
		})
	}
})

$(".bizsafe-assesment_fee_attachments").on("change", function() {
  var files = Array.from(this.files)
  var fileName = files.map(f =>{return f.name}).join(", ")
	$('.bizsafe-selected-file').text(fileName)
});

</script>
