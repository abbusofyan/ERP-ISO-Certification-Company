<input type="hidden" name="type" class="type">
<div class="form-iso">
	<?php include 'includes_iso/quote_detail_form.php'; ?>
	<?php include 'includes_iso/accreditation_form.php'; ?>
	<?php include 'includes_iso/address_form.php'; ?>
	<?php include 'includes_iso/assesment_form.php'; ?>
</div>

<script>
$('.transfer-related-field').hide()

// $('.address-form-template').hide()
// $('.selected-address-detail-template').hide()

// $('.selected-address-section').hide()

// add new address

var data = [{
	id:'',
	text:''
}];
$(document).ready(function() {
	var countries = JSON.parse('<?= json_encode($countries) ?>')
	for (const key in countries) {
		data.push({
			id: countries[key].name,
			text: countries[key].name,
			title: countries[key].name,
			html: name,
		})
	}
	if ('<?= $quotation->invoice_to ?>' == 'Consultant') {
		$('.client_pay_3_years_field').hide()
	} else {
		$('.consultant_pay_3_years_field').hide()
	}
});

$(document).on('click', '.btn-add-address', function(e) {
	var client_id = $('.client_id').val()
	if(!client_id) {
		return Swal.fire({
			icon: 'error',
			title: 'Error!',
			text: 'Please create or select a client!',
			customClass: {
				confirmButton: 'btn btn-danger'
			}
		})
	}

	$('.address-section').show()
	var template = `
			<div class="template">
				<h4 class="site-no"></h4>
				<div class="row pb-1">
					<div class="col-12">
						<div class="alert alert-primary alert-dismissible error-address-validation fade show" role="alert"></div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Client Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control address_name" id="selected-address-name">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>No of Employee</label>
							<input type="number" class="form-control address_total_employee" id="selected-address-no-of-employee">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Address</label>
							<input type="text" class="form-control address_address" id="selected-address-address">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Address 2</label>
							<input type="text" class="form-control address_address_2" id="selected-address-address-2">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Country</label>
							<select class="select2 address-country address_country" id="selected-address-country"></select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Postal Code</label>
							<input type="number" class="form-control address_postal_code" id="selected-address-postal-code">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Phone</label>
							<input type="number" class="form-control address_phone" id="selected-address-phone">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Fax</label>
							<input type="number" class="form-control address_fax" id="selected-address-fax">
						</div>
					</div>
					<input type="hidden" name="iso-other_address_id[]" class="other_address_history_id address_history_id">
					<div class="col-md-12">
						<button type="button" class="btn btn-primary float-right btn-create-address">
							Create
						</button>
						<button type="button" class="btn btn-primary float-right btn-delete-address mr-1" data-type="add">
							Remove
						</button>
					</div>
				</div>
				<hr>
			</div>
		`;
		$('.added-address-section:last').append(template)
		$(".address-country").select2({
			data: data,
			templateSelection: function(data) {
				return data.id;
			}
		})
	countTotalSite()
})

$(document).on("click", ".btn-create-address", function(e) {
	var $theRow = $(e.target).closest("div.row");
	var row_address = $theRow.find('.address_row').data('id')

	var client_id = $('.client_id').val()

	if (!client_id) {
		return Swal.fire({
			icon: 'error',
			title: 'Error!',
			text: 'Please create a new client first!',
			customClass: {
				confirmButton: 'btn btn-danger'
			}
		})
	}
	$.ajax({
		beforeSend  : function(request) {
			request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
		},
		url: <?php echo json_encode(site_url("api/address/create")); ?>,
		type: "POST",
		data: {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			client_id: client_id,
			name: $theRow.find('.address_name').val(),
			phone: $theRow.find('.address_phone').val(),
			fax: $theRow.find('.address_fax').val(),
			country: $theRow.find('.address_country').val(),
			address: $theRow.find('.address_address').val(),
			address_2: $theRow.find('.address_address_2').val(),
			total_employee: $theRow.find('.address_total_employee').val(),
			postal_code: $theRow.find('.address_postal_code').val(),

		}
	}).done(function(res) {
		if (res.status) {
			$theRow.find('.error-address-validation').empty()
			$theRow.find('.alert-validation').hide()
			Swal.fire({
				icon: 'success',
				title: 'Created!',
				text: 'Address has been created.',
				customClass: {
					confirmButton: 'btn btn-success'
				}
			})
			$theRow.find('.btn-create-address').removeClass('btn-create-address').addClass('btn-update-address').text('Update')
			$theRow.find('.address_history_id').val(res.data.address_history_id)
			$theRow.removeClass('border-danger')
			countTotalSite()
			// $theRow.find('.create_address_field').attr('readonly', true)
			// $theRow.find('.address_country').select2({
			// 	disabled: true
			// });
		} else {
			var html = res.data;
			$theRow.find('.error-address-validation').empty()
			$theRow.find('.error-address-validation').append(
				'<div class="alert-body alert-validation">'+html+'</div>'
			)
		}
	})
});


$(document).on("click", ".btn-update-address", function(e) {
	var $theRow = $(e.target).closest("div.row");
	var address_history_id = $theRow.find('.address_history_id').val();

	var client_id = $('.client_id').val()

	if (!client_id) {
		return Swal.fire({
			icon: 'error',
			title: 'Error!',
			text: 'Please create a new client first!',
			customClass: {
				confirmButton: 'btn btn-danger'
			}
		})
	}
	$.ajax({
		beforeSend  : function(request) {
			request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
		},
		url: <?php echo json_encode(site_url("api/address/update")); ?>,
		type: "POST",
		data: {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			client_id: client_id,
			address_history_id: address_history_id,
			name: $theRow.find('.address_name').val(),
			phone: $theRow.find('.address_phone').val(),
			fax: $theRow.find('.address_fax').val(),
			country: $theRow.find('.address_country').val(),
			address: $theRow.find('.address_address').val(),
			address_2: $theRow.find('.address_address_2').val(),
			total_employee: $theRow.find('.address_total_employee').val(),
			postal_code: $theRow.find('.address_postal_code').val(),
		}
	}).done(function(res) {
		if (res.status) {
			$theRow.find('.error-address-validation').empty()
			$theRow.find('.alert-validation').hide()
			Swal.fire({
				icon: 'success',
				title: 'Created!',
				text: 'Site Address Updated.',
				customClass: {
					confirmButton: 'btn btn-success'
				}
			})
			$theRow.find('.address_history_id').val(res.data.address_history_id)
			// $theRow.find('.create_address_field').attr('readonly', true)
			// $theRow.find('.address_country').select2({
			// 	disabled: true
			// });
		} else {
			var html = res.data;
			$theRow.find('.error-address-validation').empty()
			$theRow.find('.error-address-validation').append(
				'<div class="alert-body alert-validation">'+html+'</div>'
			)
		}
	})
});

// delete address field
$(document).on("click", ".btn-delete-address", function(e) {
	var $theRow = $(e.target).closest("div.template");
	if (confirm("This is not reversible. Are you sure?")) {
		$theRow.remove();
	}
	countTotalSite()
});

$(document).on('change', '#select-address', function(e) {
	var ids = $(this).select2("val")
	var props = $(this).select2('data')
	console.log('props', props);
	$('.selected-address-section .template').remove()
	props.forEach((item, i) => {
		var value = item.value
		var template = `
			<div class="template" data-id="`+value.id+`">
				<h4 class="site-no"></h4>
				<div class="row pb-1">
					<div class="col-12">
						<div class="alert alert-primary alert-dismissible error-address-validation fade show" role="alert"></div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Client Name</label>
							<input type="text" class="form-control address_name" id="selected-address-name" value="`+value.address_name+`" >
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>No of Employee</label>
							<input type="number" class="form-control address_total_employee" id="selected-address-no-of-employee" value="`+value.total_employee+`" >
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Address 1</label>
							<input type="text" class="form-control address_address" id="selected-address-address" value="`+value.address+`" >
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Address 2</label>
							<input type="text" class="form-control address_address_2" id="selected-address-address-2" value="`+value.address_2+`" >
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Country</label>
							<select class="select2 address-country address_country" id="selected-address-country" value="`+value.country+`"></select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Postal Code</label>
							<input type="number" class="form-control address_postal_code" id="selected-address-postal-code" value="`+value.postal_code+`" >
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Phone</label>
							<input type="number" class="form-control address_phone" id="selected-address-phone" value="`+value.phone+`" >
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Fax</label>
							<input type="number" class="form-control address_fax" id="selected-address-fax" value="`+value.fax+`" >
						</div>
					</div>
					<input type="hidden" name="iso-other_address_id[]" class="other_address_history_id address_history_id" value="`+value.id+`">
					<div class="col-md-12">
						<button type="button" class="btn btn-primary float-right btn-update-address">
							Update
						</button>
					</div>
				</div>
				<hr>
			</div>
		`;

		$('.selected-address-section').append(template)
		$('.selected-address-section').find('.address-country:last').select2();
		$('.selected-address-section').find('.address-country:last').select2({
			data: data,
		})
		$('.selected-address-section').find('.address-country:last').select2("val", value.country);
	});
	countTotalSite()
})

function countTotalSite() {
	var total_address = '<?= $quotation->num_of_sites ?>';
	var other_address = 0;

	$(".other_address_history_id").each(function(e) {
		var address_history_id = $(this).val()
		if(address_history_id) {
			$(this).parent().closest('.template').find('.site-no').text('Site ' + (total_address + 1))
			total_address++
		}
		other_address++;
	});

	$('#number-of-sites').val(total_address);
	if (other_address == 0) {
		$('.address-section').hide()
	}
}

$(document).on('keyup', '#iso_referred_by', function(e) {
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
		$("#iso_referred_by").autocomplete({
			source: res
		});
	})
})

$(document).on('keyup', '#application-form', function(e) {
	var key = $(this).val()
	$.ajax({
		beforeSend  : function(request) {
			request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
		},
		url: <?php echo json_encode(site_url("api/application_form/get_like")); ?>,
		type: "POST",
		data: {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			key:key,
		}
	}).done(function(res) {
		$("#application-form").autocomplete({
			source: res
		});
	})
})

$(document).on('change', '#select-group-of-companies', function(e) {
	var value = $(this).select2('val')
	$('#group-of-companies').val(value)
})

onChangeCertificationCycle('<?= $quotation->certification_cycle->name ?>')

$(document).on("change", "#iso_certificate_cycle", function(e) {
	var cycle = $("#iso_certificate_cycle option:selected" ).text();
	onChangeCertificationCycle(cycle)
});

function onChangeCertificationCycle(cycle) {
	if (cycle == 'Transfer 1st Year Surveillance' || cycle == 'Transfer 2nd Year Surveillance') {
		$('.transfer-related-field').show()
	} else {
		$('.transfer-related-field').hide()
	}

	$('.assesment_fee').hide()
	if (cycle == 'New') {
		$('#iso-stage_audit').show();
		$('#iso-surveillance_year_1').show();
		$('#iso-surveillance_year_2').show();
		$('#iso-transportation').show();
	}

	if (cycle == 'Transfer 1st Year Surveillance') {
		$('#iso-surveillance_year_1').show()
		$('#iso-surveillance_year_2').show()
		$('#iso-transportation').show()

		$('#iso-stage_audit input[name="iso-stage_audit"]').val('');
	}

	if (cycle == 'Transfer 2nd Year Surveillance') {
		$('#iso-surveillance_year_2').show()
		$('#iso-transportation').show()

		$('#iso-stage_audit input[name="iso-stage_audit"]').val('');
		$('#iso-surveillance_year_1 input[name="iso-surveillance_year_1"]').val('');
	}

	if (cycle == 'Re-Audit' || cycle == 'Re-Audit New') {
		$('#iso-stage_audit').show();
		$('#iso-surveillance_year_1').show();
		$('#iso-surveillance_year_2').show();
		$('#iso-transportation').show();
	}

	if (cycle == 'New' || cycle == 'Re-Audit New') {
		$('#stage-audit-label').text('Stage 1 & Stage 2 Audit')
	}

	if(cycle == 'Re-Audit') {
		$('#stage-audit-label').text('Stage 2')
	}
}

$(document).on('click', '.btn-add-accreditation-iso', function(e) {
	var el = $('.accreditation-iso-template').children().clone()
			.find('.certification-scheme-iso').select2().prop('required', true).end()
			.find('.accreditation-iso').select2().prop('required', true).end()
			.appendTo('.new-accreditation-iso-section:last');
})


$(document).on('click', '.btn-delete-accreditation', function(e) {
	var $theRow = $(e.target).closest("div.row");
	if (confirm("Are you sure your want to delete this certification scheme ?")) {
		$theRow.remove();
	}
})

$(document).on('change', '.accreditation-iso', function(e) {
	var $theRow = $(e.target).closest("div.row");
	var certification_scheme_field = $theRow.find('.certification-scheme-iso')
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
$(document).on('change', '.certification-scheme-iso', function() {
	var certification_scheme = $(this).val()
	var selected_certification_scheme_field = $(this)

	var accreditation_field = $(this).closest('.row').find('.accreditation-iso');
	var accreditation = accreditation_field.val();

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

		var selected_certification_scheme = [];
		var selected_bizsafe_scheme = [];
		var alert = false;
		$('.certification-scheme-iso').each(function() {
			if (selected_certification_scheme.includes(parseInt(this.value))) {
				$(this).select2('val', '')
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: "Cannot select the same certification scheme",
					customClass: {
						confirmButton: 'btn btn-danger'
					}
				})
				alert = true
			} else {
				selected_certification_scheme.push(parseInt(this.value))
				selected_certification_scheme.join('').split('')
			}

			var certification_scheme_label = $(this).find('option:selected').text();
			if (certification_scheme_label == 'Bizsafe Level 3' || certification_scheme_label == 'Bizsafe Level Star') {
				if(selected_bizsafe_scheme.includes('Bizsafe Level 3') || selected_bizsafe_scheme.includes('Bizsafe Level Star')) {
					$(this).select2('val', '')
					Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: "Cannot select more than 1 bizsafe certification scheme",
						customClass: {
							confirmButton: 'btn btn-danger'
						}
					})
					alert = true
				}  else {
					selected_bizsafe_scheme.push(certification_scheme_label)
					selected_bizsafe_scheme.join('').split('')
				}
			}
		})

		if (!alert) {
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
		}
	}
})

$(".iso-assesment_fee_attachments").on("change", function() {
  var files = Array.from(this.files)
  var fileName = files.map(f =>{return f.name}).join(", ")
	$('.iso-selected-file').text(fileName)
});

$(".iso-certification_and_reports_file").on("change", function() {
  var files = Array.from(this.files)
  var fileName = files.map(f =>{return f.name}).join(", ")
	$('.iso-certification_and_reports_selected_file').text(fileName)
});

$('.select-invoice-to').change(function() {
	$('.consultant_pay_3_years_field').hide()
	$('.client_pay_3_years_field').hide()

	var $consultant = $('input:radio[name=iso-consultant_pay_3_years]');
    if($consultant.is(':checked') === true) {
        $consultant.filter('[value=No]').prop('checked', true);
    }

	var $client = $('input:radio[name=iso-client_pay_3_years]');
    if($client.is(':checked') === true) {
        $client.filter('[value=No]').prop('checked', true);
    }

	var invoice_to = $(this).val()
	if (invoice_to == 'Consultant') {
		$('.consultant_pay_3_years_field').show()
	}
	if (invoice_to == 'Client') {
		$('.client_pay_3_years_field').show()
	}
})

</script>
