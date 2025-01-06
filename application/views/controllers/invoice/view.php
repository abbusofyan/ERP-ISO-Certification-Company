<style media="screen">
	.navbar .dropdown-toggle::after{ color:black; }
</style>

<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
		<div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0"><?= $invoice->number ?></h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('invoice'); ?>">Invoice</a>
	                            </li>
	                            <li class="breadcrumb-item active">View Invoice
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
				<div class="p-0">
					<div class="d-flex">
						<?php if (in_array($invoice->request_status, ['Pending Create', 'Pending Update'])): ?>
							<a class="btn btn-secondary mr-1" readonly style="pointer-events: none;">Waiting for Approval</a>
						<?php else: ?>
							<a class="btn btn-primary mr-1" readonly style="pointer-events: none;"><?= $invoice->request_status ?></a>
						<?php endif; ?>

						<div class="dropdown mr-1">
							<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" data-offset="10,20">
								View
							</button>
							<div class="dropdown-menu dropdown-menu-right">
								<a class="dropdown-item view-notes" href="#" data-id="<?= $invoice->id ?>">Notes</a>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#attachment-modal">Attachment</a>
								<button class="dropdown-item" data-toggle="modal" data-target="#download-pdf-modal">Invoice PDF Version</button>
							</div>
						</div>
						<?php if ($invoice->status != 'Paid' && !in_array($invoice->request_status, ['Pending Create', 'Pending Update'])): ?>
							<a href="<?= site_url('invoice/edit/'.$invoice->id) ?>" class="btn btn-primary" name="button"><i data-feather="edit-3" class="mr-1"></i> Edit</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
        </div>
    </div>
    <div class="content-body">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-4">
						<p>Invoice No</p>
						<b><?= invoice_status_badge($invoice->status) . ' ' . $invoice->number ?></b>
					</div>
					<div class="col-4">
						<p>Invoice Date </p>
						<b><?= ($invoice->invoice_date) ?></b>
					</div>
					<div class="col-4">
						<p>Invoice For</p>
						<b><?= $invoice->invoice_type ?></b>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-4">
						<p>Invoice Amount </p>
						<b><?= money_number_format($invoice->amount, $invoice->address->country) ?></b>
					</div>
					<?php if ($invoice->quotation->type == 'ISO'): ?>
						<div class="col-4">
							<input type="hidden" id="invoice-id" value="<?= $invoice->id ?>">
							<p>Audit Fix Date-Calendar
								<a href="#" class="btn-edit-audit-fixed-date"><b><i data-feather="edit-3" class="ml-1"></i> Edit</b></a>
								<a href="#" class="btn-save-audit-fixed-date"><b><i data-feather="check" class="ml-1"></i> Save</b></a>
							</p>
							<input type="text" class="form-control flatpickr-basic" id="field-edit-audit-fixed-date">
							<b id="audit-fixed-date"><?= human_date($invoice->audit_fixed_date, 'Y-m-d') ?></b>
						</div>
					<?php endif; ?>
					<div class="col-4">
						<p>Invoice Follow Up Date-Calendar
							<a href="#" class="btn-edit-follow-up-date"><b><i data-feather="edit-3" class="ml-1"></i> Edit</b></a>
							<a href="#" class="btn-save-follow-up-date"><b><i data-feather="check" class="ml-1"></i> Save</b></a>
						</p>
						<input type="text" class="form-control flatpickr-basic" id="field-edit-follow-up-date">
						<b id="follow-up-date"><?= human_date($invoice->follow_up_date, 'Y-m-d') ?></b>
					</div>
				</div>
			</div>
		</div>
		<hr>

		<h4>Client</h4>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<p>Status</p>
						<b><?= client_status_badge($invoice->client->client->status) ?></b>
					</div>
					<div class="col-3">
						<p>Company Name</p>
						<b><?= $invoice->client->name ?></b>
					</div>
					<div class="col-3">
						<p>UEN </p>
						<b><?= $invoice->client->uen ?></b>
					</div>
					<div class="col-3">
						<p>Website</p>
						<b><?= $invoice->client->website ?></b>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-3">
						<p>Address</p>
						<b><?= full_address($invoice->address) ?></b>
					</div>
					<div class="col-3">
						<p>Phone</p>
						<b><?= $invoice->client->phone ?></b>
					</div>
					<div class="col-3">
						<p>Fax</p>
						<b><?= $invoice->client->fax ?></b>
					</div>
					<div class="col-3">
						<p>Email</p>
						<b><?= $invoice->client->email ?></b>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-3">
						<p>No of Employee</p>
						<b><?= $invoice->address->total_employee ?></b>
					</div>
				</div>
			</div>
		</div>

		<h4>Contact</h4>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<p>Salutation</p>
						<b><?= $invoice->contact->salutation?></b>
					</div>
					<div class="col-3">
						<p>Name</p>
						<b><?= $invoice->contact->name ?></b>
					</div>
					<div class="col-3">
						<p>Position </p>
						<b><?= $invoice->contact->position ?></b>
					</div>
					<div class="col-3">
						<p>Mobile</p>
						<b><?= $invoice->contact->mobile ?></b>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-3">
						<p>Department</p>
						<b><?= $invoice->contact->department ?></b>
					</div>
					<div class="col-3">
						<p>Phone (Direct)</p>
						<b><?= $invoice->contact->phone ?></b>
					</div>
					<div class="col-3">
						<p>Fax (Direct)</p>
						<b><?= $invoice->contact->fax ?></b>
					</div>
					<div class="col-3">
						<p>Email</p>
						<b><?= $invoice->contact->email ?></b>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="rejection-invoice-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg modal-dialog-centered">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Invoice Rejected</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
				<?php echo form_open_multipart('#', ['autocomplete' => 'off', 'id' => 'form-invoice-rejection-note']); ?>
					<div class="form-group">
						<label>Write reason here:</label>
						<textarea name="note" class="form-control" rows="5"></textarea>
					</div>
					<small>Changes written above will be automatically sync in PDF</small><br><br>
					<button type="submit" class="btn btn-primary float-right">Submit</button>
				<?php echo form_close(); ?>
		      </div>
		    </div>
		  </div>
		</div>
    </div>
</div>

<?php include 'includes_view/note_modal.php'; ?>
<?php include 'includes_view/attachment_modal.php'; ?>
<?php include 'includes_view/download_pdf_modal.php'; ?>


<script type="text/javascript">
$('.flatpickr-basic').flatpickr();
$('#field-edit-audit-fixed-date').hide()
$('.btn-save-audit-fixed-date').hide()

$('#field-edit-follow-up-date').hide()
$('.btn-save-follow-up-date').hide()

const follow_up_date = $('#field-edit-follow-up-date').flatpickr({
	minDate: setMinFollowupDate('<?= $invoice->audit_fixed_date ?>')
});

flatpickr("#field-edit-audit-fixed-date", {
	// minDate: "<?= human_timestamp($invoice->created_on, 'Y-m-d') ?>",
	onChange: function(selectedDates) {
		const selectedDate = selectedDates[0];
		follow_up_date.set("minDate", setMinFollowupDate(selectedDate));
	}
});

function setMinFollowupDate(audit_fixed_date) {
	var originalDate = new Date(audit_fixed_date);
	originalDate.setDate(originalDate.getDate() + 1);

	var year = originalDate.getFullYear();
	var month = (originalDate.getMonth() + 1).toString().padStart(2, '0'); // Month is zero-based, so we add 1
	var day = originalDate.getDate().toString().padStart(2, '0');

	var newDate = year + "-" + month + "-" + day;
	return newDate;
}

$(document).ready(function () {
    if ($('.datatable-poc').length > 0) {
        let csrf     = $('.datatable-poc').data('csrf');
        let dtUrl    = $('.datatable-poc').data('url');
        let clientId = $('.datatable-poc').data('client-id');

        $('.datatable-poc')
            .DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: {
                    url: dtUrl,
                    type: 'POST',
                    data: {
                        [csrf.name]: csrf.value,
                        client_id: clientId
                    },
                },
                order: [[0, 'asc']],
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [{
                    text: feather.icons['plus'].toSvg({ class: 'mr-50 font-small-4' }) + 'Add POC',
                    className: 'create-new btn btn-primary',
                    attr: {
                        'data-toggle': 'modal',
                        'data-target': '#modals-slide-in'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }],
                columns: [
                    {
                        data: 'name',
                    },
					{
                        data: 'position',
                    },
                    {
                        data: 'department',
                    },
                    {
                        data: 'mobile',
                    },
                    {
                        data: 'phone',
                    },
                    {
                        data: 'fax',
                    },
					{
                        data: 'email',
                    },
                    {
                        data: 'tools',
                        searchable: false,
                        orderable: false,
                    },
                ]
            });

        $('div.head-label').html('<h6 class="mb-0">POC</h6>');
    }

	$(document).on('click', '.btn-decline-invoice', function() {
		var url = $(this).attr('data-url');
		$('#form-invoice-rejection-note').attr("action", url)
		$('#rejection-invoice-modal').modal('toggle');
	})

	$(document).on('click', '.view-notes', function() {
		var invoiceId = $(this).attr('data-id');
		$('#note-invoice-id').val(invoiceId);
		$('#note-modal').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/invoice/get_notes")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				invoice_id: invoiceId,
			}
		}).done(function(data) {
			$('.notes').remove()
			data.forEach((item, i) => {
		        const html = `<div class="notes"><div class="d-flex bd-highlight">
		            <div class="pr-1 flex-shrink-1 bd-highlight">
		                <img class="img-fluid" src="<?= assets_url('img/blank-profile.png') ?>" width="50" alt="">
		            </div>
		            <div class="w-100 bd-highlight">
		                <b>`+item.user+`</b><br>
		                <p>`+item.role+`</p>
		            </div>
		        </div>
		        <span>`+item.note.replace(/\n/g, '<br>')+`<br><br>`+item.created_on+`<hr></span>
				</div>
		        `;
				$('.notes-section').append(html)
			});
		});
		$('#note-invoice-id').val(invoiceId)
	})

	$(document).on('click', '.btn-edit-audit-fixed-date', function() {
		$('#field-edit-audit-fixed-date').toggle()
		$('#audit-fixed-date').toggle()
		$('.btn-save-audit-fixed-date').toggle()
		$('.btn-edit-audit-fixed-date').toggle()
	})

	$(document).on('click', '.btn-edit-follow-up-date', function() {
		$('#field-edit-follow-up-date').toggle()
		$('#follow-up-date').toggle()
		$('.btn-save-follow-up-date').toggle()
		$('.btn-edit-follow-up-date').toggle()
	})

	$(document).on('click', '.btn-save-audit-fixed-date', function() {
		var auditFixedDate = $('#field-edit-audit-fixed-date').val()
		var invoiceId = $('#invoice-id').val()
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/invoice/update_audit_fixed_date")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				invoice_id: invoiceId,
				audit_fixed_date: auditFixedDate,

			}
		}).done(function(new_audit_fixed_date) {
			if (new_audit_fixed_date) {
				$('#audit-fixed-date').text(new_audit_fixed_date)
				$('#field-edit-audit-fixed-date').toggle()
				$('#audit-fixed-date').toggle()
				$('.btn-save-audit-fixed-date').toggle()
				$('.btn-edit-audit-fixed-date').toggle()

				Swal.fire({
					title: 'Success',
					text: "Audit fixed date updated",
					icon: 'success',
					confirmButtonText: 'OK'
				}).then((result) => {
					if (result.isConfirmed) {
						location.reload(); // Refresh the page
					}
				});
			} else {
				Swal.fire({
		            title: 'Error',
		            text: "Can't update audit fixed date!",
		            icon: 'error',
		        })
			}
		});
	})

	$(document).on('click', '.btn-save-follow-up-date', function() {
		var followUpDate = $('#field-edit-follow-up-date').val()
		var invoiceId = $('#invoice-id').val()

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/invoice/update_follow_up_date")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				invoice_id: invoiceId,
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

	$('.btn-cancel-note').click(function() {
		$('.note').val('')
	})
});
</script>
