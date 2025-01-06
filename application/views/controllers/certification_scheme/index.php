<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">Certification Scheme</h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('certification-scheme'); ?>">Certification Scheme</a>
	                            </li>
	                            <li class="breadcrumb-item active">Listing
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
				<div class="p-0">
					<div class="dropdown">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-certification-scheme-modal">
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
                        <table class="datatables-basic table datatable-certification-scheme" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/certification_scheme")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
                                    <th data-priority="3">Certification Scheme</th>
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


		<div class="modal modal fade" id="add-certification-scheme-modal">
			<div class="modal-dialog modal-dialog-centered sidebar-sm">
				<?php echo form_open($form['action'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-certification-scheme']); ?>
					<div class="modal-header mb-1">
						<h5 class="modal-title" id="exampleModalLabel">Add Certification Scheme</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
					</div>
					<div class="modal-body flex-grow-1">
						<div class="form-group">
							<label class="form-label" for="name">Name <span class="text-danger">*</span></label>
							<input type="text" name="name" value="" id="new-certification-scheme" class="form-control">
						</div>
						<button type="button" class="btn btn-primary data-submit btn-add-certification-scheme mr-1">Submit</button>
						<button type="reset" class="btn btn-outline-secondary btn-cancel" data-dismiss="modal">Cancel</button>
						<hr>
						<div class="notes-section"></div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>


		<div class="modal modal fade" id="edit-certification-scheme-modal">
			<div class="modal-dialog modal-dialog-centered sidebar-sm">
				<?php echo form_open('#', ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id'	=> 'form-edit-certification-scheme']); ?>
					<div class="modal-header mb-1">
						<h5 class="modal-title" id="exampleModalLabel">Edit Certification Scheme</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
					</div>
					<div class="modal-body flex-grow-1">
						<input type="hidden" name="id" class="certification_scheme_id" value="">
						<div class="form-group">
							<label class="form-label" for="name">Name <span class="text-danger">*</span></label>
							<input type="text" name="name" value="" id="edit-certification-scheme" class="certification_name form-control">
						</div>
						<button type="button" class="btn btn-primary data-submit btn-edit-certification-scheme mr-1">Save Changes</button>
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

    if ($('.datatable-certification-scheme').length > 0) {
        let csrf = $('.datatable-certification-scheme').data('csrf');
        let dtUrl = $('.datatable-certification-scheme').data('url');

        var table = $('.datatable-certification-scheme')
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
                order: [[0, 'asc']],
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [],
                columns: [
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

        $('div.head-label').html('<h6 class="mb-0">Certification Scheme Listing</h6>');
    }

    $(document).on('click', '.edit-modal', function() {
		var id = $(this).data('id')

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/certification_scheme/get")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				certification_scheme_id: id,
			}
		}).done(function(data) {
			$('.certification_name').val(data.name)
			$('.certification_scheme_id').val(data.id)
			$('#form-edit-certification-scheme').attr('action', '<?= site_url("certification-scheme/update/") ?>'+data.id)
			$('#edit-certification-scheme-modal').modal('toggle')
		});
	})


	$("#add-certification-scheme-modal").on("hidden.bs.modal", function(){
		$('#name').val('')
	});

	$(document).on('click', '.btn-add-certification-scheme', function() {
		var name = $('#new-certification-scheme').val()
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/certification_scheme/validate_create")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				name: name,
			}
		}).done(function(validate) {
			if(validate.status) {
				$('#form-certification-scheme').submit()
			} else {
				toastr.error(validate.data, 'Error validation form')
			}
		});
	})

	$(document).on('click', '.btn-edit-certification-scheme', function() {
		var name = $('#edit-certification-scheme').val()
		var id = $('.certification_scheme_id').val()

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/certification_scheme/validate_update")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				name: name,
				id:id
			}
		}).done(function(validate) {
			if(validate.status) {
				$('#form-edit-certification-scheme').submit()
			} else {
				toastr.error(validate.data, 'Error validation form')
			}
		});

	})

  $(document).on('click', '.btn-cancel', function() {
    $('#new-certification-scheme').val('')
  })

  $(document).on('click', '.delete-sa', function() {
      if ('<?= can("delete-certification-scheme") ?>') {
          let certificationSchemeId = $(this).attr('data-id');
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
                      url: <?php echo json_encode(site_url("api/certification_scheme/delete")); ?>,
                      type: "POST",
                      data: {
                          <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                          certification_scheme_id: certificationSchemeId,
                      }
                  }).done(function() {
                      Swal.fire({
                          icon: 'success',
                          title: 'Deleted!',
                          text: 'Certification scheme has been deleted.',
                          customClass: {
                              confirmButton: 'btn btn-success'
                          }
                      }).then(function (result) {
                          window.location.href = "<?php echo site_url('certification-scheme'); ?>";
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
