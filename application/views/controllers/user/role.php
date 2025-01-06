<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">Role User</h2>
						<div class="breadcrumb-wrapper">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo site_url('user'); ?>">User</a>
								</li>
								<li class="breadcrumb-item active">Role
								</li>
							</ol>
						</div>
					</div>
				</div>
				<a class="btn btn-primary" href="#" data-toggle="modal" data-target="#add-role-modal">
					<i data-feather="plus" class="mr-1"></i> Add Role <br>
				</a>
			</div>

        </div>
    </div>
    <div class="content-body">
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
					<div class="card">
						<div class="card-body">
							<?php foreach ($groups as $group): ?>
								<div class="alert alert-secondary alert-dismissible fade show" role="alert">
	                                <div class="alert-body">
										<h3 class="text-dark"><?= $group->name ?></h3>
										<?php if ($group->full_access): ?>
											Full Access
										<?php else: ?>
											<?php foreach ($group->permissions as $permission): ?>
												<?= $permission->display_name. ' ' . $permission->module->name. ' , ' ?>
											<?php endforeach; ?>
										<?php endif; ?>
	                                </div>
	                                <button type="button" class="close btn-detail-group" data-id="<?= $group->id ?>">
	                                    <i data-feather="settings" class="font-medium-4"></i>
	                                </button>
	                            </div>
							<?php endforeach; ?>
						</div>
					</div>
                </div>
            </div>
			<div class="modal fade" id="add-role-modal">
				<div class="modal-dialog modal-lg sidebar-sm">
					<?php echo form_open(site_url('group/store'), ['autocomplete' => 'off', 'class' => 'modal-content pt-0 add-role-form']); ?>
						<div class="modal-header mb-1">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
						</div>
						<div class="modal-body flex-grow-1">
							<div class="tab-content">
								<div class="card">
									<div class="card-body">
										<div class="form-group">
											<label>Role Name</label>
											<input type="text" id="add-role-name" class="form-control" name="name" placeholder="insert role name" required>
										</div>
										<hr>
										Permissions : <br>
										<?php if ($modules): ?>
											<div class="demo-inline-spacing">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input add-role-checkbox" name="permission_id[]" onchange="checkAll()" id="permission-all" value="all" />
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
			                                            <input type="checkbox" class="custom-control-input checkbox-permission checkbox-create-role-permission" name="permission_id[]" id="permission-<?= $permission->id ?>" value="<?= $permission->id ?>" />
			                                            <label class="custom-control-label" for="permission-<?= $permission->id ?>"><?= $permission->display_name ?></label>
			                                        </div>
												<?php endforeach; ?>
											</div>
											<br>
										<?php endforeach; ?>
									</div>
								</div>
								<button type="button" class="btn btn-primary btn-submit-role">Submit</button>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>

			<div class="modal fade" id="detail-role-modal">
				<div class="modal-dialog modal-lg sidebar-sm">
					<?php echo form_open('#', ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-update-role']); ?>
						<div class="modal-header mb-1">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
						</div>
						<div class="modal-body flex-grow-1">
							<div class="tab-content">
								<div class="card">
									<div class="card-body">
										<div class="form-group">
											<label>Role Name</label>
											<input type="text" class="form-control input-detail-role-name" name="name" placeholder="insert role name" required>
										</div>
										<hr>
										Permissions : <br>
										<?php if ($modules): ?>
											<div class="demo-inline-spacing">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input checkbox-detail-role-permission-all" name="permission_id[]" id="detail-role-permission-all" value="all" />
													<label class="custom-control-label" for="detail-role-permission-all">Full Access</label>
												</div>
											</div>
											<br>
										<?php endif; ?>
										<?php foreach ($modules as $module): ?>
											<?= $module->name ?> <br>
											<div class="demo-inline-spacing">
												<?php foreach ($module->permissions as $permission): ?>
			                                        <div class="custom-control custom-checkbox">
			                                            <input type="checkbox" class="custom-control-input checkbox-detail-role-permission" data-permission-id="<?= $permission->id ?>" name="permission_id[]" id="detail-permission-<?= $permission->id ?>" value="<?= $permission->id ?>" />
			                                            <label class="custom-control-label" for="detail-permission-<?= $permission->id ?>"><?= $permission->display_name ?></label>
			                                        </div>
												<?php endforeach; ?>
											</div>
											<br>
										<?php endforeach; ?>
									</div>
								</div>
								Created on : <span id="role-created-on"></span><br>
								<hr>
								<button type="submit" class="btn btn-primary">
									<i data-feather="save" class="mr-50"></i>
									Save Changes</button>
								<button type="button" class="btn btn-primary btn-delete-role">
									<i data-feather="trash" class="mr-50"></i>
									Delete Role</button>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>

        </section>
    </div>
</div>

<script type="text/javascript">
	function checkAll() {
		var permissionAll = $('#permission-all').is(':checked')
		$('.checkbox-permission').attr('checked', permissionAll)
	}

	$('#detail-role-permission-all').change(function() {
		var permissionAll = $(this).is(':checked')
		$('.checkbox-detail-role-permission').attr('checked', permissionAll)
	})

	$('.checkbox-detail-role-permission').click(function() {
		var values = [];
		$('.checkbox-detail-role-permission').each(function(i, obj) {
			var checked = $(obj).is(':checked')
			values.push(checked)
		});
		if (values.every(Boolean)) {
			$('#detail-role-permission-all').attr('checked', true)
		} else {
			$('#detail-role-permission-all').attr('checked', false)
		}
	})

	$('.btn-delete-role').click(function() {
		let groupId = $(this).data('id');
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
					url: <?php echo json_encode(site_url("api/group/delete")); ?>,
					type: "POST",
					data: {
						<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
						group_id: groupId,
					}
				}).done(function() {
					Swal.fire({
						icon: 'success',
						title: 'Deleted!',
						text: 'Role has been deleted.',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					}).then(function (result) {
						// redirect to client page
						window.location.href = "<?php echo site_url('user/role'); ?>";
					});
				});
			}
		});
	})

	$('.btn-detail-group').click(function() {
		var groupId = $(this).data('id')
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/group/get_permission")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				group_id: groupId,
			}
		}).done(function(data) {
			var role_permissions = data.permissions
			var role_permissions_id = role_permissions.map(x => x.permission_id).map(Number);

			$('.input-detail-role-name').val(data.name)

			$('.checkbox-detail-role-permission').each(function(i, obj) {
				var permission_id = $(obj).data('permission-id')
				if (role_permissions_id.includes(permission_id)) {
					$(obj).attr('checked', true)
				} else {
					$(obj).attr('checked', false)
				}
			});

			if (data.full_access == '1') {
				$('#detail-role-permission-all').attr('checked', true)
				$('.checkbox-detail-role-permission').attr('checked', true)
			}

			$('#form-update-role').attr('action', '<?= site_url('group') ?>'+data.id+'/update')

			$('#role-created-on').html(data.created_on)

			$('.btn-delete-role').data('id', data.id)

			$('#detail-role-modal').modal('toggle')
		});
	})

	$("#add-role-modal").on("hidden.bs.modal", function(){
		$('#add-role-name').val('')
		$('.checkbox-permission').prop('checked', false);
	});

	$('.btn-submit-role').click(function() {
		var checked = false;
		$('.checkbox-create-role-permission').each(function(i, obj) {
			if($(obj).is(':checked')) {
				checked = true;
			}
		});

		if (!checked) {
			return Swal.fire({
				icon: 'error',
				title: 'Error!',
				text: 'Please select atleast one permission',
				customClass: {
					confirmButton: 'btn btn-success'
				}
			})
		}
		$('.add-role-form').submit()
	})
</script>
