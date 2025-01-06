<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
		<div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h3 class="content-header-title "><?= $quotation->number ?></h3>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('quotation'); ?>">Quotation </a>
	                            </li>
	                            <li class="breadcrumb-item active">View Quotation
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
				<div class="p-0">
					<a href="<?= site_url('quotation/export/'.$quotation->id) ?>" target="_blank" class="btn btn-primary"><i data-feather="file" class="mr-1"></i> Export PDF</a>
					<button type="button" data-toggle="modal" data-target="#view-other-quotes-modal" class="btn btn-primary " name="button"><i data-feather="eye" class="mr-1"></i> Other Quotes</button>
					<button type="button" data-toggle="modal" data-target="#view-history-modal" class="btn btn-primary " name="button"><i data-feather="clock" class="mr-1"></i> View History</button>
					<button type="button" data-toggle="modal" data-target="#view-notes-modal" class="btn btn-primary " name="button"><i data-feather="file" class="mr-1"></i> View Notes</button>
					<?php if (in_array($quotation->status, constant('QUOTATION_STATUS_ALLOWED_TO_EDIT'))): ?>
						<a href="<?= site_url('quotation/edit/'.$quotation->id) ?>" class="btn btn-primary " name="button"><i data-feather="edit-3" class="mr-1"></i> Edit</a>
					<?php endif; ?>
				</div>
			</div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
								<p>Quote Status</p>
								<div class="btn-group">
								  <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									  <?= $quotation->status ?>
								  </button>
								  <div class="dropdown-menu">
									  <?php foreach (constant('QUOTATION_STATUS') as $key => $status): ?>
										<?php if($status == 'Confirmed') { ?>
											<a class="dropdown-item" data-toggle="modal" data-target="#confirmDateModal"><?= quotation_status_badge($status) ?></a>
										<?php } else { ?>
											<?php if($status != 'Archive') { ?>
												<a class="dropdown-item quote-status" href="#" data-status="<?= $status ?>" data-quote-id="<?= $quotation->id ?>"><?= quotation_status_badge($status) ?></a>
											<?php } ?>
										<?php } ?>
									  <?php endforeach; ?>
								  </div>
									<div class="modal fade" id="confirmDateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Select Confirm Date</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<?php echo form_open_multipart(site_url('quotation/confirm/'.$quotation->id), ['autocomplete' => 'off', 'id' => 'form-confirm-date']); ?>
														<div class="form-group">
															<input type="text" class="form-control confirm-date flatpickr-basic" name="confirm_date">
														</div>
														<button type="button" class="btn btn-primary btn-sm btn-confirm-quotation">Confirm Quote</button>
													<?php echo form_close(); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
                            </div>
							<div class="col">
								<p>Quotation Type </p>
								<b><?= $quotation->type ?></b>
                            </div>
							<div class="col-3">
								<input type="hidden" id="quotation-id" value="<?= $quotation->id ?>">
								<input type="hidden" id="quotation-type" value="<?= $quotation->type ?>">
								<p>Quote Follow Up Date-Calendar
									<a href="#" class="btn-edit-follow-up-date"><b><i data-feather="edit-3" class="ml-1"></i> Edit</b></a>
									<a href="#" class="btn-save-follow-up-date"><b><i data-feather="check" class="ml-1"></i> Save</b></a>
								</p>
								<input type="text" class="form-control flatpickr-basic" id="field-edit-follow-up-date">
								<b id="follow-up-date"><?= human_date($quotation->follow_up_date, 'd/m/Y') ?></b>
							</div>
							<?php if ($quotation->type != 'Training'): ?>
                				<div class="col">
	  								<div class="dropdown">
	  									Memo
	  									<button class="btn btn-sm text-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" <?= $quotation->status == 'Confirmed' ? '' : 'disabled' ?>>
	  										<i data-feather="plus" class="ml-1"></i> Add Memo
	  									</button>
	  									<div class="dropdown-menu">
	  										<button class="dropdown-item btn-generate-memo-a" data-quotation-id="<?= $quotation->id ?>">Memo A</button>
	  										<button class="dropdown-item btn-generate-memo-b" data-quotation-id="<?= $quotation->id ?>">Memo B</button>
	  									</div>
	  								</div>
	  								<?php if ($memo_a): ?>
	  									<b><a href="#" data-toggle="modal" data-target="#view-memo-a-modal">Memo A <i data-feather="chevron-right" class="ml-1"></i></a></b><br>
	  								<?php endif; ?>

	  								<?php if ($memo_b): ?>
	  									<b><a href="#" data-toggle="modal" data-target="#view-memo-b-modal">Memo B <i data-feather="chevron-right" class="ml-1"></i></a></b>
	  								<?php endif; ?>
                				</div>
          					<?php endif; ?>
							<div class="col">
								<?php if ($quotation->status == 'Confirmed' && !$has_reached_max_total_invoice): ?>
									<?php $disabled = ''; ?>
								<?php else: ?>
									<?php $disabled = 'disabled'; ?>
								<?php endif; ?>

								<?php if ($quotation->type == 'Bizsafe'): ?>
									<?php echo form_open_multipart(site_url('invoice/create'), ['autocomplete' => 'off', 'id' => 'form-create-invoice', 'class' => '']); ?>
										<input type="hidden" name="quotation_id" value="<?= $quotation->id ?>">
										<input type="hidden" name="client_id" value="<?= $quotation->client_history_id ?>">
										<input type="hidden" name="address_id" value="<?= $quotation->address_history_id ?>">
										<input type="hidden" name="contact_id" value="<?= $quotation->contact_history_id ?>">
										<input type="hidden" name="audit_fixed_date" value="">
                                        <input type="hidden" name="invoice_type" value="Bizsafe">
										<p>Invoice <button type="submit" class="btn btn-sm text-primary" <?= $disabled ?>>
											<i data-feather="plus" class="ml-1"></i> Add Invoice
										</button></p>
									<?php echo form_close(); ?>
								<?php else: ?>
									<span>Invoice <button type="button" class="btn btn-sm text-primary" data-toggle="modal" data-target="#create-invoice-modal" <?= $disabled?>>
										<i data-feather="plus" class="ml-1"></i> Add Invoice
									</button></span>
								<?php endif; ?>
								<br>
								<?php if ($quotation->invoice): ?>
									<?php foreach ($quotation->invoice as $key => $invoice): ?>
										<a href="<?= site_url('invoice/view/'.$invoice->id) ?>"> <b><?= $invoice->invoice_type ?></b> </a> <br>
									<?php endforeach; ?>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<hr class="m-0"><br>

		<div class="d-flex justify-content-between">
			<h4>Client</h4>
			<div>
				<p>Status : <?= client_status_badge($quotation->client->client->deleted ? 'Deleted' : $quotation->client->client->status) ?></p>
			</div>
		</div>
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
								<p>Name</p>
								<b><?= $quotation->client->name ?></b>
                            </div>
							<div class="col-3">
								<p>UEN</p>
								<b><?= $quotation->client->uen ?></b>
                            </div>
							<div class="col-3">
								<p>Website</p>
								<b><?= $quotation->client->website ?></b>
                            </div>
							<div class="col-3">
								<p>No of Employee</p>
								<b><?= $quotation->address->total_employee ?></b>
                            </div>
                        </div>
						<hr>
						<div class="row">
							<div class="col-3">
								<p>Address</p>
								<b><?= full_address($quotation->address) ?></b>
							</div>
							<div class="col-3">
								<p>Phone</p>
								<b><?= $quotation->client->phone ?></b>
							</div>
							<div class="col-3">
								<p>Fax</p>
								<b><?= $quotation->client->fax ?></b>
							</div>
							<div class="col-3">
								<p>Email</p>
								<b><?= $quotation->client->email ?></b>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
		<hr class="m-0"><br>

		<div class="row">
			<div class="col-8">
				<h4>Primary Contact</h4>
			</div>
			<div class="col-4">
				<select class="form-control select-contact select2 select-select2" id="contact">
				</select>
			</div>
		</div>

		<div class="row mt-2">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<p>Salutation</p>
								<b><?= $quotation->contact->salutation ?></b>
							</div>
							<div class="col">
								<p>Name</p>
								<b><?= $quotation->contact->name ?></b>
							</div>
							<div class="col">
								<p>Position</p>
								<b><?= $quotation->contact->position ?></b>
							</div>
							<div class="col">
								<p>Department</p>
								<b><?= $quotation->contact->department ?></b>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col">
								<p>Phone (Direct)</p>
								<b><?= $quotation->contact->phone ?></b>
							</div>
							<div class="col">
								<p>Fax (Direct)</p>
								<b><?= $quotation->contact->fax ?></b>
							</div>
							<div class="col">
								<p>Email</p>
								<b><?= $quotation->contact->email ?></b>
							</div>
							<div class="col">
								<p>Mobile</p>
								<b><?= $quotation->contact->mobile ?></b>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	    <div class="modal modal-slide-in fade" id="view-attachment-modal">
	  		<div class="modal-dialog sidebar-sm">
	  			<div class="modal-content pt-0">
	  			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
	  				<div class="modal-header mb-1">
	  					<h5 class="modal-title">Attachment</h5>
	  				</div>
	  				<div class="modal-body flex-grow-1">
	  					<div class="view-attachment-template">
	  						<?php foreach ($quotation->assessment_fee_file as $file): ?>
	                <div class="card cloned">
	    							<div class="card-body">
	                    <div class="row">
	                      <div class="col-2 p-0">
	                        <i data-feather="paperclip" class="ml-1"></i>
	                      </div>
	                      <div class="col-8 p-0">
	                        <span class="filename mb-1"><?= $file->filename ?></span><br>
	                        <small class="mb-5 uploaded-on"><?= human_timestamp($file->created_on) ?></small><br>
	                        <?php if ($file->mime == 'application/pdf'): ?>
	                          <a href="<?= $file->url ?>" target="_blank" class="text-danger mr-50 btn-view-attachment-detail"><b>View</b> <span class="ml-50">|</span> </a>
	                        <?php endif; ?>
	                         <a href="<?= site_url('quotation/download_attachment/'.$file->id) ?>" class="text-danger btn-download-attachment-detail"><b>Download</b></a>
	                      </div>
	                      <div class="col-2 p-0">
	                        <i data-feather="trash" class="ml-1"></i>
	                      </div>
	                    </div>
	    							</div>
	    						</div>
	              <?php endforeach; ?>
	  					</div>
	  				</div>
	  			</div>
	  		</div>
	  	</div>

		<div class="modal modal-slide-in fade" id="view-past-certificate-attachment-modal">
	  		<div class="modal-dialog sidebar-sm">
	  			<div class="modal-content pt-0">
	  			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
	  				<div class="modal-header mb-1">
	  					<h5 class="modal-title">Attachment</h5>
	  				</div>
	  				<div class="modal-body flex-grow-1">
	  					<div class="view-attachment-template">
	  						<?php foreach ($quotation->past_certification_report as $file): ?>
	                <div class="card cloned">
	    							<div class="card-body">
	                    <div class="row">
	                      <div class="col-2 p-0">
	                        <i data-feather="paperclip" class="ml-1"></i>
	                      </div>
	                      <div class="col-8 p-0">
	                        <span class="filename mb-1"><?= $file->filename ?></span><br>
	                        <small class="mb-5 uploaded-on"><?= human_timestamp($file->created_on) ?></small><br>
	                        <?php if ($file->mime == 'application/pdf'): ?>
	                          <a href="<?= $file->url ?>" target="_blank" class="text-danger mr-50 btn-view-attachment-detail"><b>View</b> <span class="ml-50">|</span> </a>
	                        <?php endif; ?>
	                         <a href="<?= site_url('quotation/download_attachment/'.$file->id) ?>" class="text-danger btn-download-attachment-detail"><b>Download</b></a>
	                      </div>
	                      <div class="col-2 p-0">
	                        <i data-feather="trash" class="ml-1"></i>
	                      </div>
	                    </div>
	    							</div>
	    						</div>
	              <?php endforeach; ?>
	  					</div>
	  				</div>
	  			</div>
	  		</div>
	  	</div>

		<hr class="m-0"><br>

		<?php if ($quotation->type == 'ISO'): ?>
			<?php include 'includes_view/detail_iso_quotation.php'; ?>
		<?php endif; ?>

		<?php if ($quotation->type == 'Bizsafe'): ?>
			<?php include 'includes_view/detail_bizsafe_quotation.php'; ?>
		<?php endif; ?>

		<?php if ($quotation->type == 'Training'): ?>
			<?php include 'includes_view/detail_training_quotation.php'; ?>
		<?php endif; ?>

    </div>

	<?php include 'includes_view/view_other_quotes_modal.php'; ?>
	<?php include 'includes_view/view_history_modal.php'; ?>
	<?php include 'includes_view/view_memo_a_modal.php'; ?>
	<?php include 'includes_view/view_memo_b_modal.php'; ?>
	<?php include 'includes_view/create_invoice_modal.php'; ?>
	<?php include 'includes_view/view_notes_modal.php'; ?>

</div>




<script type="text/javascript">
$('.select2').select2();

$('#field-edit-follow-up-date').hide()
$('.btn-save-follow-up-date').hide()

$('#field-edit-confirm-date').hide()
$('.btn-save-confirm-date').hide()

$('.flatpickr-basic').flatpickr();

$('.fixed-audit-date').flatpickr({
	minDate: "today"
});

$('.confirm-date').flatpickr({
	maxDate: "today"
});

var contacts = JSON.parse('[{"id":"", "name":"", "position":""}]')
var newContacts = contacts.concat(JSON.parse('<?= json_encode($contacts) ?>'))
$("#contact").select2({
	placeholder: "select contact",
	data: newContacts,
	escapeMarkup: function(markup) {
		return markup;
	},
	templateResult: function (d) {
		return '<span>'+d.name+'</span><span class="pull-right subtext" style="float: right!important;">'+d.position+'</span>';
	},
	templateSelection: function() {
		return '<span>'+'<?= $quotation->contact->name ?>'+'</span><span class="pull-right subtext" style="float: right!important;">'+'<?= $quotation->contact->position ?>'+'</span>';
	}
})


$(document).on('change', '.select-contact', function(e) {
	if ('<?= can("update-quotation") ?>') {
		var contactId = $(this).val()
		var quotationId = '<?= $quotation->id ?>'

		Swal.fire({
			title: 'Note!',
			text: "The selected contact will be set as primary contact",
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
				$.ajax({
					beforeSend  : function(request) {
						request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
					},
					url: <?php echo json_encode(site_url("api/quotation/change_contact")); ?>,
					type: "POST",
					data: {
						<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
						contact_id: contactId,
						quotation_id: quotationId
					}
				}).done(function() {
					Swal.fire({
						icon: 'success',
						title: 'Switched!',
						text: 'Primary contact switched',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					}).then(function (result) {
						window.location.href = '<?= site_url('quotation/view/'.$quotation->id) ?>';
					});
				});
			}
		});
	} else {
		alert('Action not allowed!')
	}
})

$(document).on('click', '.quote-status', function() {
	if ('<?= can("update-quotation") ?>') {
		var quote_id = $(this).attr('data-quote-id')
		var status = $(this).data('status')

		Swal.fire({
			title: 'Note!',
			text: "Are you sure to change the quotation status ?",
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
				$.ajax({
					beforeSend  : function(request) {
						request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
					},
					url: <?php echo json_encode(site_url("api/quotation/update_status")); ?>,
					type: "POST",
					data: {
						<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
						quotation_id: quote_id,
						status: status
					}
				}).done(function() {
					Swal.fire({
						icon: 'success',
						title: 'Updated!',
						text: 'Quotation status updated!',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					}).then(function (result) {
						window.location.href = '<?= site_url('quotation/view/'.$quotation->id) ?>';
					});
				});
			}
		});
	} else {
		alert('Action not allowed!')
	}
})


$(document).on('click', '.btn-generate-memo-a', function() {
	var quotationId = $(this).data('quotation-id');
  $('#view-memo-a-modal').modal('toggle')
})


$(document).on('click', '.btn-generate-memo-b', function() {
	var quotationId = $(this).data('quotation-id');
  $('#view-memo-b-modal').modal('toggle')
})

$(document).on('click', '.btn-edit-follow-up-date', function() {
	$('#field-edit-follow-up-date').toggle()
	$('#follow-up-date').toggle()
	$('.btn-save-follow-up-date').toggle()
	$('.btn-edit-follow-up-date').toggle()
})

$(document).on('click', '.btn-save-follow-up-date', function() {
	var followUpDate = $('#field-edit-follow-up-date').val()
	var quotationId = $('#quotation-id').val();
	var quotationType = $('#quotation-type').val();

	if(!followUpDate) {
		return Swal.fire({
			title: 'Error',
			text: "Select follow up date",
			icon: 'error',
		})
	}

	$.ajax({
		beforeSend  : function(request) {
			request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
		},
		url: <?php echo json_encode(site_url("api/quotation/update_follow_up_date")); ?>,
		type: "POST",
		data: {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			quotation_id: quotationId,
			type: quotationType,
			follow_up_date: followUpDate,
		}
	}).done(function(new_follow_up_date) {
		if (new_follow_up_date) {
			$('#follow-up-date').text(new_follow_up_date)
			$('#field-edit-follow-up-date').toggle()
			$('#follow-up-date').toggle()
			$('.btn-save-follow-up-date').toggle()
			$('.btn-edit-follow-up-date').toggle()

			Swal.fire({
				title: 'Success',
				text: "Follow up date updated",
				icon: 'success',
			})
		} else {
			Swal.fire({
				title: 'Error',
				text: "Can't update follow up date!",
				icon: 'error',
			})
		}
	});
})

$(document).on("click", ".btn-confirm-quotation", function(e) {
	var confirm_date = $('.confirm-date').val()
	if(!confirm_date) {
		return toastr.error('Select confirm date', 'Error validation form')
	}
	$( "#form-confirm-date" ).submit();
});

$(document).on('click', '.btn-edit-confirm-date', function() {
	$('#field-edit-confirm-date').toggle()
	$('#confirm-date').toggle()
	$('.btn-save-confirm-date').toggle()
	$('.btn-edit-confirm-date').toggle()
})

$(document).on('click', '.btn-save-confirm-date', function() {
	var confirmDate = $('#field-edit-confirm-date').val()
	var quotationId = $('#quotation-id').val();
	var quotationType = $('#quotation-type').val();

	if(!confirmDate) {
		return Swal.fire({
			title: 'Error',
			text: "Select confirm date",
			icon: 'error',
		})
	}

	$.ajax({
		beforeSend  : function(request) {
			request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
		},
		url: <?php echo json_encode(site_url("api/quotation/update_confirm_date")); ?>,
		type: "POST",
		data: {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			quotation_id: quotationId,
			type: quotationType,
			confirm_date: confirmDate,
		}
	}).done(function(new_confirm_date) {
		if (new_confirm_date) {
			$('#confirm-date').text(new_confirm_date)
			$('#field-edit-confirm-date').toggle()
			$('#confirm-date').toggle()
			$('.btn-save-confirm-date').toggle()
			$('.btn-edit-confirm-date').toggle()

			Swal.fire({
				title: 'Success',
				text: "Confirm date updated",
				icon: 'success',
			})
		} else {
			Swal.fire({
				title: 'Error',
				text: "Can't update confirm date!",
				icon: 'error',
			})
		}
	});
})

$(document).on('click', '.btn-create-invoice', function() {
	var fixed_audit_date = $('.fixed-audit-date').val()
	var stage_audit = $('.invoice-stage-audit').val()
	var errors = [];
	if (!fixed_audit_date) {
		errors.push('- Select fixed audit date <br>')
	}

	if ('<?= $quotation->type == 'ISO' ?>') {
		if (!stage_audit) {
			errors.push('- Select invoice stage sudit <br>')
		}
	}

	if (errors.length > 0) {
		return toastr.error(errors, 'Error validation form')
	} else {
		$( "#form-create-invoice" ).submit();
	}
})

$('.history-type-section').hide()
$('.quotation-history').show()

$(document).on('change', '#select-history', function() {
  var history_type = $(this).val()
  $('.history-type-section').hide()

  $('.quotation_iso_history').hide()
  $('.quotation_bizsafe_history').hide()
  $('.quotation_training_history').hide()

  if (history_type == 'quotation') {
    $('.quotation-history').show()
  }

  if (history_type == 'client') {
    $('.client-history').show()
  }

  if (history_type == 'address') {
    $('.address-history').show()
  }

  if (history_type == 'contact') {
    $('.contact-history').show()
  }
})

	$('.btn-cancel-note').click(function() {
		$('.note').val('')
	})


</script>
