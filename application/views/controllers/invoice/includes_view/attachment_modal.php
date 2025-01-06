<div class="modal modal-slide-in fade" id="attachment-modal">
	<div class="modal-dialog sidebar-sm">
		<?php echo form_open_multipart(site_url('invoice/add-attachment'), ['autocomplete' => 'off', 'class' => 'modal-content pt-0']); ?>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title" id="exampleModalLabel">Attachment</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<input type="hidden" name="invoice_id" value="<?= $invoice->id ?>">
				<div class="form-group">
					<label class="form-label" for="name">Select File <span class="text-danger">*</span></label>
					<input type="file" class="form-control" name="file[]" multiple>
				</div>
				<button type="submit" class="btn btn-primary btn-block"><i data-feather="upload" class="mr-1"></i> Upload Attachment</button>
				<hr>
				<?php foreach ($attachments as $attachment): ?>
					<div class="card cloned">
						<div class="card-body">
							<div class="row">
								<div class="col-2 p-0">
									<i data-feather="paperclip" class="ml-1"></i>
								</div>
								<div class="col-8 p-0">
									<span class="filename"><?= $attachment->file->filename ?></span><br>
									<small class="mb-5 uploaded-on">Uploaded on <?= human_timestamp($attachment->file->created_on, 'm d, Y') ?></small><br>
									<a class="text-danger mr-50 view-attachment btn-view-attachment-detail" href="<?= $attachment->file->url ?>" target="_blank"><b>View</b></a> | <a target="_blank" href="<?= site_url('invoice/download-attachment/'.$attachment->file->id) ?>" class="text-danger ml-50 btn-download-attachment-detail"><b>Download</b></a>
								</div>
								<div class="col-2 p-0">
									<a href="<?= site_url('invoice/delete-attachment/'.$attachment->id) ?>" onclick="return confirm('Are you sure you want to delete this attachment?')"><i data-feather="trash" class="ml-1"></i></a>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
