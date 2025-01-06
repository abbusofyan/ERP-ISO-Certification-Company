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
	                            <li class="breadcrumb-item active">Listing
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
							<a class="dropdown-item" href="<?php echo site_url('user/create'); ?>">
								<i data-feather="upload" class="mr-1"></i> Import <br>
								<small>import multiple clients by upload PDF file</small>
							</a>
							<a class="dropdown-item" href="<?php echo site_url('user/create'); ?>">
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
                                    <th data-priority="3">UEN</th>
                                    <th data-priority="4">Status</th>
                                    <th data-priority="5">Address</th>
									<th data-priority="6">Website</th>
									<th data-priority="7">Primary Contact</th>
                                    <th data-priority="2" width="100"></th>
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
							<button type="submit" id="submit" form="form-main-contact" class="btn btn-primary data-submit mr-1">Submit</button>
							<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
							<hr>
							<div class="main-contact-section">
								<label>Phone</label><br>
								<p><b id="main-contact-phone"></b></p>
								<label>Fax</label>
								<p><b id="main-contact-fax"></b></p>
								<label>Email</label>
								<p><b id="main-contact-email"></b></p>
								<label>Website</label>
								<b id="main-contact-website"></b>
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
										<span class="badge badge-pill badge-success bg-success bg-lighten-5">New</span>
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="active" name="status[]" value="Past Active"/>
									<label class="custom-control-label" for="active">
										<span class="badge badge-pill badge-warning bg-warning bg-lighten-5 text-warning">Past Active</span>
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="non-active" name="status[]" value="Non-Active"/>
									<label class="custom-control-label" for="non-active">
										<span class="badge badge-pill badge-secondary bg-secondary bg-lighten-5 text-dark">Non-Active</span>
									</label>
								</div>
							</div>
							<hr>

							<h5>Year Created</h5>
							<div class="form-group">
								<select class="form-control" id="year-created-select" name="year_created">
									<option value=""> -- Select Year Created --</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
									<option value="2022">2022</option>
									<option value="2023">2023</option>
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
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </section>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.flatpickr-basic').flatpickr();

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
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
							if (data == 'Active') {
								return '<span class="badge badge-pill badge-success">'+data+'</span>';
							} else {
								return '<span class="badge badge-pill badge-danger">'+data+'</span>';
							}
                        }
                    },
                    {
                        data: 'address',
                    },
					{
						data: 'website',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
							return '<span class="menu-item text-truncate" data-i18n="Listing">'+data+'</span><a class="d-flex align-items-center" href="'+data+'" target="_blank"><i class="website-icon"></i></a>';
                        }
                    },
					{
						data: 'contact_name',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
							return '<span class="menu-item text-truncate" data-i18n="Listing">'+data+'<br>'+row.contact_mobile+'</span><a class="d-flex align-items-center view-main-contact-btn" href="#" data-contact-id="'+row.contact_id+'" data-client-id="'+row.id+'" data-toggle="modal" data-target="#view-main-contact"><i class="contact-icon"></i></a>';
                        }
                    },
                    {
                        data: 'tools',
                        searchable: false,
                        orderable: false,
						render: function(data, type, row) {
							if (row.flagged == 1) {
								return '<div class="toggle-flag-icon"><img class="flag-icon flag img-fluid rounded" src="<?php echo assets_url('img/flag.png'); ?>" data-id="'+row.id+'" alt="flagged" />' + data + '</div>';
							} else {
								return '<div class="toggle-flag-icon"><i class="flag-icon flag" data-flag="0" style="color:red" data-id="'+row.id+'"></i> '+data+'</div>';
							}
                        }
                    },
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
                        // redirect to client page
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
				var html = '<div class="row notes">';
					html += '<div class="col-md-1">';
					html += '<i data-feather="upload" class="mr-1"></i>';
					html += '</div>';
					html += '<div class="col-md-11">';
					html += '<p>'+item.user+' <br>'+item.role+' </p>';
					html += '</div>';
					html += '<div class="col-12">';
					html += item.note + '<br>' + item.created_on + '<hr>';
					html += '</div>';
					html += '</div>';
				$('.notes-section').append(html)
			});
		});
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
			$('#main-contact-website').text(data.main_contact.website)

			$('#main-contact-client-id').val(clientId)

			$('.contact-options').remove();

			data.contact.forEach((item, i) => {
				var selected = '';
				if (item.id == data.main_contact.id) {
					selected = 'selected';
				}
				var html = '<option class="contact-options" '+selected+' value="'+item.id+'">'+item.name+' ( ' + item.position + ' )</option>';

				$('#main-contact-option').append(html)
			});
		});
	})


	$(document).on('click', '.flag', function() {
		var clientId = $(this).attr('data-id');
		var flag = $(this).attr('data-flag');

		if (flag == 0) {
			var icon = '<img class="flag-icon flag img-fluid rounded" data-id="'+clientId+'" src="<?php echo assets_url('img/flag.png'); ?>" height="30px" alt="flagged" />';
			var flagged = 1;
		} else {
			var icon = '<i class="flag-icon flag" data-flag="0" style="color:red; fill:red" data-id="'+clientId+'"></i>';
			var flagged = 0;
		}

		console.log(icon);

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
			$('.flag-icon').remove()
			$('.toggle-flag-icon').prepend(icon)
			const flag_icon = $('.flag-icon');
			flag_icon.html(feather.icons['flag'].toSvg());
		});
	})


	$(".btn-filter").click(function() {
		let dtUrl = $('.datatable-client').data('url');
		filterData = $('.form-filter').serialize();
	    table.ajax.url( dtUrl ).load();
	});
});
</script>
