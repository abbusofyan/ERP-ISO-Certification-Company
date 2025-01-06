<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">User</h2>
						<div class="breadcrumb-wrapper">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo site_url('user'); ?>">User</a>
								</li>
								<li class="breadcrumb-item active">Listing
								</li>
							</ol>
						</div>
					</div>
				</div>
				<a href="<?php echo site_url('user/create'); ?>" class="btn btn-primary">
					<i data-feather="plus" class="mr-1"></i> Add User
				</a>
			</div>
        </div>
    </div>
    <div class="content-body">
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table class="datatables-basic table datatable-user" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/user")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
                                    <th data-priority="7"></th>
                                    <th data-priority="1">First Name</th>
									<th data-priority="3">Last Name</th>
                                    <th data-priority="4">Email</th>
									<th data-priority="5">Mobile</th>
                                    <th data-priority="6">Role</th>
									<th data-priority="7">Status</th>
                                    <th data-priority="2" width="100">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

			<div class="row">
                <div class="col-12">
                    <div class="card">
                        <table class="datatables-basic table datatable-auth-log" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/auth_log")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
                                    <th data-priority="1">IP Address</th>
									<th data-priority="2">Date</th>
									<th data-priority="3">User</th>
                                    <th data-priority="4">Log In</th>
									<th data-priority="5">Log Out</th>
									<th data-priority="6"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal modal-slide-in fade" id="modals-slide-in">
                <div class="modal-dialog sidebar-sm">
                    <?php echo form_open($form['action'], ['autocomplete' => 'off', 'id' => 'form-user', 'class' => 'modal-content pt-0']); ?>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                        </div>
                        <div class="modal-body flex-grow-1">
                            <div class="form-group">
                                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                <?php echo form_input($form['name']); ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                <?php echo form_input($form['email']); ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                                <?php echo form_input($form['username']); ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <?php echo form_input($form['password']); ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                <?php echo form_input($form['confirm_password']); ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="contact">Contact <span class="text-danger">*</span></label>
                                <?php echo form_input($form['contact']); ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="reference_id">ID Number</label>
                                <?php echo form_input($form['reference_id']); ?>
                            </div>
                            <div class="form-group mb-4">
                                <label class="form-label" for="group_id">User Role</label>
                                <select class="form-control" name="group_id">
                                    <option value="1">Superadmin</option>
                                    <option value="2">Technician</option>
                                </select>
                            </div>
                            <button type="submit" id="submit" form="form-user" class="btn btn-primary data-submit mr-1">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

			<div class="modal fade" id="add-role-modal">
				<div class="modal-dialog modal-lg sidebar-sm">
					<?php echo form_open(site_url('group/store'), ['autocomplete' => 'off', 'class' => 'modal-content pt-0']); ?>
						<div class="modal-header mb-1">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
						</div>
						<div class="modal-body flex-grow-1">
							<div class="tab-content">
								<div class="card">
									<div class="card-body">
										<div class="form-group">
											<label>Role Name</label>
											<input type="text" class="form-control" name="name" placeholder="insert role name" required>
										</div>
										<hr>
										Permissions : <br>
										<?php if ($modules): ?>
											<div class="demo-inline-spacing">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" name="permission_id[]" onchange="checkAll()" id="permission-all" value="all" />
													<label class="custom-control-label" for="permission-all">Full Access</label>
												</div>
											</div>
											<br>
										<?php endif; ?>
										<?php foreach ($modules as $module): ?>
											<?= $module->name ?> <br>
											<div class="demo-inline-spacing">
												<?php foreach ($module->permissions as $permission): ?>
			                                        <div class="custom-control custom-checkbox">
			                                            <input type="checkbox" class="custom-control-input checkbox-permission" name="permission_id[]" id="permission-<?= $permission->id ?>" value="<?= $permission->id ?>" />
			                                            <label class="custom-control-label" for="permission-<?= $permission->id ?>"><?= $permission->display_name ?></label>
			                                        </div>
												<?php endforeach; ?>
											</div>
											<br>
										<?php endforeach; ?>
									</div>
								</div>
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
        </section>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    if ($('.datatable-user').length > 0) {
        let csrf  = $('.datatable-user').data('csrf');
        let dtUrl = $('.datatable-user').data('url');

        $('.datatable-user')
            .DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: {
                    url: dtUrl,
                    type: 'POST',
                    data: {
                        [csrf.name]: csrf.value,
                    },
                },
                order: [[0, 'desc']],
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [],
                columns: [
                    {
                        data: 'id',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
							return '<img class="img-fluid rounded" src="<?php echo assets_url('img/blank-profile.png'); ?>" width="20" alt="User profile" />';
                        }
                    },
                    {
                        data: 'first_name',
                    },
					{
                        data: 'last_name',
                    },
                    {
                        data: 'email',
                    },
					{
						data: 'contact',
					},
                    {
                        data: 'group_name',
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
                        data: 'tools',
                        searchable: false,
                        orderable: false,
                    },
                ]
            });

        $('div.head-label').html('<h6 class="mb-0">User Listing</h6>');
    }


	if ($('.datatable-auth-log').length > 0) {
		let csrf  = $('.datatable-auth-log').data('csrf');
		let dtUrl = $('.datatable-auth-log').data('url');

		$('.datatable-auth-log')
			.DataTable({
				processing: true,
				responsive: true,
				serverSide: true,
				ajax: {
					url: dtUrl,
					type: 'POST',
					data: {
						[csrf.name]: csrf.value,
					},
				},
				order: [[1, 'desc']],
				dom: '<"card-header border-bottom p-1"<"head-auth-log-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
				lengthMenu: [10, 25, 50, 100],
				displayLength: 10,
				buttons: [],
				columns: [
					{
						data: 'ip',
					},
					{
						data: 'date',
					},
					{
						data: 'first_name',
						orderable: false,
						render: function(data, type, row) {
							return row.first_name + ' ' + row.last_name
						}
					},
					{
						data: 'login_time',
					},

					{
						data: 'logout_time',
					},
					{
						data: 'last_name',
						visible: false
					},
				]
			});

		$('div.head-auth-log-label').html('<h6 class="mb-0">Logs</h6>');
	}


    // delete user
    var curUserID  = <?php echo $this->session->userdata()['user_id']; ?>;

    $(document).on('click', '.delete-sa', function() {
        let userId = $(this).attr('data-id');

        if (userId == curUserID) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Not allowed to delete your own account!',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
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
                        url: <?php echo json_encode(site_url("api/user/delete")); ?>,
                        type: "POST",
                        data: {
                            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                            user_id: userId,
                        }
                    }).done(function(data) {
                        if (data == false) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        } else {
                            // reload DT
                            var table = $('.datatable-user').DataTable();
                            table.ajax.reload();

                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'User has been deleted.',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        }
                    });
                }
            });
        }
    });


    // submit form
    var form = $("#form-user");
    var loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle">Loading...</span>';

    $("#submit").click(function (e) {
        form.unbind("submit");
        form.submit(function (e) {
            $("#submit").html(loading).prop('disabled', true);
        });
    });
});
</script>
