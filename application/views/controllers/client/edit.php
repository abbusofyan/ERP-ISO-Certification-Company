<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Client</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('client'); ?>">Client</a>
                            </li>
                            <li class="breadcrumb-item">
								Edit
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
				<input type="hidden" class="client_id" value="<?= $client->id ?>">
				<input type="hidden" class="address_id" value="<?= $client->primary_address->id ?>">

				<div class="client-form">
					<div class="card">
						<div class="card-header border-bottom">
							<h4 class="card-title">Client Details</h4>
						</div>
						<div class="card-body">
							<div class="alert alert-primary error-client-validation alert-dismissible fade show" role="alert"></div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="name">Name <span class="text-danger">*</span></label>
										<input type="text" value="<?= $client->name ?>" id="client_name" class="form-control client_field" placeholder="Web Imp Pte Ltd" maxlength="200">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="uen">UEN <span class="text-danger">*</span></label>
										<input type="text" value="<?= $client->uen ?>" id="client_uen" class="form-control client_field" placeholder="196700066N" maxlength="20">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="name">Address 1 <span class="text-danger">*</span></label>
										<textarea cols="40" rows="3" id="client_address" class="form-control client_field" placeholder="Branch address" maxlength="200"><?= $client->primary_address->address ?></textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="uen">Address 2 <span class="text-danger">*</span></label>
										<textarea cols="40" rows="3" id="client_address_2" class="form-control client_field" placeholder="2nd Branch address" maxlength="200"><?= $client->primary_address->address_2 ?></textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="status">Country <span class="text-danger">*</span></label>
										<select id="client_country" class="form-control client_country select2 select-select2 client_field">
											<option value="">-- select country --</option>
											<?php foreach ($countries as $country): ?>
												<option value="<?= $country->name ?>" <?= $country->name == $client->primary_address->country ? 'selected' : '' ?>><?= $country->name ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="postal_code">Postal Code <span class="text-danger">*</span></label>
										<input type="<?= $client->primary_address->country == 'Singapore' ? 'number' : 'text' ?>" value="<?= $client->primary_address->postal_code ?>" id="client_postal_code" class="form-control client_field" placeholder="16517">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="phone">Phone</label>
										<input type="number" value="<?= $client->phone ?>" id="client_phone" class="form-control client_field" placeholder="6344488889" maxlength="8" onkeydown="return event.keyCode !== 69">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="fax">Fax</label>
										<input type="number" value="<?= $client->fax ?>" id="client_fax" class="form-control client_field" placeholder="6344488889" maxlength="8" onkeydown="return event.keyCode !== 69">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="website">Website</label>
										<input type="text" value="<?= $client->website ?>" id="client_website" class="form-control client_field" placeholder="company.com" maxlength="200">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="email">Email </label>
										<input type="text" value="<?= $client->email ?>" id="client_email" class="form-control client_field" placeholder="company@mail.com" maxlength="150">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="postal_code">No of Employee <span class="text-danger">*</span></label>
										<input type="number" value="<?= $client->primary_address->total_employee ?>" id="client_total_employee" class="form-control client_field" placeholder="100">
									</div>
								</div>
								<div class="col-md-12">
									<button type="button" class="btn btn-primary mb-1 btn-update-client float-right" name="button">
										<i data-feather="save" class="mr-1"></i> Update
									</button>
 								</div>
							</div>

						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {

	$('.select2').select2();

    // submit form
    var form = $("#form-edit-user");
    var loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle">Loading...</span>';

    $("#submit").click(function (e) {
        form.unbind("submit");
        form.submit(function (e) {
            $("#submit").html(loading).prop('disabled', true);
        });
    });

	// add contact field
	$("#add-new-contact").click(function(e) {
		var el = $('.contact-template').children().clone().find("input").val("").end().appendTo('.new-contact:last');
	});

	// delete contact row
	$(document).on("click", ".btn-delete-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		var keyholder_id = $theRow.find('input#id').val();
		if (confirm("This is not reversible. Are you sure?")) {
			$theRow.remove();
		}
	});

	$(document).on('click', '.btn-update-client', function() {
		var address_id = $('.address_id').val()
		var client_id = $('.client_id').val()

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/update")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: client_id,
				address_id: address_id,
				name: $('#client_name').val(),
				uen: $('#client_uen').val(),
				address: $('#client_address').val(),
				address_2: $('#client_address_2').val(),
				country: $('#client_country').val(),
				postal_code: $('#client_postal_code').val(),
				phone: $('#client_phone').val(),
				fax: $('#client_fax').val(),
				website: $('#client_website').val(),
				email: $('#client_email').val(),
				total_employee: $('#client_total_employee').val(),
			}
		}).done(function(res) {
			if (res.status) {
				$('.error-client-validation').empty()
				$('.alert-validation').hide()
				Swal.fire({
					icon: 'success',
					title: 'Updated!',
					text: 'Client updated.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				window.location.href = "<?php echo site_url('client/view/') . $client->id; ?>";
			} else {
				var html = res.data;
				$('.error-client-validation').empty()
				$('.error-client-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		});
	})

    $('#client_country').change(function() {
        if ($(this).val() == 'Singapore') {
            $('#client_postal_code').attr('type', 'number')
        } else {
            $('#client_postal_code').attr('type', 'text')
        }
    })

});
</script>
