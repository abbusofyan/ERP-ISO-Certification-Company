<div class="content-wrapper container-xxl p-0">
	<?php echo form_open_multipart(site_url('application-form/edit/'.$application->id), ['autocomplete' => 'off', 'id' => 'form-edit-application', 'class' => '']); ?>
	<div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
	                    <h2 class="content-header-title float-left mb-0">Edit Application Form</h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('application-form'); ?>">Application Form</a>
	                            </li>
	                            <li class="breadcrumb-item">
									Edit Application Form
	                            </li>
	                        </ol>
	                    </div>
		            </div>
				</div>
				<div class="p-0">
					<a href="<?= site_url('application-form') ?>" class="btn btn-light text-primary">
						Cancel
					</a>
					<button class="btn btn-primary btn-save" type="button">Save</a>
				</div>
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
                                    <input type="text" class="form-control" name="client_name" value="<?= $application->client_name ?>" title="Client Name" required>
									<b class="text-danger"><?= form_error('client_name') ?></b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Certification Scheme <span class="text-danger">*</span></label>
									<select class="form-control select2 select-select2 standard" name="standard[]" title="Certification Scheme" multiple required>
                                        <?php foreach ($certification_scheme as $scheme) { ?>
                                            <option value="<?= $scheme->name ?>"><?= $scheme->name ?></option>
                                        <?php }  ?>
                                    </select>
									<b class="text-danger"><?= form_error('standard[]') ?></b>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status of Application <span class="text-danger">*</span></label>
									<select class="form-control select2 select-select2 select-status" name="send_quotation_status" value="<?= set_value('send_quotation_status') ?>" title="Status of Application" required>
										<option value="">-- select status --</option>
										<?php foreach ($status_send as $key => $status) { ?>
											<option data-key="<?= $key ?>" value="<?= $status->name ?>" <?= $status->name == $application->send_quotation_status ? 'selected' : '' ?>><?= $status->name ?></option>
										<?php } ?>
									</select>
									<b class="text-danger"><?= form_error('send_quotation_status') ?></b>
                                </div>
                            </div>
							<div class="col-md-6">
                                <div class="form-group">
                                    <label>Application Send Date <span class="text-danger">*</span></label>
									<input type="text" class="form-control flatpickr-basic send-date" name="send_date" value="<?= $application->send_date ?>" title="Application Send Date" required>
									<b class="text-danger"><?= form_error('send_date') ?></b>
								</div>
                            </div>
							<div class="col-md-6">
                                <div class="form-group">
                                    <label>Application Receive Date</label>
									<input type="text" class="form-control flatpickr-basic receive-date" name="receive_date" value="<?= $application->receive_date ?>" title="Application Receive Date" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<hr>
				<h3>Notes</h3><br>
				<div class="card">
                    <div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Clarification Date </label>
									<input type="text" class="form-control flatpickr-basic clarification-date" name="clarification_date">
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
								<div class="form-group">
									<label for="upload_application_form">Notes</label>
									<textarea name="notes" class="form-control" rows="4"></textarea>
								</div>
							</div>
						</div>
                    </div>
                </div>
				<hr>
				<h3>Past Follow Ups</h3><br>
				<?php foreach ($follow_up as $row): ?>
					<div class="row" style="margin-left:-50px">
						<div class="col-md-1 text-right p-0">
							<img src="<?= assets_url('img/blank-profile.png') ?>" class="rounded-circle" width="50" height="50">
						</div>
						<div class="col-md-11">
							<span><?= $row->user->first_name . ' ' . $row->user->last_name ?></span>
							<p><?= $row->user->group->name ?></p>
						</div>
					</div>
					<div class="row" style="margin-left:-50px">
						<div class="col-1"></div>
						<div class="col-11">
							<p><?= $row->notes ?></p>
							<p class="text-secondary"><?= human_timestamp($row->created_on) ?></p>
						</div>
					</div>
					<hr>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
	<?php echo form_close(); ?>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.select2').select2();
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

	if (Array.from('<?= $application->standard ?>')[0] == '[') {
		$('.standard').val(JSON.parse('<?= $application->standard ?>')).trigger('change');
	}

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

	$(document).on('change', '.select-status', function() {
		var status = $(this).val()
		if (status == 'App Form Received') {
			$('.receive-date').prop('required', true)
			openCalendar()
		} else {
			$('.receive-date').prop('required', false)
		}
	})

	function openCalendar(){
		setTimeout(function(){
			receiveDate.open();
		}, 0);
	}

	$('.btn-save').click(function() {
		validateForm()
	})

	function validateForm(quote_type) {
		var errors = [];

		var input = $('#form-edit-application :input').filter(function() { return this.value === ""; });

		input.each(function() {
			if (this.required) {
				if ($(this).is(":visible")) {
					errors.push('- Please enter ' + this.title + '<br>')
				}
			}
		});

		if (errors.length > 0) {
			return toastr.error(errors, 'Error validation form')
		}
		
		$( "#form-edit-application" ).submit();
	}

});
</script>
