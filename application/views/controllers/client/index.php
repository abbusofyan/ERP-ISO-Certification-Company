<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">Client</h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('client'); ?>">Client</a>
	                            </li>
	                            <li class="breadcrumb-item active">All
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
				<div class="p-0">
					<div class="dropdown">
						<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
							<i data-feather="plus" class="mr-1"></i> Add Client
						</button>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="<?php echo site_url('client/create'); ?>">
								<i data-feather="user-plus" class="mr-1"></i> Add Client <br>
								<small>add client directly from here</small>
							</a>
							<a href="#" title="Import Client" target="_self" class="dropdown-item view-import-client">
							<i data-feather="upload" class="mr-1"></i> Import <br>
								<small>import multiple clients by upload PDF file</small>
							</a>
							<a class="dropdown-item" href="<?php echo site_url('client/export'); ?>">
								<i data-feather="download" class="mr-1"></i> Export <br>
								<small>Download client list to your local file</small>
							</a>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
    <div class="content-body">
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table class="datatables-basic table datatable-client" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/client")); ?>" data-filter-url="<?php echo htmlspecialchars(site_url("dt/client/filter")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
                                    <th data-priority="1">Company Name</th>
                                    <th data-priority="3">UEN Number</th>
                                    <th data-priority="4">Client Status</th>
                                    <th data-priority="5">Address</th>
									<th data-priority="6">Website</th>
									<th data-priority="7">Primary Contact</th>
                                    <th data-priority="2" width="100">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal modal-slide-in fade" id="modals-slide-in">
                <div class="modal-dialog sidebar-sm">
                    <?php echo form_open($form['action'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-client']); ?>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">Add Note</h5>
                        </div>
                        <div class="modal-body flex-grow-1">
							<input type="hidden" id="notes-client-id" name="client_id">
                            <div class="form-group">
                                <label class="form-label" for="name">Note <span class="text-danger">*</span></label>
								<?php echo form_textarea($form['note']); ?>
                            </div>
							<button type="submit" id="submit" form="form-client" class="btn btn-primary data-submit mr-1">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary btn-cancel-note" data-dismiss="modal">Cancel</button>
							<hr>

							<div class="notes-section"></div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

			<div class="modal modal-slide-in fade" id="modal-import-client">
                <div class="modal-dialog sidebar-sm">
                    <?php echo form_open_multipart(site_url('client/import'), ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-client']); ?>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">Import Client</h5>
                        </div>
                        <div class="modal-body flex-grow-1">
							<input type="hidden" id="notes-client-id" name="client_id">
							<div class="form-group custom-file">
								<input type="file" class="custom-file-input" name="file" required >
								<label class="custom-file-label" for="certification_and_reports_file">Choose file...</label>
							</div>
							<br> <br>
							<button type="submit" class="btn btn-primary mr-1">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
							<hr>

							<div class="notes-section"></div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

			<div class="modal modal-slide-in fade" id="view-main-contact">
                <div class="modal-dialog sidebar-sm">
                    <?php echo form_open($form['action_main_contact'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-main-contact']); ?>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">Main Contact</h5>
                        </div>
                        <div class="modal-body flex-grow-1">
							<div class="form-group">
                                <label class="form-label" for="name">Main Contact <span class="text-danger">*</span></label>
								<input type="hidden" name="client_id" id="main-contact-client-id">
								<select class="form-control" name="contact_id" id="main-contact-option">
								</select>
                            </div>
							<button type="submit" id="submit" form="form-main-contact" class="btn btn-primary data-submit mr-1 btn-submit-main-contact" disabled>Submit</button>
							<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
							<hr>
							<div class="main-contact-section">
								<label>Phone (Direct)</label>
								<p><b id="main-contact-phone"></b></p>
								<label>Fax (Direct)</label>
								<p><b id="main-contact-fax"></b></p>
								<label>Mobile</label>
								<p><b id="main-contact-mobile"></b></p>
								<label>Email</label>
								<p><b id="main-contact-email"></b></p>
								<label>Position</label>
								<p><b id="main-contact-position"></b></p>
								<label>Department</label>
								<p><b id="main-contact-department"></b></p>
							</div>

                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

			<div class="modal modal-slide-in fade" id="filter-modal-slide-in">
                <div class="modal-dialog sidebar-sm">
                    <?php echo form_open($form['action'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0 form-filter', 'id' => 'form-main-contact']); ?>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                        </div>
                        <div class="modal-body flex-grow-1">
							<h5>Status</h5>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="new" name="status[]" value="New"/>
									<label class="custom-control-label" for="new">
										<?= client_status_badge('New') ?>
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="active" name="status[]" value="Active"/>
									<label class="custom-control-label" for="active">
										<?= client_status_badge('Active') ?>
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="past-active" name="status[]" value="Past Active"/>
									<label class="custom-control-label" for="past-active">
										<?= client_status_badge('Past Active') ?>
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="non-active" name="status[]" value="Non-Active"/>
									<label class="custom-control-label" for="non-active">
										<?= client_status_badge('Non-Active') ?>
									</label>
								</div>
							</div>
							<hr>
							<h5>Area</h5>
							<?php for ($i=0; $i <= 2 ; $i++) { ?>
								<div class="form-group">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="area-<?= $postal_area[$i]['postal_district'] ?>" name="area[]" value="<?= $postal_area[$i]['postal_district'] ?>"/>
										<label class="custom-control-label" for="area-<?= $postal_area[$i]['postal_district'] ?>">
											<?= $postal_area[$i]['general_location'] ?>
										</label>
									</div>
								</div>
							<?php } ?>

							<div class="accordion" id="accordionExample">
								<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
									<?php for ($i=3; $i < count($postal_area) ; $i++) { ?>
										<div class="form-group">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="area-<?= $postal_area[$i]['postal_district'] ?>" name="area[]" value="<?= $postal_area[$i]['postal_sector_arr'] ?>"/>
												<label class="custom-control-label" for="area-<?= $postal_area[$i]['postal_district'] ?>">
													<?= $postal_area[$i]['general_location'] ?>
												</label>
											</div>
										</div>
									<?php } ?>
								</div>
								<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									View 25 more
									<i data-feather="chevron-down" class="mr-1"></i>
								</button>
							</div>

							<hr>
							<h5>Year Created</h5>
							<div class="form-group">
								<select class="form-control select2" id="year-created-select" name="year_created">
									<option value=""> -- Select Year Created --</option>
                                    <?php for ($i = 2016; $i <= date('Y') ; $i++) { ?>
            							<option value="<?= $i ?>"><?= $i ?></option>
            						<?php } ?>
								</select>
							</div>
							<hr>

							<h5>Date Created</h5>
							<div class="form-group">
								<input type="text" name="date_created_start" value="" id="date_created_start" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"  />
							</div>
							<p class="text-center">--To--</p>
							<div class="form-group">
								<input type="text" name="date_created_end" value="" id="date_created_end" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"  />
							</div>
							<hr>

							<h5>Flag</h5>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="flaged" name="flagged[]" value="1" />
									<label class="custom-control-label" for="flaged">Flaged</label>
								</div>
							</div>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="unflagged" name="flagged[]" value="0"/>
									<label class="custom-control-label" for="unflagged">Unflagged<label>
								</div>
							</div>
							<hr>
							<button class="btn btn-primary btn-filter" type="button">Apply Filter</button>
							<button class="btn btn-primary btn-clear-filter" type="button">Clear</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="history-modal">
	<div class="modal-dialog modal-xl sidebar-sm">
		<?php echo form_open($form['action'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-client']); ?>
			<div class="modal-header mb-1">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			</div>
			<div class="ml-3 mr-3 d-flex justify-content-between">
				<div>
					<h5 class="modal-title">History</h5>
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
						<div class="client_history_template">
							<div class="card client-template">
								<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<p>Modification Date</p>
											<b class="modification_date"></b>
										</div>
										<div class="col">
											<p>Modified By</p>
											<b class="modified_by"></b>
										</div>
										<div class="col">
											<p>Name</p>
											<b class="name"></b>
										</div>
										<div class="col">
											<p>UEN</p>
											<b class="uen"></b>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col">
											<p>Phone</p>
											<b class="phone"></b>
										</div>
										<div class="col">
											<p>Fax</p>
											<b class="fax"></b>
										</div>
										<div class="col">
											<p>Email</p>
											<b class="email"></b>
										</div>
										<div class="col">
											<p>Website</p>
											<b class="website"></b>
										</div>
									</div>
                                    <hr>
                                    <div class="row">
										<div class="col">
											<p>Status</p>
											<b class="status"></b>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="client-history-section"></div>
					</div>

					<div class="history-tabs" id="address-history">
						<div class="address_history_template">
							<div class="card address-template">
								<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<p>Modification Date</p>
											<b class="modification_date"></b>
										</div>
										<div class="col">
											<p>Modified By</p>
											<b class="modified_by"></b>
										</div>
										<div class="col">
										</div>
										<div class="col">
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col">
											<p>Address 1</p>
											<b class="address"></b>
										</div>
										<div class="col">
											<p>Address 2</p>
											<b class="address_2"></b>
										</div>
										<div class="col">
											<p>Postal Code</p>
											<b class="postal_code"></b>
										</div>
										<div class="col">
											<p>Country</p>
											<b class="country"></b>
										</div>
									</div>
                                    <hr>
									<div class="row">
										<div class="col">
											<p>No of Employee</p>
											<b class="total_employee"></b>
										</div>
										<div class="col"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="address-history-section"></div>
					</div>

					<div class="history-tabs" id="contact-history">
						<div class="contact_history_template">
							<div class="card contact-template">
								<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<p>Modification Date</p>
											<b class="modification_date"></b>
										</div>
										<div class="col">
											<p>Modified By</p>
											<b class="modified_by"></b>
										</div>
										<div class="col">
											<p>Contact Status</p>
											<b class="contact_status"></b>
										</div>
										<div class="col"></div>
									</div>
									<hr>
									<div class="row">
										<div class="col">
											<p>Salutation</p>
											<b class="salutation"></b>
										</div>
										<div class="col">
											<p>Name</p>
											<b class="name"></b>
										</div>
										<div class="col">
											<p>Position</p>
											<b class="position"></b>
										</div>
										<div class="col">
											<p>Department</p>
											<b class="department"></b>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col">
											<p>Email</p>
											<b class="email"></b>
										</div>
										<div class="col">
											<p>Phone (direct)</p>
											<b class="phone"></b>
										</div>
										<div class="col">
											<p>Fax (direct) </p>
											<b class="fax"></b>
										</div>
										<div class="col">
											<p>Mobile</p>
											<b class="mobile"></b>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="contact-history-section"></div>
					</div>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.select2').select2()
	$('.flatpickr-basic').flatpickr();

	$('#address-history').hide()
	$('#contact-history').hide()

	$('#select-history').on('change', function(e) {
		var value = $(this).val();
		$('.history-tabs').hide()
		$('.history-section .template').remove()
		$(value).show();
	});


    if ($('.datatable-client').length > 0) {
        let csrf = $('.datatable-client').data('csrf');
        let dtUrl = $('.datatable-client').data('url');
		var filterData = '';

        var table = $('.datatable-client')
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
						filter: function() {
							return filterData
						}
                    },
					complete: function() {
						const website_icon = $('.website-icon');
						website_icon.html(feather.icons['external-link'].toSvg());

						const contact_icon = $('.contact-icon');
						contact_icon.html(feather.icons['eye'].toSvg());

						const flag_icon = $('.flag-icon');
						flag_icon.html(feather.icons['flag'].toSvg());
				    },
                },
                order: [[0, 'asc']],
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [{
					text: feather.icons['filter'].toSvg({ class: 'mr-50 font-small-4' }) + ' Filter',
					className: 'create-new btn btn-white border-primary',
					attr: {
						'data-toggle': 'modal',
						'data-target': '#filter-modal-slide-in'
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
						data: 'uen',
					},
					{
						data: 'status',
					},
                    {
                        data: 'address',
						render: function(data, type, row) {
							if (data) {
                                if (row.address_2) {
                                    return row.address + ', ' + row.address_2 + ', '+ row.country + ', ' + row.postal_code
                                }
								return data + ', ' + row.country + ', ' + row.postal_code
							}
							return '';
						}
                    },
					{
						data: 'website',
                        searchable: true,
                        orderable: true,
                        render: function(data, type, row) {
							if (data) {
								return '<span class="menu-item text-truncate" data-i18n="Listing">'+data+'</span><a class="d-flex align-items-center" href="//'+data+'" target="_blank"><i class="website-icon"></i></a>';
							}
							return '';
                        }
                    },
					{
						data: 'contact_name',
                        searchable: true,
                        orderable: true,
                        render: function(data, type, row) {
							if (!data) {
								return '';
							}
							return '<span class="menu-item text-truncate" data-i18n="Listing">'+row.salutation + ' ' + data + '<br>'+row.contact_mobile+'</span><a class="d-flex align-items-center view-main-contact-btn" href="#" data-contact-id="'+row.contact_id+'" data-client-id="'+row.id+'" data-toggle="modal" data-target="#view-main-contact"><i class="contact-icon"></i></a>';
                        }
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
						data: 'client_name_history',
						visible: false
					},
					{
						data: 'postal_code',
						visible: false
					},
					{
						data: 'country',
						visible: false
					},
					{
						data: 'contact_email',
						visible: false
					},
					{
						data: 'contact_phone',
						visible: false
					},
					{
						data: 'contact_mobile',
						visible: false
					},
                    {
						data: 'combined_contact_name',
						visible: false
					}
                ]
            });

        $('div.head-label').html('<h6 class="mb-0">Client Listing</h6>');
    }

    // delete client
    $(document).on('click', '.delete-sa', function() {
        let clientId = $(this).attr('data-id');

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
                    url: <?php echo json_encode(site_url("api/client/delete")); ?>,
                    type: "POST",
                    data: {
                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                        client_id: clientId,
                    }
                }).done(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Client has been deleted.',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    }).then(function (result) {
                        window.location.href = "<?php echo site_url('client'); ?>";
                    });
                });
            }
        });
    });

    // submit form
    var form = $("#form-client");
    var loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle">Loading...</span>';

    $("#submit").click(function (e) {
        form.unbind("submit");
        form.submit(function (e) {
            $("#submit").html(loading).prop('disabled', true);
        });
    });

	$('.client_history_template').hide()
	$('.address_history_template').hide()
	$('.contact_history_template').hide()

	$(document).on('click', '.view-history', function() {
		var clientId = $(this).attr('data-id');
	    $('#select-history').val('#company-history');
	    $('#address-history').hide()
	    $('#contact-history').hide()
		$('#history-modal').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/get_history")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: clientId,
			}
		})
	    .done(function(data) {
			$('#company-history').show();
			$('.client-history-section .client-template').remove()
			$('.address-history-section .address-template').remove()
			$('.contact-history-section .contact-template').remove()

			var border = 'border-dark';
			data.client_history.forEach((item, i) => {
				var el = $('.client_history_template').children().clone()
					// .addClass(border).insertAfter('.client-template').end()
					.find('.id').html(item.id.padStart(4, '0')).end()
					.find('.modification_date').html(item.created_on).end()
					.find('.modified_by').html(item.created_by).end()
					.find('.name').html(item.name).end()
					.find('.uen').html(item.uen).end()
					.find('.phone').html(item.phone).end()
					.find('.fax').html(item.fax).end()
					.find('.email').html(item.email).end()
					.find('.website').html(item.website).end()
                    .find('.status').html(item.status).end();
				if (i != 0) {
					el.find('.current-data-label').remove().end()
				}
				el.appendTo('.client-history-section:last');
			});


			data.address_history.forEach((item, i) => {
				var el = $('.address_history_template').children().clone()
					.find('.id').html(item.id.padStart(4, '0')).end()
					.find('.modification_date').html(item.created_on).end()
					.find('.modified_by').html(item.created_by).end()
					.find('.address').html(item.address).end()
					.find('.address_2').html(item.address_2).end()
					.find('.postal_code').html(item.postal_code).end()
					.find('.country').html(item.country).end()
                    .find('.total_employee').html(item.total_employee).end()
				if (i != 0) {
					el.find('.current-data-label').remove().end()
				}
				el.appendTo('.address-history-section:last');
			});


			data.contact_history.forEach((item, i) => {
				// var border = i == 0 ? 'border-dark' : '';
				var el = $('.contact_history_template').children().clone()
					// .addClass(border).insertAfter('.contact-template').end()
					.find('.id').html(item.id.padStart(4, '0')).end()
					.find('.modification_date').html(item.created_on).end()
					.find('.modified_by').html(item.created_by).end()
					.find('.name').html(item.name).end()
					.find('.position').html(item.position).end()
					.find('.department').html(item.department).end()
					.find('.phone').html(item.phone).end()
					.find('.mobile').html(item.mobile).end()
					.find('.fax').html(item.fax).end()
					.find('.email').html(item.email).end()
                    .find('.contact_status').html(item.status).end()
				if (i != 0) {
					el.find('.current-data-label').remove().end()
				}
				el.appendTo('.contact-history-section:last');
			});
		});
	})

	$(document).on('click', '.view-notes', function() {
		var clientId = $(this).attr('data-id');
		$('#notes-client-id').val(clientId);
		$('#modals-slide-in').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/get_notes")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: clientId,
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
	})


	$(document).on('click', '.view-import-client', function() {
		var clientId = $(this).attr('data-id');
		$('#modal-import-client').modal('toggle');
	})



	$(document).on('click', '.view-main-contact-btn', function() {
		var contactId = $(this).attr('data-contact-id');
		var clientId = $(this).attr('data-client-id');
 		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/get_main_contact")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				contact_id: contactId,
				client_id: clientId
			}
		}).done(function(data) {
			$('#main-contact-phone').text(data.main_contact.phone)
			$('#main-contact-fax').text(data.main_contact.fax)
			$('#main-contact-email').text(data.main_contact.email)
			$('#main-contact-mobile').text(data.main_contact.mobile)
			$('#main-contact-position').text(data.main_contact.position)
			$('#main-contact-department').text(data.main_contact.department)

			$('#main-contact-client-id').val(clientId)

			$('.contact-options').remove();

			data.contact.forEach((item, i) => {
				console.log(item);
				var selected = '';
				if (item.id == data.main_contact.id) {
					selected = 'selected';
				}
				var html = '<option class="contact-options" '+selected+' value="'+item.id+'">'+item.salutation + ' ' + item.name + ' ( ' + item.position + ' )</option>';

				$('#main-contact-option').append(html)
			});
		});
	})


	$(document).on('click', '.flag', function() {
		var clientId = $(this).attr('data-id');
		var flag = $(this).attr('data-flag');
		var parent = $(this);

		if (flag == 0) {
			var icon = '<img class="flag-icon img-fluid rounded" src="<?php echo assets_url('img/flag.png'); ?>" />';
			var flagged = 1;
		} else {
			var icon = '<i class="flag-icon" style="color:red;"></i>';
			var flagged = 0;
		}

 		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/flag")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: clientId,
				flagged: flagged
			}
		}).done(function(data) {
			parent.children('.flag-icon').remove()
			parent.prepend(icon)
			parent.attr('data-flag', flagged)
		});
	})


	$(".btn-filter").click(function() {
		let dtUrl = $('.datatable-client').data('url');
		filterData = $('.form-filter').serialize();
	    table.ajax.url( dtUrl ).load();
	});

	$( document ).ajaxComplete(function() {
		$('.flag-icon').html(feather.icons['flag'].toSvg());
	});

	$(".btn-clear-filter").click(function() {
		$('input').each(function(index,data) {
		   var value = $(this).val();
		   var type = $(this).attr('type');
			switch (type) {
				case 'checkbox':
					$(this).prop('checked', false)
					break;
				case 'text':
					$(this).val('')
					break;
				default:
			}
			$('select').val('')
		});
		let dtUrl = $('.datatable-client').data('url');
		filterData = '';
	    table.ajax.url( dtUrl ).load();
	});

	$('#main-contact-option').change(function() {
		$('.btn-submit-main-contact').prop('disabled', false)
	})

	$('.btn-cancel-note').click(function() {
		$('.note-field').val('')
	})
});
</script>
