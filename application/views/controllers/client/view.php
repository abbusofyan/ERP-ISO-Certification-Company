<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
		<div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0"><?= $client->name ?></h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('client'); ?>">Client</a>
	                            </li>
	                            <li class="breadcrumb-item active"><?= $client->name ?>
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
				<div class="p-0">
					<button type="button" data-toggle="modal" data-target="#note-modal-slide-in" class="btn btn-light text-primary border-primary " name="button"><i data-feather="message-circle" class="mr-1"></i> View Notes</button>
					<button type="button" data-toggle="modal" data-target="#view-history-modal" class="btn btn-light text-primary border-primary " name="button"><i data-feather="eye" class="mr-1"></i> View History</button>
					<a href="<?php echo site_url('client/edit/'.$client->id); ?>" class="btn btn-primary " name="button"><i data-feather="edit" class="mr-1"></i> Edit</a>
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
                            <div class="col-3">
								<p>Status</p>
								<?= client_status_badge($client->status) ?>
                            </div>
							<div class="col-3">
								<p>Company Name </p>
								<b><?= $client->name ?></b>
                            </div>
							<div class="col-3">
								<p>UEN</p>
								<b><?= $client->uen ?></b>
                            </div>
							<div class="col-3">
								<p>Website</p>
								<b><?= $client->website ?></b>
                            </div>
                        </div>
						<hr>
						<div class="row">
                            <div class="col-3">

                            </div>
							<div class="col-3">
								<p>Main Phone </p>
								<b><?= $client->phone ?></b>
                            </div>
							<div class="col-3">
								<p>Main Fax</p>
								<b><?= $client->fax ?></b>
                            </div>
							<div class="col-3">
								<p>Email</p>
								<b><?= $client->email ?></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<br>
		<div class="nav nav-tabs nav-fill w-100 border-primary" id="nav-tab" role="tablist">
			<a class="nav-item pt-1 pb-1 nav-link btn btn-light active text-primary" data-toggle="tab" href="#poc-section" role="tab"> <i data-feather="users" class="mr-1"></i> Contact</a>
			<a class="nav-item nav-link btn btn-light text-primary" data-toggle="tab" href="#address-section" role="tab"> <i data-feather="map-pin" class="mr-1"></i> Address</a>
			<a class="nav-item nav-link btn btn-light text-primary" data-toggle="tab" href="#application-form-section" role="tab"> <i data-feather="folder" class="mr-1"></i> Application Form</a>
			<a class="nav-item nav-link btn btn-light text-primary" data-toggle="tab" href="#quotation-section" role="tab"> <i data-feather="file-text" class="mr-1"></i> Quotation</a>
		</div>

		<div class="tab-content">

			<?php include 'includes_view/poc_tab.php'; ?>

			<?php include 'includes_view/address_tab.php'; ?>

			<?php include 'includes_view/application_form_tab.php'; ?>

			<?php include 'includes_view/quotation_tab.php'; ?>

		</div>


		<div class="modal modal-slide-in fade" id="note-modal-slide-in">
			<div class="modal-dialog sidebar-sm">
				<?php echo form_open($form_add_note['action'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0']); ?>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
					<div class="modal-header mb-1">
						<h5 class="modal-title" id="exampleModalLabel">Add Note</h5>
					</div>
					<div class="modal-body flex-grow-1">
						<input type="hidden" value="<?= $client->id ?>" name="client_id">
						<div class="form-group">
							<label class="form-label" for="name">Note <span class="text-danger">*</span></label>
							<?php echo form_textarea($form_add_note['note']); ?>
						</div>
						<button type="submit" class="btn btn-primary mr-1">Submit</button>
						<button type="reset" class="btn btn-outline-secondary btn-cancel-note" data-dismiss="modal">Cancel</button>
						<hr>

						<div class="notes-section">
							<?php foreach ($notes as $note): ?>
								<div class="notes">
									<div class="d-flex bd-highlight">
										<div class="pr-1 flex-shrink-1 bd-highlight">
											<img class="img-fluid" src="<?= assets_url('img/blank-profile.png') ?>" width="50" alt="">
										</div>
										<div class="w-100 bd-highlight">
											<b><?= $note->user->first_name . ' ' . $note->user->last_name?></b><br>
											<p><?= $note->user->group->name ?></p>
										</div>
									</div>
									<span><?= nl2br($note->note) ?><br><br><?= date('d M Y H:i', $note->created_on) ?><hr></span>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>

		<div class="modal fade" id="view-history-modal">
			<div class="modal-dialog modal-xl sidebar-sm">
				<div class="modal-content pt-0" id="form-client">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
					</div>
					<div class="ml-2 mr-2 mt-2 d-flex justify-content-between">
						<div>
							<h5>History</h5>
						</div>
						<div>
							<select class="form-control" id="select-history">
								<option value="#company-history">Company</option>
								<option value="#address-history">Company Address</option>
								<option value="#contact-history">Contact</option>
							</select>
						</div>
					</div>
					<div class="modal-body flex-grow-1">
						<div class="tab-content">
							<div class="history-tabs" id="company-history">
								<?php foreach ($client_history as $key => $history): ?>
									<?php $border = $key == 0 ? 'border-dark' : '' ?>
									<div class="card <?= $border ?>">
										<?php if ($key == 0): ?>
											<p class="text-center mt-2 bg-dark text-white">Current Data</p>
										<?php endif; ?>
										<div class="card-body">
											<div class="row">
                                                <div class="col">
        											<p>Modification Date</p>
        											<b class="modification_date"><?= $history->created_on ?></b>
        										</div>
        										<div class="col">
        											<p>Modified By</p>
        											<b class="modified_by"><?= $history->created_by ?></b>
        										</div>
        										<div class="col">
        											<p>Name</p>
        											<b class="name"><?= $history->name ?></b>
        										</div>
        										<div class="col">
        											<p>UEN</p>
        											<b class="uen"><?= $history->uen ?></b>
        										</div>
											</div>
                                            <hr>
                                            <div class="row">
        										<div class="col">
        											<p>Phone</p>
        											<b class="phone"><?= $history->phone ?></b>
        										</div>
        										<div class="col">
        											<p>Fax</p>
        											<b class="fax"><?= $history->fax ?></b>
        										</div>
        										<div class="col">
        											<p>Email</p>
        											<b class="email"><?= $history->email ?></b>
        										</div>
        										<div class="col">
        											<p>Website</p>
        											<b class="website"><?= $history->website ?></b>
        										</div>
        									</div>
                                            <hr>
                                            <div class="row">
        										<div class="col">
        											<p>Status</p>
        											<b class="status"><?= $history->status ?></b>
        										</div>
        									</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="history-tabs" id="address-history">
								<?php foreach ($address_history as $key => $address): ?>
									<?php $border = $key == 0 ? 'border-dark' : '' ?>
									<div class="card <?= $border ?>">
										<?php if ($key == 0): ?>
											<p class="text-center mt-2 bg-dark text-white">Current Data</p>
										<?php endif; ?>
										<div class="card-body">
											<div class="row">
												<div class="col-3">
													<p>Modification Time</p>
													<b><?= $address->created_on ?></b>
												</div>
												<div class="col-3">
													<p>Modified By</p>
													<b><?= $address->created_by?></b>
												</div>
                                                <div class="col-3">
												</div>
                                                <div class="col-3">
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-3">
													<p>Address</p>
													<b><?= $address->address ?></b>
												</div>
												<div class="col-3">
													<p>Address 2</p>
													<b><?= $address->address_2 ?></b>
												</div>
												<div class="col-3">
													<p>Postal Code</p>
													<b><?= $address->postal_code ?></b>
												</div>
												<div class="col-3">
													<p>Country</p>
													<b><?= $address->country ?></b>
												</div>
											</div>
											<hr>
                                            <div class="row">
        										<div class="col">
        											<p>No of Employee</p>
        											<b class="total_employee"><?= $address->total_employee ?></b>
        										</div>
        										<div class="col"></div>
        									</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="history-tabs" id="contact-history">
								<?php foreach ($contact_history as $key => $contact): ?>
									<?php $border = $key == 0 ? 'border-dark' : '' ?>
									<div class="card <?= $border ?>">
										<?php if ($key == 0): ?>
											<p class="text-center mt-2 bg-dark text-white">Current Data</p>
										<?php endif; ?>
										<div class="card-body">
											<div class="row">
												<div class="col">
													<p>Modification Time</p>
													<b><?= $contact->created_on ?></b>
												</div>
												<div class="col">
													<p>Modified By</p>
													<b><?= $contact->created_by?></b>
												</div>
                                                <div class="col">
        											<p>Contact Status</p>
        											<b class="contact_status"><?= $contact->status ?></b>
        										</div>
                                                <div class="col"></div>
											</div>
											<hr>
                                            <div class="row">
        										<div class="col">
        											<p>Salutation</p>
        											<b class="salutation"><?= $contact->salutation ?></b>
        										</div>
        										<div class="col">
        											<p>Name</p>
        											<b class="name"><?= $contact->name ?></b>
        										</div>
        										<div class="col">
        											<p>Position</p>
        											<b class="position"><?= $contact->position ?></b>
        										</div>
        										<div class="col">
        											<p>Department</p>
        											<b class="department"><?= $contact->department ?></b>
        										</div>
        									</div>
											<hr>
                                            <div class="row">
        										<div class="col">
        											<p>Email</p>
        											<b class="email"><?= $contact->email ?></b>
        										</div>
        										<div class="col">
        											<p>Phone (direct)</p>
        											<b class="phone"><?= $contact->phone ?></b>
        										</div>
        										<div class="col">
        											<p>Fax (direct) </p>
        											<b class="fax"><?= $contact->fax ?></b>
        										</div>
        										<div class="col">
        											<p>Mobile</p>
        											<b class="mobile"><?= $contact->mobile ?></b>
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
		</div>


    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.select2').select2();
	$('.address-select-country').select2()

    if ($('.datatable-poc').length > 0) {
        let csrf     = $('.datatable-poc').data('csrf');
        let dtUrl    = $('.datatable-poc').data('url');
        let clientId = $('.datatable-poc').data('client-id');

        var poc_table = $('.datatable-poc')
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
                dom: '<"card-header p-1"<"poc-head-label"><"dt-action-buttons row text-right"fB>> <"d-flex justify-content-between align-items-center mx-0 row">t<"d-flex justify-content-between mx-0 row"<"text-center col-sm-12 col-md-12"i>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [{
                    text: feather.icons['plus'].toSvg({ class: 'mr-50 font-small-4' }) + 'Add Other Contact',
                    className: 'create-new btn btn-white text-primary border-primary mt-1 ml-1 mr-1',
                    attr: {
                        'data-toggle': 'modal',
                        'data-target': '#add-poc-form-modal'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }],
                columns: [
                    {
                        data: 'salutation',
                    },
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
                        render: function(data, type, row) {
							return '<input type="hidden" id="primary-poc-'+row.id+'" value="'+row.primary+'">' + data
                        }
					},
					{
                        data: 'salutation',
						visible:false
                    },
					{
                        data: 'status',
						visible:false
                    },
                ]
            });

        $('div.poc-head-label').html('<h4 class="mb-0">Contact</h4>');
    }


	if ($('.datatable-address').length > 0) {
		let csrf     = $('.datatable-address').data('csrf');
		let dtUrl    = $('.datatable-address').data('url');
		let clientId = $('.datatable-address').data('client-id');

		var address_table = $('.datatable-address')
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
				order: [[6, 'desc']],
				dom: '<"card-header p-1"<"address-head-label"><"dt-action-buttons row text-right"fB>><"d-flex justify-content-between align-items-center mx-0 row">t<"d-flex justify-content-between mx-0 row"<" text-center col-sm-12 col-md-12"i>>',
				lengthMenu: [10, 25, 50, 100],
				displayLength: 10,
				buttons: [{
					text: feather.icons['plus'].toSvg({ class: 'mr-50 font-small-4' }) + 'Add Address',
					className: 'create-new btn btn-white border-primary mt-1 mr-1 ml-1 text-primary',
					attr: {
						'data-toggle': 'modal',
						'data-target': '#add-address-form-modal'
					},
					init: function (api, node, config) {
						$(node).removeClass('btn-secondary');
					}
				}],
				columns: [
					{
						data: 'address',
					},
					{
						data: 'address_2',
					},
					{
						data: 'country',
					},
					{
						data: 'postal_code',
					},
					{
						data: 'total_employee',
					},
					{
						data: 'tools',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
							return '<input type="hidden" id="primary-address-'+row.id+'" value="'+row.primary+'">' + data
                        }
					},
                    {
						data: 'primary',
                        visible: false
					},
				]
			});

		$('div.address-head-label').html('<h4 class="mb-0">Address</h4>');
	}


	if ($('.datatable-application-form').length > 0) {
        let csrf = $('.datatable-application-form').data('csrf');
        let dtUrl = $('.datatable-application-form').data('url');
		let clientId = $('.datatable-address').data('client-id');

		var filterData = '';

        var table = $('.datatable-application-form')
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
                order: [[1, 'asc']],
                dom: '<"card-header p-1"<"application-form-head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row">t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [],
                columns: [
                    {
						data: 'application_form',
						render: function(data, type, row) {
							var url = '<?= site_url("application-form/view") ?>'+row.id
							return '<b><a href="'+url+'">#'+data+'</a></b>'
                        }
					},
					{
						data: 'created_on',
					},
                    {
						data: 'tools',
                        searchable: false,
                        orderable: false,
					},
                ]
            });

        $('div.application-form-head-label').html('<h4 class="mb-0">Application Form</h4>');
    }


	if ($('.datatable-quotation').length > 0) {
        let csrf = $('.datatable-quotation').data('csrf');
        let dtUrl = $('.datatable-quotation').data('url');
		let clientId = $('.datatable-address').data('client-id');

		var filterData = '';

        var table = $('.datatable-quotation')
            .DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
				"initComplete": function(){
					const website_icon = $('.website-icon');
					website_icon.html(feather.icons['external-link'].toSvg());

					const contact_icon = $('.contact-icon');
					contact_icon.html(feather.icons['eye'].toSvg());

					const flag_icon = $('.flag-icon');
					flag_icon.html(feather.icons['flag'].toSvg());
				},
                ajax: {
                    url: dtUrl,
                    type: 'POST',
                    data: {
                        [csrf.name]: csrf.value,
						client_id: clientId
                    },
                },
                order: [[0, 'desc']],
                dom: '<"card-header p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row">t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
				buttons: [],
        columns: [
  {
    data: 'id',
                render: function(data, type, row) {
      return '<p><b class="text-danger">'+row.quote_number_formatted+'</b></p>' + row.quote_status
                }
            },
  {
    data: 'client_name',
                render: function(data, type, row) {
      return '<p>'+data+'</p>' + row.client_status
                }
            },
  {
                data: 'certification_scheme',
            },
  {
                data: 'certificate_cycle',
            },
  {
    data: 'created_on',
  },
  {
    data: 'confirmed_on',
  },
  {
    data: 'note',
  },
  {
                data: 'tools',
                searchable: false,
                orderable: false,
    render: function(data, type, row) {
      if (row.flagged == 1) {
        return '<div class="row"><div class="toggle-flag-icon flag mr-2" data-id="'+row.id+'" data-flag="1"><img class="flag-icon img-fluid rounded" src="<?php echo assets_url('img/flag.png'); ?>" /></div>'+data+'</div>';
      } else {
        return '<div class="row"><div class="toggle-flag-icon flag mr-2" data-id="'+row.id+'" data-flag="0"><i class="flag-icon" style="color:red"></i></div>'+data+'</div>';
      }
                }
            },
  {
    data: 'quote_number',
    visible: false
  }
        ]
            });

        $('div.head-label').html('<h4 class="mb-0">Quotation</h4>');
    }


    // delete poc
    $(document).on('click', '.delete-poc', function() {
        let contactId = $(this).attr('data-id');
		let primary = $('#primary-poc-'+contactId).val()
		if (primary == '1') {
			Swal.fire({
				title: "Can't delete primary contact",
				icon: 'warning',
				confirmButtonText: 'Ok',
				customClass: {
					confirmButton: 'btn btn-primary',
					cancelButton: 'btn btn-outline-danger ml-1'
				},
				buttonsStyling: false
			})
		} else {
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
							text: 'Contact has been deleted.',
							customClass: {
								confirmButton: 'btn btn-success'
							}
						}).then(function (result) {
							window.location.href = "<?php echo site_url('client/view/') . $client->id; ?>";
						});
					});
				}
			});
		}
    });



	// edit poc
    $(document).on('click', '.edit-poc', function() {
        let contactId = $(this).attr('data-id');
		var data = poc_table.row($(this).parents('tr')).data();

		$("#edit-poc-form-modal .poc-select-salutation").val(data.salutation).change();
		$("#edit-poc-form-modal .poc-select-status").val(data.status).change();
		$("#edit-poc-form-modal .poc-input-name").val(data.name);
		$("#edit-poc-form-modal .poc-input-email").val(data.email);
		$("#edit-poc-form-modal .poc-input-position").val(data.position);
		$("#edit-poc-form-modal .poc-input-department").val(data.department);
		$("#edit-poc-form-modal .poc-input-phone").val(data.phone);
		$("#edit-poc-form-modal .poc-input-mobile").val(data.mobile);
		$("#edit-poc-form-modal .poc-input-fax").val(data.fax);
		$("#edit-poc-form-modal #form-poc").attr('action', '<?= site_url("contact/update/") ?>' + data.id);
		$('#edit-poc-form-modal').modal('toggle')
    });



	// edit address
	$(document).on('click', '.edit-address', function() {
		let addressId = $(this).attr('data-id');
		var data = address_table.row($(this).parents('tr')).data();

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/address/get")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				address_id: addressId,
			}
		}).done(function(response) {
			var data = response.data;
			$("#edit-address-form-modal .edit-country-field").val(data.country).change();
			$("#edit-address-form-modal .edit-name-field").val(data.name);
			$("#edit-address-form-modal .edit-address-field").val(data.address);
			$("#edit-address-form-modal .edit-address-2-field").val(data.address_2);
			$("#edit-address-form-modal .edit-postal-code-field").val(data.postal_code);
			$("#edit-address-form-modal .edit-phone-field").val(data.phone);
			$("#edit-address-form-modal .edit-fax-field").val(data.fax);
            $("#edit-address-form-modal .edit-address-id-field").val(data.id);
			$("#edit-address-form-modal .edit-total-employee-field").val(data.total_employee);
			// $("#edit-address-form-modal #form-edit-address").attr('action', '<?= site_url("address") ?>' + data.id + '/edit');
			$('#edit-address-form-modal').modal('toggle')
		});
	});




	// delete address
	$(document).on('click', '.delete-address', function() {
		let addressId = $(this).attr('data-id');
		let primary = $('#primary-address-'+addressId).val()
		if (primary == '1') {
			Swal.fire({
				title: "Can't delete primary address",
				icon: 'warning',
				confirmButtonText: 'Ok',
				customClass: {
					confirmButton: 'btn btn-primary',
					cancelButton: 'btn btn-outline-danger ml-1'
				},
				buttonsStyling: false
			})
		} else {
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
						url: <?php echo json_encode(site_url("api/address/delete")); ?>,
						type: "POST",
						data: {
							<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
							address_id: addressId,
						}
					}).done(function() {
						Swal.fire({
							icon: 'success',
							title: 'Deleted!',
							text: 'Address has been deleted.',
							customClass: {
								confirmButton: 'btn btn-success'
							}
						}).then(function (result) {
							window.location.href = "<?php echo site_url('client/view') . $client->id; ?>";
						});
					});
				}
			});
		}
	});


	$(document).on("change", "#poc-select", function() {
		if ('<?= can("update-client") ?>') {
			var poc_name = $( "#poc-select option:selected" ).text()
			var contact_id = $(this).val()
			var client_id = '<?= $client->id ?>'
			var current_primary_contact = '<?= $primary_contact ? $primary_contact->id : '' ?>'

			if (contact_id && contact_id != current_primary_contact) {
				Swal.fire({
		            title: 'Set ' + poc_name + ' as primary contact ?',
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
		                        title: 'Updated!',
		                        customClass: {
		                            confirmButton: 'btn btn-success'
		                        }
		                    }).then(function (result) {
		                        // redirect to client details page
		                        window.location.href = "<?php echo site_url('client/view/') . $client->id; ?>";
		                    });
		                });
		            }
		        });
			}
		} else {
			alert('not allowed')
		}
	});



	// change primary address
	$(document).on("click", ".btn-change-primary-address", function() {
		var address_id = $(this).data('address-id')
		var client_id = $(this).data('client-id')

		if (address_id && client_id) {
			Swal.fire({
	            title: 'Set this data as primary address ?',
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
	                    url: <?php echo json_encode(site_url("api/address/switch_main_address")); ?>,
	                    type: "POST",
	                    data: {
	                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
	                        address_id: address_id,
							client_id: client_id
	                    }
	                }).done(function() {
	                    Swal.fire({
	                        icon: 'success',
	                        title: 'Updated!',
	                        customClass: {
	                            confirmButton: 'btn btn-success'
	                        }
	                    }).then(function (result) {
	                        window.location.href = "<?php echo site_url('client/view/') . $client->id; ?>";
	                    });
	                });
	            }
	        });
		}
	});

	$('.history-tabs').hide()
	$('#company-history').show();

	$('#select-history').on('change', function(e) {
	    var value = $(this).val();
		$('.history-tabs').hide()
	    $(value).show();
	});

	$(document).on('select2:open', () => {
		(list => list[list.length - 1])(document.querySelectorAll('.select2-container--open .select2-search__field')).focus()
	});

	$(document).on("click", ".btn-add-address", function(e) {
		validateForm()
	});

	function validateFormAddress() {
		var errors = [];

		var input = $('.form-add-address :input').filter(function() { return this.value === ""; });

		input.each(function() {
			if (this.required) {
				errors.push('- Please enter ' + this.title + '<br>')
			}
		});

		if (errors.length > 0) {
			return toastr.error(errors, 'Error validation form')
		} else {
			$( "#form-quotation" ).submit();
		}
	}

  $(document).on('click', '.view-notes', function() {
    var quotationId = $(this).attr('data-id');
    $('#notes-client-id').val(quotationId);
    $('#note-modal').modal('toggle');
    $.ajax({
      beforeSend  : function(request) {
        request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
      },
      url: <?php echo json_encode(site_url("api/quotation/get_notes")); ?>,
      type: "POST",
      data: {
        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
        quotation_id: quotationId,
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
    $('#note-quotation-id').val(quotationId)
  })

	$('.btn-cancel-note').click(function() {
		$('.note-field').val('')
	})

});
</script>
