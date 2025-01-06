<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
		<div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">#<?= $application->number ?></h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('application-form'); ?>">Application Form</a>
	                            </li>
	                            <li class="breadcrumb-item active">View Application Form
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
				<div class="p-0">
					<a class="btn btn-light text-danger" href="<?= site_url('application-form') ?>"> Cancel </a>
					<a href="<?= site_url('application-form/edit/'.$application->id) ?>" class="btn btn-primary">
						<i data-feather="edit-2" class="mr-1"></i> Edit
					</a>
				</div>
			</div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <div class="row">
					<div class="col-3">
						<p># </p>
						<b><?= leading_zero($application->id, 4); ?></b>
                    </div>
					<div class="col-3">
						<p>Client Name</p>
						<b><?= $application->client_name ?></b>
                    </div>
					<div class="col-3">
						<p>Status of Application</p>
						<b><?= $application->send_quotation_status ? send_quotation_status_badge($application->send_quotation_status) : '' ?></b>
                    </div>
                </div>
            </div>
        </div>

		<div class="card">
            <div class="card-body">
                <div class="row">
					<div class="col-3">
						<p>Certification Scheme </p>
						<?php if (substr($application->standard, 0, 1) == '['): ?>
							<?php foreach (json_decode($application->standard) as $standard) { ?>
								<b>- <?= $standard ?> </b><br>
							<?php } ?>
						<?php else: ?>
							<b>- <?= $application->standard ?> </b><br>
						<?php endif; ?>
                    </div>
					<div class="col-3">
						<p>Application Send Date</p>
						<b><?= human_date($application->send_date) ?></b>
                    </div>
					<div class="col-3">
						<p>Application Receive Date</p>
						<b><?= human_date($application->receive_date) ?></b>
                    </div>
                </div>
            </div>
        </div>

		<div class="card">
			<table class="table card-body datatable-follow-up p-0">
				<thead>
					<tr>
						<th></th>
						<th>Clarification Date</th>
						<th>Author</th>
						<th>Notes</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($follow_up as $row): ?>
						<tr>
							<td><?= $row->id ?></td>
							<td><?= human_date($row->clarification_date, 'M d, Y') ?></td>
							<td><?= $row->user ? $row->user->first_name.' '.$row->user->last_name : '' ?></td>
							<td><?= $row->notes ?></td>
							<td>
                                <?php if ($row->attachment): ?>
                                    <a href="#" class="view-attachment" data-id="<?= $row->id ?>"><i data-feather="paperclip" class="font-medium-4 mr-25"></i></a>
                                    <?php if(can('delete-application-form')) { ?>
                                        <a href="#" class="delete-follow-up" data-id="<?= $row->id ?>"><i data-feather="trash" class="font-medium-4 mr-25"></i></a>
                                    <?php }?>
                                <?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
        </div>
    </div>


	<div class="modal fade" id="add-follow-up-modal">
		<div class="modal-dialog modal-dialog-centered modal-lg sidebar-sm">
			<div class="modal-content pt-0">
				<div class="modal-header mb-1">
					<h5 class="modal-title">Add Follow Up</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<?php echo form_open_multipart(site_url('application_follow_up/store'), ['autocomplete' => 'off', 'id' => 'form-create-application', 'class' => '']); ?>
						<div class="row mb-2">
							<input type="hidden" name="application_id" value="<?= $application->id ?>">
							<div class="col-md-12">
								<div class="form-group">
									<label>Clarification Date <span class="text-danger">*</span></label>
									<input type="text" class="form-control flatpickr-basic" name="clarification_date" required>
								</div>
							</div>
							<div class="col-md-12">
								<label for="attachment">Attachment</label>
								<div class="form-group custom-file">
									<input type="file" class="custom-file-input" name="attachment[]" multiple>
									<label class="custom-file-label" for="attachment">Choose file...</label>
								</div>
							</div>
							<div class="col-md-12 mt-1">
								<div class="form-group">
									<label for="upload_application_form">Notes</label>
									<textarea name="notes" class="form-control" rows="4" required></textarea>
								</div>
							</div>
						</div>
						<center>
							<button type="submit" class="btn btn-primary">Submit</button>
						</center>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
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
										<span class="filename"></span><br>
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

    if ($('.datatable-follow-up').length > 0) {
        var poc_table = $('.datatable-follow-up')
            .DataTable({
				order: [[0, 'desc']],
                dom: '<"card-header border-bottom p-1"<"poc-head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [{
                    text: feather.icons['plus'].toSvg({ class: 'mr-50 font-small-4' }) + 'Add',
                    className: 'create-new btn btn-primary',
                    attr: {
                        'data-toggle': 'modal',
                        'data-target': '#add-follow-up-modal'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }],
				columnDefs: [
					{ targets: [4], sortable: false},
				]
            });

        $('div.poc-head-label').html('<h4 class="mb-0">Follow Up</h4>');
		poc_table.column(0).visible(false);
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


	$(document).on('click', '.delete-follow-up', function() {
        let followUpId = $(this).attr('data-id');

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
                    url: <?php echo json_encode(site_url("api/application_follow_up/delete")); ?>,
                    type: "POST",
                    data: {
                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                        follow_up_id: followUpId,
                    }
                }).done(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Follow up has been deleted.',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    }).then(function (result) {
                        window.location.href = "<?php echo site_url('application-form/view/'.$application->id); ?>";
                    });
                });
            }
        });
    });

});
</script>
