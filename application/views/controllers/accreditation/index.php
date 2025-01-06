<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">Accreditation</h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('accreditation'); ?>">Accreditation</a>
	                            </li>
	                            <li class="breadcrumb-item active">Listing
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
				<div class="p-0">
					<div class="dropdown">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-accreditation-modal">
							<i data-feather="plus" class="mr-1"></i> Add
						</button>
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
                        <table class="datatables-basic table datatable-accreditation" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/accreditation")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
                                    <th data-priority="1"></th>
                                    <th data-priority="3">Accreditation</th>
                                    <th data-priority="4">Date Created</th>
                                    <th data-priority="5">Date Modified</th>
                                    <th data-priority="2" width="100">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>


		<div class="modal modal fade" id="add-accreditation-modal">
			<div class="modal-dialog modal-dialog-centered sidebar-sm">
				<?php echo form_open($form['action'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-accreditation']); ?>
					<div class="modal-header mb-1">
						<h5 class="modal-title" id="exampleModalLabel">Add Accreditation</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
					</div>
					<div class="modal-body flex-grow-1">
						<div class="form-group">
							<label class="form-label" for="name">Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="new-accreditation" name="name" value="" required>
						</div>
						<button type="button" class="btn btn-primary data-submit mr-1 btn-add-accreditation">Submit</button>
						<button type="reset" class="btn btn-outline-secondary btn-cancel" data-dismiss="modal">Cancel</button>
						<hr>

						<div class="notes-section"></div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>


		<div class="modal modal fade" id="edit-accreditation-modal">
			<div class="modal-dialog modal-dialog-centered sidebar-sm">
				<?php echo form_open(site_url('accreditation/update'), ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id'	=> 'edit-accreditation-form']); ?>
					<div class="modal-header mb-1">
						<h5 class="modal-title" id="exampleModalLabel">Edit Accreditation</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
					</div>
					<div class="modal-body flex-grow-1">
						<input type="hidden" name="id" class="accreditation_id" value="">
						<div class="form-group">
							<label class="form-label" for="name">Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control accreditation_name" id="edit-accreditation" name="name" value="">
						</div>
						<button type="button" class="btn btn-primary mr-1 btn-edit-accreditation">Save changes</button>
						<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
						<hr>

						<div class="notes-section"></div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>

    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.flatpickr-basic').flatpickr();

    if ($('.datatable-accreditation').length > 0) {
        let csrf = $('.datatable-accreditation').data('csrf');
        let dtUrl = $('.datatable-accreditation').data('url');

        var table = $('.datatable-accreditation')
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
						visible: false
					},
					{
                        data: 'name',
                    },
					{
						data: 'created_on',
					},
					{
						data: 'updated_on',
					},
                    {
                        data: 'tools',
                        searchable: false,
                        orderable: false,
                    },
                ]
            });

        $('div.head-label').html('<h6 class="mb-0">Accreditation Listing</h6>');
    }

	$(document).on('click', '.edit-modal', function() {
		var id = $(this).data('id')

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/accreditation/get")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				accreditation_id: id,
			}
		}).done(function(data) {
			$('.accreditation_name').val(data.name)
			$('.accreditation_id').val(data.id)
			$('#edit-accreditation-form').attr('action', '<?= site_url("accreditation/update/") ?>'+data.id)
			$('#edit-accreditation-modal').modal('toggle')
		});
	})

	$("#add-accreditation-modal").on("hidden.bs.modal", function(){
		$('#name').val('')
	});

	$(document).on('click', '.btn-add-accreditation', function() {
		var accreditation = $('#new-accreditation').val()
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/accreditation/validate_create")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				name: accreditation,
			}
		}).done(function(validate) {
			if(validate.status) {
				$('#form-accreditation').submit()
			} else {
				toastr.error(validate.data, 'Error validation form')
			}
		});
	})

	$(document).on('click', '.btn-edit-accreditation', function() {
		var accreditation = $('#edit-accreditation').val()
		var id = $('.accreditation_id').val()
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/accreditation/validate_update")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				name: accreditation,
				id: id
			}
		}).done(function(validate) {
			if(validate.status) {
				$('#edit-accreditation-form').submit()
			} else {
				toastr.error(validate.data, 'Error validation form')
			}
		});
	})

  $(document).on('click', '.btn-cancel', function() {
    $('#new-accreditation').val('')
  })

  $(document).on('click', '.delete-sa', function() {
      if ('<?= can("delete-accreditation") ?>') {
          let accreditationId = $(this).attr('data-id');
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
                      url: <?php echo json_encode(site_url("api/accreditation/delete")); ?>,
                      type: "POST",
                      data: {
                          <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                          accreditation_id: accreditationId,
                      }
                  }).done(function() {
                      Swal.fire({
                          icon: 'success',
                          title: 'Deleted!',
                          text: 'Accreditation has been deleted.',
                          customClass: {
                              confirmButton: 'btn btn-success'
                          }
                      }).then(function (result) {
                          window.location.href = "<?php echo site_url('accreditation'); ?>";
                      });
                  });
              }
          });
      } else {
          Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Action not allowed.',
              customClass: {
                  confirmButton: 'btn btn-success'
              }
          })
      }
  });


});
</script>
