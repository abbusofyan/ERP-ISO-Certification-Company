<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0"><?= $quotation->number ?></h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('quotation'); ?>">Listing</a>
                            </li>
                            <li class="breadcrumb-item">
								Edit
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-md-3 float-right">
			<button type="button" class="btn btn-primary float-right ml-1 btn-submit-quotation mr-1">Save</button>
			<button class="btn btn-outline-secondary waves-effect float-right btn-cancel">Cancel</button>
		</div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Edit Quotation</h4>
                    </div>
                    <div class="card-body">
						<br>
						<div class="row">
							<div class="col-6">
								<div class="form-group">
									<label for="quotation_type">Quotation Type</label>
									<input type="text" class="form-control" name="type" value="<?= $quotation->type ?>" id="select-quote-type" readonly>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									<label for="client_status">Client Status</label>
									<input type="text" class="form-control select-client-status" name="client_status" value="<?= $quotation->client->status ?>" readonly>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>

		<div class="form-create-quotation">
			<?php echo form_open_multipart(site_url('quotation/update/'.$quotation->id), ['autocomplete' => 'off', 'id' => 'form-quotation']); ?>
				<div class="form-client-and-contact">
					<?php include 'includes_edit/client_form.php' ?>
					<?php include 'includes_edit/contact_form.php' ?>
				</div>

				<?php if ($quotation->type == 'ISO'): ?>
					<?php include 'includes_edit/form_iso.php' ?>
				<?php endif; ?>

				<?php if ($quotation->type == 'Bizsafe'): ?>
					<?php include 'includes_edit/form_bizsafe.php' ?>
				<?php endif; ?>

				<?php if ($quotation->type == 'Training'): ?>
					<?php include 'includes_edit/form_training.php' ?>
				<?php endif; ?>
				<div class="latest_notes">
					<?php foreach ($notes as $note): ?>
						<div class="d-flex bd-highlight">
							<div class="pr-1 flex-shrink-1 bd-highlight">
								<img class="img-fluid" src="<?= assets_url('img/blank-profile.png') ?>" width="50" alt="">
							</div>
							<div class="w-100 bd-highlight">
								<b><?= $note->user->first_name . ' ' . $note->user->last_name ?></b><br>
								<p><?= $note->user->group->name ?></p>
							</div>
						</div>
						<span><?= nl2br($note->note) ?><br><br><?= human_timestamp($note->created_on) ?><hr></span>
					<?php endforeach; ?>
				</div>
			<?php echo form_close(); ?>
		</div>
    </div>

</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.flatpickr-basic').flatpickr({
		"maxDate": new Date(),
	});

  	$('#prev-cert-exp-date').flatpickr()

	CKEDITOR.replaceClass = 'ckeditor';
	$('.select2').select2();

	// $('.select-contact-section').hide()

	var expanded = false;
	function showCheckboxes() {
		var checkboxes = document.getElementById("checkboxes");
		if (!expanded) {
			checkboxes.style.display = "block";
			expanded = true;
		} else {
			checkboxes.style.display = "none";
			expanded = false;
		}
	}

	var quote_type = '<?= $quotation->type ?>';
	$('.type').val(quote_type)
	$('.quotation-form-type').addClass('hidden')
	$('.form-client-and-contact').addClass('hidden')

	var client_status = '<?= $quotation->client->status ?>';
	$('.quotation-form-type').addClass('hidden')
	if(client_status) {
		resetForm()
	}

	function resetForm() {
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/get_by_status")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				status: client_status,
			}
		}).done(function(res) {
			if (quote_type == 'ISO') {
				$('.form-iso').removeClass('hidden')
			}

			if (quote_type == 'Training') {
				$('.form-training').removeClass('hidden')
			}

			if (quote_type == 'Bizsafe') {
				$('.form-bizsafe').removeClass('hidden')
			}

			$('.form-client-and-contact').removeClass('hidden')

			$('.client-form').removeClass('hidden')

			$('.contact-form').removeClass('hidden')

			// $('.select-contact-section').hide()

			$('.contact-form-section').empty()

		});
	}

	$('.btn-update-contact').hide();

	$(document).on('click', '.btn-update-client', function() {
		var address_id = $('.address_id').val()
		var client_id = $('.client_id').val()

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/update")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: client_id,
				address_id: address_id,
				name: $('#client_name').val(),
				uen: $('#client_uen').val(),
				address: $('#client_address').val(),
				address_2: $('#client_address_2').val(),
				country: $('#client_country').val(),
				postal_code: $('#client_postal_code').val(),
				phone: $('#client_phone').val(),
				fax: $('#client_fax').val(),
				website: $('#client_website').val(),
				email: $('#client_email').val(),
				total_employee: $('#client_total_employee').val(),
			}
		}).done(function(res) {
			if (res.status) {
				$('.error-client-validation').empty()
				$('.alert-validation').hide()
				Swal.fire({
					icon: 'success',
					title: 'Updated!',
					text: 'Client updated.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$('.client_history_id').val(res.data.client_history_id)
				$('.address_history_id').val(res.data.address_history_id)
                initializeClientData()
			} else {
				var html = res.data;
				$('.error-client-validation').empty()
				$('.error-client-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		});
	})

  var client_history_id = '<?= $quotation->client_history_id ?>'
  if (client_history_id) {
    $.ajax({
		beforeSend  : function(request) {
			request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
		},
		url: <?php echo json_encode(site_url("api/client/get_detail")); ?>,
		type: "POST",
		data: {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			client_history_id: client_history_id,
		}
    }).done(function(res) {
		$('.error-client-validation').empty()
		$('.alert-validation').hide()

		$('.selected-client-detail').show()
		$('.btn-create-client').hide()
		$('.btn-update-client').show()

		var client = res.client_detail
		setSelectContactOptions(res.contacts)
		setSelectAddressOptions(res.addresses)

		$('.contact-form-section').empty()

		$('.contact-form-template .contact_salutation').val('')
		$('.contact-form-template .contact_status').val('Active')
		$('.contact-form-template .contact_name').val('')
		$('.contact-form-template .contact_email').val('')
		$('.contact-form-template .contact_position').val('')
		$('.contact-form-template .contact_department').val('')
		$('.contact-form-template .contact_phone').val('')
		$('.contact-form-template .contact_fax').val('')
		$('.contact-form-template .contact_mobile').val('')
		$('.contact_id').val('')
    });
	} else {
		$('.btn-create-client').show()
		$('.btn-update-client').hide()

		$('.client_field').val('')
		$('#client_country').select2('val', '')
		$('.client_history_id').val('')
		$('.client_id').val('')
		$('.address_id').val('')
		$('.address_history_id').val('')
	}

	var quotation_selected_contact_history = JSON.parse('<?= json_encode($quotation->contact) ?>');
	function setSelectContactOptions(contacts) {
		var data = [];
		if(contacts.length > 0) {
			$('.select-contact-section').show()
		} else {
			$('.select-contact-section').hide()
		}

		for (const key in contacts) {
			if (contacts[key].contact_id == quotation_selected_contact_history.contact_id) {
				data.push({
					id: quotation_selected_contact_history.id,
					parent_contact_id: quotation_selected_contact_history.contact_id,
					client_id: quotation_selected_contact_history.client_id,
					text: quotation_selected_contact_history.name,
					position: quotation_selected_contact_history.position,
					title: quotation_selected_contact_history.name,
					html: quotation_selected_contact_history.name,
				})
			} else {
				data.push({
					id: contacts[key].id,
					parent_contact_id: contacts[key].contact_id,
					client_id: contacts[key].client_id,
					text: contacts[key].name,
					position: contacts[key].position,
					title: contacts[key].name,
					html: contacts[key].name,
				})
			}
		}

	    var idToSelect = '<?= $quotation->contact_history_id ?>'; // Change this to the id of the item you want to select
	    var itemToSelect = data.find(function(item) {
			return item.id === idToSelect;
	    });

	    // Set the 'selected' property of the item to true
	    if (itemToSelect) {
			itemToSelect.selected = true;
	    }

		$('.select-contact').find('option').not(':first').remove();
		$(".select-contact").select2({
			data: data,
				escapeMarkup: function(markup) {
				return markup;
			},
			templateResult: function (d) {
				if (d.id) {
					return '<span>'+d.text+'</span><span class="pull-right subtext" style="float: right!important;">'+d.position+'</span>';
				}
				return '<span>'+d.text+'</span>';
			},
			templateSelection: function(data) {
				return data.text;
			},
		})

	    $('.select-contact').select2('val', idToSelect)
	}

	var quotation_selected_address_history = JSON.parse('<?= str_replace(["'", '\n'], "", json_encode($quotation->address)) ?>');
	var quotation_selected_additional_address = JSON.parse('<?= str_replace(array("'", '`'), '', json_encode($quotation_address)); ?>');
	var quotation_selected_additional_address_id = JSON.parse('<?= json_encode(array_column(array_column($quotation_address, 'address'), 'address_id')) ?>');
	var quotation_selected_additional_address_history_id = JSON.parse('<?= json_encode(array_column($quotation_address, 'address_history_id')) ?>');

	function setSelectAddressOptions(address) {
		var address_options = [];
		$('.address-section').show()
		for (const key in address) {
			if (address[key].address_id != quotation_selected_address_history.address_id) { //dont show main address in this select options
		        if (quotation_selected_additional_address_id.includes(address[key].address_id)) {
					var additional_address = quotation_selected_additional_address.find(item => item.address.address_id === address[key].address_id).address;
		        } else {
					var additional_address = address[key]
		        }
		        var address_2 = additional_address.address_2 ? additional_address.address_2 : '-'
		        address_options.push({
					id: additional_address.id,
					text: '<b>'+additional_address.name+'</b>',
					html: '<b>'+additional_address.name+'</b><br>'+
							additional_address.address+'<br>'+
							address_2+'<br>'+
							additional_address.postal_code+'<br>',
					title: additional_address.address,
					value: {
						id: additional_address.id,
						address_name: additional_address.name,
						address: additional_address.address,
						address_2: additional_address.address_2,
						phone: additional_address.phone,
						fax: additional_address.fax,
						country: additional_address.country,
						postal_code: additional_address.postal_code,
						total_employee: additional_address.total_employee,
					}
		        })
			}
		}

		$('#select-address').find('option').not(':first').remove();
		$("#select-address").select2({
			data: address_options,
				escapeMarkup: function(markup) {
				return markup;
			},
			templateResult: function(data) {
				return data.html;
			},
			templateSelection: function(data) {
				return data.text;
			}
		})
	    $('#select-address').select2('val', quotation_selected_additional_address_history_id)
	}

	$('.selected-contact-detail').hide()
	$(document).on('change', '.select-contact', function(e) {
		var data = $('.select-contact').select2("data")[0]
		var contact_history_id = data.id
		var clientId = data.client_id

		if (!contact_history_id) {
			$('.contact-form-template .contact_salutation').val('')
			$('.contact-form-template .contact_status').val('')
			$('.contact-form-template .contact_name').val('')
			$('.contact-form-template .contact_email').val('')
			$('.contact-form-template .contact_position').val('')
			$('.contact-form-template .contact_department').val('')
			$('.contact-form-template .contact_phone').val('')
			$('.contact-form-template .contact_fax').val('')
			$('.contact-form-template .contact_mobile').val('')
			$('.contact_id').val('')
			$('.contact_history_id').val('')

			$('.btn-create-contact').show()
			$('.btn-update-contact').hide()
			return;
		}

		$('.error-contact-validation').empty()

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/contact/get_detail")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				contact_history_id: contact_history_id,
			}
		}).done(function(res) {
			var contact = res.contact_detail
			if (contact) {
				$('.contact-form-template .contact_salutation').val(contact ? contact.salutation : '')
				$('.contact-form-template .contact_status').val(contact ? contact.status : '')
				$('.contact-form-template .contact_name').val(contact ? contact.name : '')
				$('.contact-form-template .contact_email').val(contact ? contact.email : '')
				$('.contact-form-template .contact_position').val(contact ? contact.position : '')
				$('.contact-form-template .contact_department').val(contact ? contact.department : '')
				$('.contact-form-template .contact_phone').val(contact ? contact.phone : '')
				$('.contact-form-template .contact_fax').val(contact ? contact.fax : '')
				$('.contact-form-template .contact_mobile').val(contact ? contact.mobile : '')
				$('.contact_id').val(contact ? contact.contact_id : '')
				$('.contact_history_id').val(contact ? contact.id : '')

				$('.btn-create-contact').hide()
				$('.btn-update-contact').show()
                initializeContactData();

			}
		});
	})

	var row_contact = 1;
	$(document).on('click', '.btn-add-contact', function(e) {
		if (client_status != 'New' && row_contact == 0) {
			$('.create-contact-form').removeClass('hidden')
			row_contact = $('.contact-child').length + 1
		} else {
			row_contact = $('.contact-child').length + 1
			var el = $('.contact-form-template').children().clone()
					.find('input').attr('readonly', false).end()
					.find('.contact_salutation').attr('readonly', false).end()
					.find('.contact_status').attr('readonly', false).end()
					.find('.btn-delete-contact').removeClass('hidden').end()
					.find('.btn-create-contact').removeClass('hidden').end()
					.find('.btn-create-contact').show().end()
					.find('.btn-update-contact').hide().end()
					.find('.contact_history_id').remove().end()
					.find('.error-contact-validation').empty().end()
					.find("input").val("").end().append('<hr>')
					.find(".contact_row").attr('data-id', row_contact).end()
					.append('<hr>')
					.appendTo('.contact-form-section:last');
		}
		$('.selected-contact-detail').hide()
		// $('.select-contact-form').find('select').select2('val', '')
	})

	$(document).on("click", ".btn-create-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		var row_contact = $theRow.find('.contact_row').data('id')
		var contact_id = $theRow.find('.contact_id').val()
		var primary = row_contact == 1 ? 1 : 0;
		var client_id = $('.client_id').val()

		if (!client_id) {
			Swal.fire({
				icon: 'error',
				title: 'Error!',
				text: 'Please create or select a client first!',
				customClass: {
					confirmButton: 'btn btn-danger'
				}
			})
			return;
		}
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/contact/create")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: client_id,
				salutation: $theRow.find('.contact_salutation').val(),
				name: $theRow.find('.contact_name').val(),
				status: $theRow.find('.contact_status').val(),
				position: $theRow.find('.contact_position').val(),
				department: $theRow.find('.contact_department').val(),
				email: $theRow.find('.contact_email').val(),
				mobile: $theRow.find('.contact_mobile').val(),
				phone: $theRow.find('.contact_phone').val(),
				fax: $theRow.find('.contact_fax').val(),
				primary: primary
			}
		}).done(function(res) {
			if (res.status) {
				$('.error-contact-validation').empty()
				Swal.fire({
					icon: 'success',
					title: 'Created!',
					text: 'Contact has been created.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$theRow.find('.contact_id').val(res.data.contact_id)
				$theRow.find('.contact_history_id').val(res.data.contact_history_id)
				$theRow.find('.btn-create-contact').addClass('hidden')
				$theRow.find('.btn-delete-contact').addClass('hidden')
				$theRow.find('.create_contact_field').attr('readonly', true)
			} else {
				var html = res.data;
				$theRow.find('.error-contact-validation').empty()
				$theRow.find('.error-contact-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		})
	});

	$(document).on("click", ".btn-update-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		var row_contact = $theRow.find('.contact_row').data('id')
		var primary = row_contact == 1 ? 1 : 0;
		var client_id = $('.client_id').val()

		if (!client_id) {
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
			url: <?php echo json_encode(site_url("api/contact/update")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: client_id,
				contact_id: $theRow.find('.contact_id').val(),
				salutation: $theRow.find('.contact_salutation').val(),
				name: $theRow.find('.contact_name').val(),
				status: $theRow.find('.contact_status').val(),
				position: $theRow.find('.contact_position').val(),
				department: $theRow.find('.contact_department').val(),
				email: $theRow.find('.contact_email').val(),
				mobile: $theRow.find('.contact_mobile').val(),
				phone: $theRow.find('.contact_phone').val(),
				fax: $theRow.find('.contact_fax').val(),
				primary: primary
			}
		}).done(function(res) {
			if (res.status) {
				$('.error-contact-validation').empty()
				Swal.fire({
					icon: 'success',
					title: 'Updated!',
					text: 'Contact has been updated.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$theRow.find('.contact_history_id').val(res.data.contact_history_id)
                initializeContactData()
			} else {
				var html = res.data;
				$theRow.find('.error-contact-validation').empty()
				$theRow.find('.error-contact-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		})
	});

	$(document).on("click", ".btn-delete-contact", function(e) {
    var $theRow = $(e.target).closest("div.row");
    if (confirm("This is not reversible. Are you sure?")) {
      if ($theRow.find('.contact_history_id').val()) {
        $theRow.find('input').val('')
        $theRow.find('.contact_salutation').val('')
        $theRow.find('.contact_status').val('Active')
        $theRow.find('.btn-update-contact').hide()
        $theRow.find('.btn-create-contact').show()
        $theRow.find('.contact_status').val('Active')
        $('.select-contact').select2('val', '')
      } else {
        $theRow.remove();
        row_contact--;
        if (row_contact == 1) {
          $('.add-contact-active-client').hide()
        }
      }
    }
	});

	$(document).on("click", ".btn-submit-quotation", function(e) {
		if (_client_form_edited) {
			return toastr.error('Please save the changes on client data before continue', 'Error validation form')
		}

        if (_contact_form_edited) {
			return toastr.error('Please save the changes on contact data before continue', 'Error validation form')
		}
		var error_client = $('.error-client-validation').text()
		var error_contact = $('.error-contact-validation').text()
		if (error_client || error_contact) {
			Swal.fire({
	            title: 'Failed',
	            text: "Please fix all the errors before saving",
	            icon: 'error',
	            showCancelButton: false,
	            confirmButtonText: 'Ok',
	        })
			return;
		}
		validateForm(quote_type)
	});

	$(document).on('click', '.btn-cancel', function() {
        Swal.fire({
            title: 'Are you sure ?',
            text: "Are you sure you want to return to quotation home page ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
				window.location.href = '<?= site_url('quotation') ?>';
            }
        });
    });

	function validateForm(quote_type) {
		var errors = [];

		if (!quote_type && !client_status) {
			errors.push('- Select quotation type and client status <br>')
		} else if (!quote_type && client_status) {
			errors.push('- Select quotation type <br>')
		} else if (quote_type && !client_status) {
			errors.push('- Select client status <br>')
		} else {
			var client_id = $('.client_history_id').val()
			if (!client_id) {
				errors.push('- Create or select a client<br>')
			}

			var created_contact_id = $('.contact_id').val()
			var selected_contact_id = $('#selected-contact-id').val()
			if (!created_contact_id && !selected_contact_id) {
				errors.push('- Create or select a primary contact<br>')
			}

			if (quote_type == 'ISO') {
				var input = $('.form-iso :input').filter(function() { return this.value === ""; });
			} else if (quote_type == 'Bizsafe') {
				var input = $('.form-bizsafe :input').filter(function() { return this.value === ""; });
			} else if (quote_type == 'Training') {
				var input = $('.form-training :input').filter(function() { return this.value === ""; });
			}

			if (quote_type && client_status) {
				input.each(function() {
					if (this.required) {
						if ($(this).is(":visible")) {
							errors.push('- Please enter ' + this.title + '<br>')
						}
					}
				});
			}
		}

		if (errors.length > 0) {
			return toastr.error(errors, 'Error validation form')
		}

		var unsaved_site = false;
		var $added_sites = $('.other_address_history_id').filter(function() {
			if (!this.value) {
				$(this).closest('.row').addClass('border-danger');
				unsaved_site = true;
			}
		});

		if (unsaved_site) {
			Swal.fire({
	            title: 'Failed',
	            text: "Please save all new added sites before proceed",
	            icon: 'error',
	            showCancelButton: false,
	            confirmButtonText: 'Ok',
	        }).then(function (result) {
				$('html, body').animate({
		            scrollTop: $('.border-danger').offset().top
		        }, 1000);
	        });
			return false;
		}
		$( "#form-quotation" ).submit();
	}

	$(document).on('select2:open', () => {
		(list => list[list.length - 1])(document.querySelectorAll('.select2-container--open .select2-search__field')).focus()
	});

	$(document).on('change', '.client_country', function() {
		var country = $(this).val()
		var transportation = 'To be paid by the client';
        if(country == 'Singapore') {
            var transportation = 'N/A';
            $('#client_postal_code').attr('type', 'number')
		} else {
            $('#client_postal_code').attr('type', 'text')
        }

		if (quote_type == 'ISO') {
			$('.iso_assesment_fee_transportation').val(transportation)
		}

		if (quote_type == 'Training') {
			$('.training_assesment_fee_transportation').val(transportation)
		}
	})

    var _client_form_edited = false;
    function initializeClientData() {
        $(".client_field").each(function () {
            var fieldName = $(this).attr("id");
            var fieldValue = $(this).val();
            $(this).attr("data-originalValue", fieldValue);
        });
        _client_form_edited = false;
    }
    initializeClientData();

    $(".client_field").on("input change", function () {
        var currentValue = $(this).val();
        var originalValue = $(this).attr("data-originalValue");
        if (currentValue !== originalValue) {
            _client_form_edited = true
        } else {
            _client_form_edited = false
        }
    });

    var _contact_form_edited = false;
    function initializeContactData() {
        $(".create_contact_field").each(function () {
            var fieldName = $(this).attr("id");
            var fieldValue = $(this).val();
            $(this).attr("data-originalValue", fieldValue);
        });
        _contact_form_edited = false;
    }

    $(".create_contact_field").on("input change", function () {
        var currentValue = $(this).val();
        var originalValue = $(this).attr("data-originalValue");
        if (currentValue !== originalValue) {
            _contact_form_edited = true;
        } else {
            _contact_form_edited = false;
        }
    });

});
</script>
