<div class="content-wrapper container-xxl p-0">
	<?php echo form_open_multipart(site_url('application-form/create'), ['autocomplete' => 'off', 'id' => 'form-create-application', 'class' => '']); ?>
	<div class="content-header-left col-md-12 col-12 mb-2">
		<div class="d-flex justify-content-between">
			<div class="pl-2">
				<div class="row breadcrumbs-top">
                    <h2 class="content-header-title float-left mb-0">Add Application Form</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('application-form'); ?>">Application Form</a>
                            </li>
                            <li class="breadcrumb-item">
								Add Application Form
                            </li>
                        </ol>
                    </div>
	            </div>
			</div>
			<div class="p-0">
				<a class="btn btn-light text-danger" href="<?= site_url('application-form') ?>"> Cancel </a>
				<button type="submit" class="btn btn-primary"> Save </button>
			</div>
		</div>
	</div>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Client Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="client_name" value="<?= set_value('client_name') ?>">
										<b class="text-danger"><?= form_error('client_name') ?></b>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Certification Scheme <span class="text-danger">*</span></label>
										<select class="form-control select2 select-select2" name="standard[]" value="<?= set_value('standard[]') ?>" multiple >
                                            <option value="">-- select standard --</option>
                                            <?php foreach ($certification_scheme as $scheme) { ?>
                                                <option value="<?= $scheme->name ?>" <?= isset($post_standard) ? in_array($scheme->name, $post_standard) ? 'selected' : '' : ''?>><?= $scheme->name ?></option>
                                            <?php }  ?>
                                        </select>
										<b class="text-danger"><?= form_error('standard[]') ?></b>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status of Application <span class="text-danger">*</span></label>
										<select class="form-control select2 select-select2 select-status" name="send_quotation_status" value="<?= set_value('send_quotation_status') ?>">
                                            <option value="">-- select status --</option>
                                            <?php foreach ($status_send as $key => $status) { ?>
												<?php if ($status->name == 'App Form Sent'): ?>
													<option data-key="<?= $key ?>" value="<?= $status->name ?>"><?= $status->name ?></option>
												<?php endif; ?>
                                            <?php } ?>
                                        </select>
										<b class="text-danger"><?= form_error('send_quotation_status') ?></b>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>Application Send Date <span class="text-danger">*</span></label>
										<input type="text" class="form-control flatpickr-basic send-date" name="send_date" value="<?= set_value('send_date') ?>">
										<b class="text-danger"><?= form_error('send_date') ?></b>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>Application Receive Date</label>
										<input type="text" class="form-control flatpickr-basic receive-date" name="receive_date" value="<?= set_value('receive_date') ?>">
										<b class="text-danger"><?= form_error('receive_date') ?></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
		<div class="row mt-2">
            <div class="col-12">
				<h3 class="mb-1">Notes</h3>
                <div class="card">
                    <div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Clarification Date</label>
									<input type="text" class="form-control flatpickr-basic clarification-date" name="clarification_date" >
								</div>
							</div>
							<div class="col-md-6">
								<label for="attachment">Attachment</label>
								<div class="form-group custom-file">
									<input type="file" class="custom-file-input" name="attachment[]" multiple>
									<label class="custom-file-label" for="attachment">Choose file...</label>
								</div>
							</div>
							<div class="col-md-12">
								<label for="upload_application_form">Notes</label>
								<textarea name="notes" class="form-control" rows="4"></textarea>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php echo form_close(); ?>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.select2').select2()
	$('.send-date').flatpickr({
		defaultDate: "today"
	});
	const receiveDate = $('.receive-date').flatpickr({
		minDate: "today"
	});
	const clarificationDate = $('.clarification-date').flatpickr({
		minDate: "today",
		defaultDate: "today"
	});

	flatpickr(".send-date", {
	  onChange: function(selectedDates) {
	    const selectedDate = selectedDates[0];
	    receiveDate.set("minDate", selectedDate);
		clarificationDate.set("minDate", selectedDate);
	  }
	});

	$(document).on('click', '#add-new-contact', function() {
		var el = $('.contact-template').children().clone()
		.find("input").val('').end()
		.appendTo('.new-contact:last');
	})

	const optionFormat = (item) => {
	    if (!item.id) {
	        return item.text;
	    }

		var badge_style = [
			'bg-light text-success border-success',
			'bg-light text-info border-info',
			'bg-purple text-purple',
			'bg-blue',
			'bg-orange',
			'bg-red'
		];

		var $status = $(
			'<span class="badge badge-pill '+badge_style[$(item.element).data('key')]+'">'+item.text+'</span>'
		);
		return $status;
	}

	$('.select-status').select2({
	    placeholder: "Select an option",
	    templateResult: optionFormat
	});

});
</script>
