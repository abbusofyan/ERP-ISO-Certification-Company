<div class="modal modal-slide-in fade" id="notification-modal">
	<div class="modal-dialog sidebar-sm">
		<div class="modal-content pt-0 form-filter">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title">Notification</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<?php foreach ($notifications as $notif): ?>
					<p class="text-dark"><?= json_decode($notif->body)->message ?></p>
					<?php if ($notif->category == 'Memo'): ?>
						<button type="button" data-memo-id="<?= json_decode($notif->body)->memo_id ?>" class="btn btn-primary btn-sm btn-view-memo"><i data-feather="eye"></i> View</button>
					<?php endif; ?>
					<a href="javascript:void(0);" onclick="confirmDecline('<?= site_url('notification/process/'.$notif->id.'/Decline') ?>');" class="btn btn-primary btn-sm">
					    <i data-feather="x"></i> Decline
					</a>
					<a href="javascript:void(0);" onclick="confirmApprove('<?= site_url('notification/process/'.$notif->id.'/Approve') ?>');" class="btn btn-primary btn-sm">
					    <i data-feather="check"></i> Approve
					</a>
					<hr>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="notification-memo-modal">
	<div class="modal-dialog sidebar-sm">
		<div class="modal-content pt-0 form-filter">
			<div class="modal-header mb-1">
				<h5 class="modal-title">Memo Message</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			</div>
			<div class="modal-body flex-grow-1">
				<div id="memo-message"></div>
			</div>
		</div>
	</div>
</div>

<script>

function confirmDecline(url) {
	Swal.fire({
		title: 'Are you sure?',
		text: "Do you really want to decline this memo?",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, decline it!'
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = url;
		}
	});
}

function confirmApprove(url) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to approve this request?",
        icon: 'success',
        showCancelButton: true,
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

</script>
