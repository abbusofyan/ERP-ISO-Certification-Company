<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
		<div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0"><?= $invoice->number ?></h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('client'); ?>">Invoice</a>
	                            </li>
	                            <li class="breadcrumb-item active">Detail
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
				<div class="p-0">
					<button type="button" class="btn btn-primary " name="button"><i data-feather="message-circle" class="mr-1"></i> View Notes</button>
					<button type="button" class="btn btn-primary " name="button"><i data-feather="eye" class="mr-1"></i> Invoice PDF</button>
					<button type="button" class="btn btn-primary " name="button"><i data-feather="edit" class="mr-1"></i> Edit</button>
				</div>
			</div>
        </div>
    </div>
    <div class="content-body">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<p>Invoice No</p>
						<b><?= $invoice->number ?></b>
					</div>
					<div class="col-3">
						<p>Invoice Date </p>
						<b><?= human_timestamp($invoice->created_on) ?></b>
					</div>
					<div class="col-3">
						<p>Invoice For</p>
						<b><?= $invoice->quotation->certification_scheme->name ?></b>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-3">
						<p>Invoice Amount </p>
						<b><?= '' ?></b>
					</div>
					<div class="col-3">
						<p>Audit Fix Date-Calendar</p>
						<b><?= $invoice->audit_fixed_date ?></b>
					</div>
					<div class="col-3">
						<p>Invoice Follow Up Date-Calendar</p>
						<b><?= '' ?></b>
					</div>
				</div>
			</div>
		</div>
		<hr>

		<h4>Client Detail</h4>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<p>Client Name</p>
						<b><?= $invoice->quotation->client->name ?></b>
					</div>
					<div class="col-3">
						<p>Address </p>
						<b><?= $invoice->quotation->address->address.'<br>'.$invoice->quotation->address->address_2 ?></b>
					</div>
					<div class="col-3">
						<p>Certification Scheme</p>
						<b><?= $invoice->quotation->certification_scheme->name ?></b>
					</div>
				</div>
			</div>
		</div>

		<h4>Contact Detail</h4>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<p>Name (with salutation)</p>
						<b><?= $invoice->quotation->contact->salutation . '. ' . $invoice->quotation->contact->name?></b>
					</div>
					<div class="col-3">
						<p>Email </p>
						<b><?= $invoice->quotation->contact->email ?></b>
					</div>
					<div class="col-3">
						<p>Mobile</p>
						<b><?= $invoice->quotation->contact->mobile ?></b>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modals-slide-in">
            <div class="modal-dialog modal-lg sidebar-sm">
                <?php echo form_open_multipart($form['action'], ['autocomplete' => 'off', 'id' => 'form-branch', 'class' => 'modal-content pt-0']); ?>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title">Add New POC</h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <h5 class="mb-1">Contact Information</h5>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="salutation">Salutation <span class="text-danger">*</span></label>
									<?php echo form_dropdown($form['salutation']['name'], $form['salutation']['options'], $form['salutation']['selected'], $form['salutation']['attr']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="status">Status</label>
									<?php echo form_dropdown($form['status']['name'], $form['status']['options'], $form['status']['selected'], $form['status']['attr']); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="name">Name <span class="text-danger"></span></label>
									<?php echo form_input($form['name']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="email">Email</label>
									<?php echo form_input($form['email']); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="position">Position <span class="text-danger"></span></label>
									<?php echo form_input($form['position']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="department">Department</label>
									<?php echo form_input($form['department']); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="phone">Phone <span class="text-danger"></span></label>
									<?php echo form_input($form['phone']); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="mobile">Mobile</label>
									<?php echo form_input($form['mobile']); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="fax">Fax <span class="text-danger"></span></label>
									<?php echo form_input($form['fax']); ?>
								</div>
							</div>
						</div>
                        <hr>
                        <button type="submit" id="submit" form="form-branch" class="btn btn-primary data-submit mr-1">Add</button>
                        <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
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


    // delete branch
    $(document).on('click', '.delete-sa', function() {
        let contactId = $(this).attr('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
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
                    url: <?php echo json_encode(site_url("api/contact/delete")); ?>,
                    type: "POST",
                    data: {
                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                        contact_id: contactId,
                    }
                }).done(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'POC has been deleted.',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    }).then(function (result) {
                        // redirect to client details page
                        window.location.href = "<?php echo site_url('client') . $client->id; ?>";
                    });
                });
            }
        });
    });


    // submit form
    var form = $("#form-branch");
    var loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle">Loading...</span>';

    $("#submit").click(function (e) {
        form.unbind("submit");
        form.submit(function (e) {
            $("#submit").html(loading).prop('disabled', true);
        });
    });


	$(document).on("change", "#poc-select", function() {
		var poc_name = $( "#poc-select option:selected" ).text()
		var contact_id = $(this).val()
		var client_id = '<?= $client->id ?>'
		console.log(contact_id);
		Swal.fire({
            title: 'Set ' + poc_name + ' as main contact ?',
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
                    url: <?php echo json_encode(site_url("api/contact/switch_main_contact")); ?>,
                    type: "POST",
                    data: {
                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                        contact_id: contact_id,
						client_id: client_id
                    }
                }).done(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'POC has been deleted.',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    }).then(function (result) {
                        // redirect to client details page
                        window.location.href = "<?php echo site_url('client') . $client->id; ?>";
                    });
                });
            }
        });
	});

});
</script>
