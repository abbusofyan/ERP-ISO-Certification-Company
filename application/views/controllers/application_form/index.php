<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">Application Form</h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('application-form'); ?>">Application Form</a>
	                            </li>
	                            <li class="breadcrumb-item active">All
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
				<div class="p-0">
					<a class="btn btn-primary" href="<?= site_url('application-form-template/download/'.$template_id) ?>">
						<i data-feather="download" class="mr-1"></i> Download Application Form Template
					</a>
					<a href="<?= site_url('application-form/create') ?>" class="btn btn-primary">
						<i data-feather="plus" class="mr-1"></i> Add Application Form
					</a>
				</div>
			</div>
        </div>
    </div>
    <div class="content-body">
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table class="datatables-basic table datatable-application-form" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/application_form")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
                                    <th data-priority="1">No</th>
                                    <th data-priority="3">Client Name</th>
                                    <th data-priority="4">Status</th>
                                    <th data-priority="5">Certification Scheme</th>
									<th data-priority="6">Dates</th>
									<th data-priority="7">Latest Follow Up</th>
                                    <th data-priority="2" width="100">Action</th>
									<th></th>
									<th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
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
						<div class="card cloned">
							<div class="card-body">
								<div class="row">
									<div class="col-2 p-0">
										<i data-feather="paperclip" class="ml-1"></i>
									</div>
									<div class="col-8 p-0">
										<span class="filename mb-1"></span><br>
										<small class="mb-5 uploaded-on"></small><br>
										<a class="text-danger mr-50 btn-view-attachment-detail"><b>View</b> <span class="ml-50">|</span> </a> <a class="text-danger btn-download-attachment-detail"><b>Download</b></a>
									</div>
									<div class="col-2 p-0">
										<i data-feather="trash" class="ml-1"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="view-attachment-section"></div>
				</div>
			</div>
		</div>
	</div>

</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.flatpickr-basic').flatpickr();

	$('.view-attachment-template').hide();

    if ($('.datatable-application-form').length > 0) {
        let csrf = $('.datatable-application-form').data('csrf');
        let dtUrl = $('.datatable-application-form').data('url');
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
						filter: function() {
							return filterData
						}
                    },
                },
                order: [[0, 'desc']],
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [],
                columns: [
                    {
                        data: 'number',
						searchable: true,
						render: function(data, type, row) {
							return '<b class="text-danger">#'+data+'</b>'
                        }
                    },
					{
						data: 'client_name',
					},
					{
						data: 'send_quotation_status',
					},
                    {
                        data: 'standard',
						width: '20%'
                    },
					{
                        data: 'send_date',
						render :function(data, type, row) {
							return row.dates
						},
						width: '10%'
                    },
					{
                        data: 'follow_up_id',
						width: '25%',
						render: function(data, type, row) {
							return row.follow_up
						}
                    },
                    {
                        data: 'tools',
						sortable: false
                    },
					{
						data: 'created_by_first_name',
						visible: false
					},
					{
						data: 'created_by_last_name',
						visible: false
					}
                ]
            });

        $('div.head-label').html('<h6 class="mb-0">Application Form Listing</h6>');
    }

	$(document).on('click', '.view-attachment', function() {
		var followUpId = $(this).attr('data-id');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/application_follow_up/get_latest_attachment_by_id")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				follow_up_id: followUpId,
			}
		}).done(function(data) {
			if (data) {
				$('.view-attachment-section .cloned').remove()

				data.forEach((item, i) => {
					var template = $('.view-attachment-template').children().clone()
						.find('.filename').html(item.file.filename).end()
						.find('.uploaded-on').html(item.file.created_on).end();
						if(item.file.mime == 'application/pdf') {
							template.find('.btn-view-attachment-detail').attr('data-file-url', item.file.url).end()
						} else {
							template.find('.btn-view-attachment-detail').hide().end()
						}
						template.find('.btn-download-attachment-detail').attr('href', '/application_follow_up/download_attachment_file/'+item.file.id).end()
						.appendTo('.view-attachment-section:last');
				});
				$('#view-attachment-modal').modal('toggle');
			} else {
				alert('No Attachment Found')
			}
		});
	})


	$(document).on('click', '.btn-view-attachment-detail', function() {
		var attachment_url = $(this).attr('data-file-url');
		window.open(attachment_url, '_blank');
	})

	// $(document).on('click', '.btn-download-attachment-detail', function() {
	// 	var attachment_url = $(this).attr('data-file-url');
	// 	window.location.href = attachment_url;
	// })


	// $(document).on('click', '.download-attachment', function() {
	// 	var followUpId = $(this).attr('data-id');
	// 	var link = $(this).attr('data-href');

	// 	$.ajax({
	// 		beforeSend  : function(request) {
	// 			request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
	// 		},
	// 		url: <?php echo json_encode(site_url("api/application_follow_up/get_latest_attachment_by_id")); ?>,
	// 		type: "POST",
	// 		data: {
	// 			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
	// 			follow_up_id: followUpId,
	// 		}
	// 	}).done(function(data) {
	// 		if (data) {
	// 			window.location.href = link;
	// 		} else {
	// 			alert('No Attachment Found')
	// 		}
	// 	});
	// })


	$(document).on('click', '.delete-application-form', function() {
        if ('<?= can("delete-application-form") ?>') {
			let applicationId = $(this).attr('data-id');
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
	                    url: <?php echo json_encode(site_url("api/application_form/delete")); ?>,
	                    type: "POST",
	                    data: {
	                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
	                        application_id: applicationId,
	                    }
	                }).done(function() {
	                    Swal.fire({
	                        icon: 'success',
	                        title: 'Deleted!',
	                        text: 'Application has been deleted.',
	                        customClass: {
	                            confirmButton: 'btn btn-success'
	                        }
	                    }).then(function (result) {
							window.location.href = "<?php echo site_url('application-form'); ?>";
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
