<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
					<form class="form-inline content-header-title d-flex align-items-center float-left mb-0">
						<h2 class="mr-1 memo-number"><?= $memo['number'] ?></h2>
					</form>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
								<a href="<?php echo site_url('client'); ?>">Quotation</a>
							</li>
                            <li class="breadcrumb-item">
								View Quotation
                            </li>
							<li class="breadcrumb-item">
								Edit Memo
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <?php echo form_open_multipart(site_url('memo/store'), ['autocomplete' => 'off', 'id' => 'form-edit-memo']); ?>
                        <div class="card-header">
                            <h4 class="card-title">Edit Memo</h4>
                        </div>
                        <div class="card-body">
							<input type="hidden" class="memo-number-val" name="number" value="<?= $memo['number'] ?>">
							<input type="hidden" name="status" value="<?= $memo['status'] ?>">
							<input type="hidden" name="type" value="<?= $memo['type'] ?>">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="address">Company Name <span class="text-danger">*</span></label>
										<input type="text" class="form-control" value="<?= $memo['quotation']->client->name ?>" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="address_2">Address</label>
										<input type="text" class="form-control" value="<?= full_address($memo['quotation']->address) ?>" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="postal_code">Memo Date <span class="text-danger">*</span></label>
										<input type="text" class="form-control flatpickr-basic" name="memo_date" value="<?= $memo['memo_date'] ?>" required>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="status">Message <span class="text-danger">*</span></label>
										<textarea name="message" id="ckeditor" class="form-control" rows="8" cols="80"><?php echo htmlspecialchars($memo['message']); ?></textarea>
									</div>
								</div>
								<input type="hidden" name="status" value="<?= $memo['status'] ?>">
								<input type="hidden" name="quotation_id" value="<?= $memo['quotation']->id ?>">
							</div>
							<button type="submit" class="btn btn-primary">Save</button>
							<a href="<?= site_url('quotation/view/'.$memo['quotation']->id) ?>" class="btn btn-light border-primary">Cancel</a>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$('.flatpickr-basic').flatpickr();
	CKEDITOR.replace('ckeditor');
	$('.edit-memo-number-field').hide()
	$('.btn-save-memo-number').hide()

	var _EDIT_MEMO_NUMBER = false;
	$(document).on('click', '.btn-edit-memo-number', function() {
		if (!_EDIT_MEMO_NUMBER) {
			_EDIT_MEMO_NUMBER = true;
			$('.edit-memo-number-field').show()
			$('.btn-save-memo-number').show()
			$('.btn-edit-memo-number').hide()
		}
	})

	$(document).on('click', '.btn-save-memo-number', function() {
		if (_EDIT_MEMO_NUMBER) {
			_EDIT_MEMO_NUMBER = false;
			var memo_number = $('.memo-number').text()
			var edit_memo_number = $('.edit-memo-number-field').val()

			$('.memo-number').html(memo_number + edit_memo_number)
			$('.memo-number-val').val(memo_number + edit_memo_number)
			$('.edit-memo-number-field').hide()
			$('.btn-save-memo-number').hide()
			$('.btn-edit-memo-number').show()
		}
	})
</script>
