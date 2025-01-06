<div class="modal modal-slide-in fade" id="view-memo-a-modal">
	<div class="modal-dialog modal-xl sidebar-sm">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-body flex-grow-1">
				<div class="d-flex justify-content-between">
					<h3>Memo A</h3>
					<div class="btn-edit-memo-section">
						<?php if (!$memo_a): ?>
							<a href="<?= site_url('memo/generate/A/'.$quotation->id) ?>" class="btn btn-primary btn-edit-memo pull-right"><i data-feather="edit" class="mr-1"></i> New</a>
						<?php endif; ?>

						<?php if ($memo_a &&  $memo_a[0]->status != 'Pending Approval'): ?>
							<a href="<?= site_url('memo/edit/'.$memo_a[0]->id) ?>" class="btn btn-primary btn-edit-memo pull-right"><i data-feather="edit" class="mr-1"></i> Edit</a>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<label>Status</label>
					<input type="text" class="form-control" readonly value="<?= $memo_a ? $memo_a[0]->status : 'New' ?>">
				</div>
				<hr>
				History <br><br>
				<?php if ($memo_a): ?>
					<?php foreach ($memo_a as $memo): ?>
						<div>
							<div class="d-flex justify-content-between">
								<div>
									<b class="memo_number text-danger"><?= $memo->number ?></b><br>
									<span class="memo_date"><?= human_timestamp($memo->created_on) ?></span>
								</div>
								<a href="<?= site_url('memo/download/'.$memo->id) ?>" target="_blank" class="btn-download"><i data-feather="download" class="mr-1"></i></a>
							</div>
							<b class="memo_status"><?= $memo->status ?></b><br>
							<hr>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
