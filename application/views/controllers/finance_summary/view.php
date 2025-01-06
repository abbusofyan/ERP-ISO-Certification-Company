<style media="screen">
	.navbar .dropdown-toggle::after{ color:black; }
</style>

<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
		<div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">View Finance Summary</h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('finance-summary'); ?>">Finance Summary</a>
	                            </li>
	                            <li class="breadcrumb-item active">View Finance Summary
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
			</div>
        </div>
    </div>
    <div class="content-body">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col">
						<p>Client Name</p>
						<b><?= $quotation->client->name ?></b>
					</div>
					<div class="col">
						<p>Address</p>
						<b><?= full_address($quotation->address) ?></b>
					</div>
					<div class="col">
						<p>Name (With Salutation)</p>
						<b><?= $quotation->contact->salutation ? ($quotation->contact->salutation . '. ' . $quotation->contact->name) : $quotation->contact->name?></b>
					</div>
					<div class="col">
						<p>Email</p>
						<b><?= $quotation->contact->email ?></b>
					</div>
					<div class="col">
						<p>Mobile</p>
						<b><?= $quotation->contact->mobile ?></b>
					</div>
				</div>
			</div>
		</div>
		<hr>

		<h3>Finance Detail</h3>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col">
						<p>Quote Type</p>
						<?php if ($quotation->type == 'ISO'): ?>
							<b><?= $quotation->certification_cycle->name ?></b>
						<?php endif; ?>
						<?php if ($quotation->type == 'Training'): ?>
							<b>Training</b>
						<?php endif; ?>
						<?php if ($quotation->type == 'Bizsafe'): ?>
							<b>Bizsafe</b>
						<?php endif; ?>
					</div>
					<?php if ($quotation->type == 'ISO'): ?>
						<?php if (in_array($quotation->certification_cycle->id, [1, 5])): ?>
							<div class="col">
								<p>Stage 1 & Stage 2 Audit Price</p>
								<b><?= money_number_format($invoices['Stage 1 & Stage 2 Audit'] ? $invoices['Stage 1 & Stage 2 Audit']->amount : '0', 0); ?></b>
							</div>
						<?php endif; ?>

						<?php if ($quotation->certification_cycle->id == 4): ?>
							<div class="col">
								<p>Stage 2 Audit</p>
								<b><?= money_number_format($invoices['Stage 2 Audit'] ? $invoices['Stage 2 Audit']->amount : '0', 0); ?></b>
							</div>
						<?php endif; ?>

						<?php if (in_array($quotation->certification_cycle->id, [1, 2, 4, 5])): ?>
							<div class="col">
								<p>1st Year Surveillance Price</p>
								<b><?= money_number_format($invoices['1st Year Surveillance'] ? $invoices['1st Year Surveillance']->amount : '0', 0) ?></b>
							</div>
						<?php endif; ?>

						<div class="col">
							<p>2nd Year Surveillance Price</p>
							<b><?= money_number_format($invoices['2nd Year Surveillance'] ? $invoices['2nd Year Surveillance']->amount : '0', 0) ?></b>
						</div>

						<div class="col">
							<p>Total Amount</p>
							<b><?= money_number_format($total_amount, 0) ?></b>
						</div>

					<?php endif; ?>
					<?php if ($quotation->type == 'Bizsafe'): ?>
						<div class="col">
							<p>Audit Fee</p>
							<b><?= money_number_format($invoices['Bizsafe']->amount, 0) ?></b>
						</div>
						<div class="col">
							<p>Total Amount</p>
							<b><?= money_number_format($total_amount, 0) ?></b>
						</div>
					<?php endif; ?>
					<?php if ($quotation->type == 'Training'): ?>
						<div class="col">
							<p>Training Fee</p>
							<b><?= money_number_format($invoices['Training']->amount, 0) ?></b>
						</div>
						<div class="col">
							<p>Total Amount</p>
							<b><?= money_number_format($total_amount, 0) ?></b>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<hr>

		<div class="d-flex justify-content-between">
			<h3 class="float-left mb-0">Invoices</h3>
			<div class="pb-1">
				<button data-toggle="modal" data-target="#generate-receipt-modal" class="btn btn-light border-danger text-primary">
					<i data-feather="file-plus" class="mr-1"></i> Generate Receipt
				</button>
			</div>
		</div>
		<section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table class="datatables-basic table datatable-detail-invoice" width="100%" data-quotation-id="<?= $quotation->id ?>" data-url="<?php echo htmlspecialchars(site_url("dt/detail_invoice")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
                                    <th data-priority="1">Invoice No</th>
                                    <th data-priority="3">Status</th>
                                    <th data-priority="4">Invoice Date</th>
                                    <th data-priority="5">Due Date</th>
									<th data-priority="6">Paid Date</th>
									<th data-priority="7">Invoice Type</th>
									<th data-priority="7">Invoice Amount</th>
                                    <th data-priority="2" width="100">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
		<hr>

		<h3>Receipts</h3>
		<section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table class="datatables-basic table datatable-receipt" width="100%" data-quotation-id="<?= $quotation->id ?>" data-url="<?php echo htmlspecialchars(site_url("dt/receipt")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
									<th data-priority="1">Id</th>
                                    <th data-priority="1">Invoice(s)</th>
                                    <th data-priority="3">Invoice Status</th>
                                    <th data-priority="4">Payment Mode</th>
                                    <th data-priority="5">Receipt Date</th>
									<th data-priority="6">Receipt Amount</th>
									<th data-priority="7">Discount</th>
									<th data-priority="8">Notes</th>
									<th data-priority="9">Status</th>
                                    <th data-priority="2" width="100">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>

		<?php include 'includes_view/note_modal.php'; ?>
		<?php include 'includes_view/detail_receipt_modal.php'; ?>
		<?php include 'includes_view/generate_receipt_modal.php'; ?>

    </div>
</div>

<script type="text/javascript">
$('.flatpickr-basic').flatpickr();
$('.field-bank-transfer').hide()
$('.field-net-cash').hide()
$('.field-cheque').hide()

$(document).ready(function () {
    if ($('.datatable-detail-invoice').length > 0) {
        let csrf     = $('.datatable-detail-invoice').data('csrf');
        let dtUrl    = $('.datatable-detail-invoice').data('url');
        let quotation_id = $('.datatable-detail-invoice').data('quotation-id');

        $('.datatable-detail-invoice')
            .DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: {
                    url: dtUrl,
                    type: 'POST',
                    data: {
                        [csrf.name]: csrf.value,
                        quotation_id: quotation_id
                    },
                },
                dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [{
                    text: feather.icons['plus'].toSvg({ class: 'mr-50 font-small-4' }) + 'Add POC',
                    className: 'create-new btn btn-primary',
                    attr: {
                        'data-toggle': 'modal',
                        'data-target': '#modals-slide-in'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }],
                columns: [
                    {
                        data: 'invoice_number',
                    },
					{
                        data: 'invoice_status',
                    },
                    {
                        data: 'invoice_date',
                    },
                    {
                        data: 'audit_fixed_date',
						render: function(data, type, row) {
							return row.due_date
						}
                    },
                    {
                        data: 'paid_date',
                    },
                    {
                        data: 'invoice_type',
                    },
					{
                        data: 'amount',
                    },
                    {
                        data: 'tools',
                        searchable: false,
                        orderable: false,
                    },
                ]
            });
    }

	if ($('.datatable-receipt').length > 0) {
        let csrf     = $('.datatable-receipt').data('csrf');
        let dtUrl    = $('.datatable-receipt').data('url');
        let quotation_id = $('.datatable-receipt').data('quotation-id');

        $('.datatable-receipt')
            .DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: {
                    url: dtUrl,
                    type: 'POST',
                    data: {
                        [csrf.name]: csrf.value,
                        quotation_id: quotation_id
                    },
                },
				order: [[0, 'desc']],
                dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [{
                    text: feather.icons['plus'].toSvg({ class: 'mr-50 font-small-4' }) + 'Add POC',
                    className: 'create-new btn btn-primary',
                    attr: {
                        'data-toggle': 'modal',
                        'data-target': '#modals-slide-in'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }],
                columns: [
					{
						data: 'id',
						visible: false
					},
					{
						data: 'combined_invoice_number',
						render: function(data, type, row) {
							return row.invoice_number
						}
					},
					{
                        data: 'combined_invoice_status',
						render: function(data, type, row) {
							return row.invoice_status
						}
                    },
					{
                        data: 'payment_method',
                    },
                    {
                        data: 'receipt_date',
                    },
                    {
                        data: 'paid_amount',
                    },
					{
                        data: 'discount',
                    },
					{
                        data: 'note',
                    },
					{
                        data: 'receipt_status',
                    },
                    {
                        data: 'tools',
                        searchable: false,
                        orderable: false,
                    },
                ]
            });
    }

	var total_amount = 0;
	$('.invoice-amount:checkbox:checked').each(function(){
		total_amount += parseInt($(this).data('invoice-amount'))
	});

	$('#total-selected-amount').text(money_number_format(total_amount, 0))

	$(document).on('click', '.invoice-amount', function() {
		var selected_amount = parseInt($(this).data('invoice-amount'))
		if ($(this).is(':checked')) {
			total_amount += selected_amount
		} else {
			total_amount -= selected_amount
		}
		$('#total-selected-amount').text(money_number_format(total_amount, 0))

		if ($('#pay-total-selected-amount').is(':checked')) {
			$('#amount-to-pay').val(total_amount)
		}
	})

	$(document).on('click', '#pay-total-selected-amount', function() {
		if ($(this).is(':checked')) {
			$('#amount-to-pay').val(total_amount)
		} else {
			$('#amount-to-pay').val('')
		}
	})

	$(document).on('click', '.view-notes', function() {
		var receiptId = $(this).attr('data-id');
		$('#note-receipt-id').val(receiptId);
		$('#note-modal').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/receipt/get_notes")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				receipt_id: receiptId,
			}
		}).done(function(data) {
			$('.notes').remove()
			data.forEach((item, i) => {
		        const html = `<div class="notes"><div class="d-flex bd-highlight">
		            <div class="pr-1 flex-shrink-1 bd-highlight">
		                <img class="img-fluid" src="<?= assets_url('img/blank-profile.png') ?>" width="50" alt="">
		            </div>
		            <div class="w-100 bd-highlight">
		                <b>`+item.user+`</b><br>
		                <p>`+item.role+`</p>
		            </div>
		        </div>
		        <span>`+item.note.replace(/\n/g, '<br>')+`<br><br>`+item.created_on+`<hr></span>
				</div>
		        `;
				$('.notes-section').append(html)
			});
		});
		$('#note-receipt-id').val(receiptId)
	})

	$(document).on('click', '.view-detail-receipt', function() {
		var receiptId = $(this).attr('data-id');
		$('#detail-receipt-modal').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/receipt/get_detail")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				receipt_id: receiptId,
			}
		}).done(function(receipt) {

			if (receipt) {
				$('.detail-receipt').text('')

				$('.payment-method').text(receipt.payment_method)
				// $('.paid-date').text(receipt.paid_date)
				if (receipt.discount) {
					$('.discount').text('-' + money_number_format(parseInt(receipt.discount), 0))
				}
				$('.paid-amount').text(money_number_format(parseInt(receipt.paid_amount), 0))
				$('.receipt-date').text(receipt.created_on)

				if (receipt.payment_method == 'Cheque') {
					$('.field-cheque-detail-receipt').show()
					$('.field-bank-transfer-detail-receipt').hide()
					$('.field-net-cash-detail-receipt').hide()
					$('.cheque-no').text(receipt.cheque_no)
					$('.bank').text(receipt.bank)
					$('.cheque-date').text(receipt.cheque_date)
					$('.paid-date').text(receipt.cheque_received_date)
				} else if (receipt.payment_method == 'Bank Transfer') {
					$('.field-bank-transfer-detail-receipt').show();
					$('.field-net-cash-detail-receipt').hide()
					$('.field-cheque-detail-receipt').hide()
					$('.paid-date').text(receipt.transfer_date)
				} else {
					$('.field-net-cash-detail-receipt').show()
					$('.field-bank-transfer-detail-receipt').hide();
					$('.field-cheque-detail-receipt').hide()
					$('.paid-date').text(receipt.received_date)
				}

				receipt.detail.forEach((detail, i) => {
					var invoice_number = '';
						invoice_number += '<p><b>'+detail.invoice_number+'</b> | '+money_number_format(parseInt(detail.paid_amount), 0)+'</p>'

					var invoice_status = '';
						invoice_status += '<p>'+invoice_status_badge(detail.invoice_status)+'</p>'

					$('.invoice-number').append(invoice_number)
					$('.invoice-status').append(invoice_status)
				});

				var note = receipt.note[receipt.note.length - 1];
				var note_html = '';
					note_html += '<h6 class="mb-0 mt-1">'+note.note+'</h6>'
					note_html += '<small>by '+note.created_by+' on '+note.created_on+'</small>'
				$('.note').append(note_html)
			}
		});
	})

	$(document).on('change', '.select-payment-method', function() {
		var payment_method = $(this).val()
		$('.additional-payment-fields').hide()
		if (payment_method == 'Bank Transfer') {
			$('.field-bank-transfer').show()
		}
		if (payment_method == 'Cash' || payment_method == 'NETs') {
			$('.field-net-cash').show()
		}
		if (payment_method == 'Cheque') {
			$('.field-cheque').show()
		}
	})

	$(document).on('click', '.btn-cancel-invoice', function() {
		var invoice_id = $(this).attr('data-id');

		Swal.fire({
            title: 'Alert!',
            text: "Are you sure to cancel this invoice?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel it!',
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
					url: <?php echo json_encode(site_url("api/invoice/cancel")); ?>,
					type: "POST",
					data: {
						<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
						invoice_id: invoice_id,
					}
				}).done(function(data) {
					Swal.fire({
						icon: 'success',
						title: 'Cancelled!',
						text: 'Invoice cancelled.',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					}).then(function (result) {
						location.reload()
					});
				});
            }
        });
	})


	$(document).on('click', '.btn-cancel-receipt', function() {
		var receipt_id = $(this).attr('data-id');

		Swal.fire({
			title: 'Alert!',
			text: "Are you sure to cancel this payment?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, cancel it!',
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
					url: <?php echo json_encode(site_url("api/receipt/cancel")); ?>,
					type: "POST",
					data: {
						<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
						receipt_id: receipt_id,
					}
				}).done(function(data) {
					Swal.fire({
						icon: 'success',
						title: 'Cancelled!',
						text: 'Payment cancelled.',
						customClass: {
							confirmButton: 'btn btn-success'
						}
					}).then(function (result) {
						location.reload()
					});
				});
			}
		});
	})

	$(document).on('click', '.btn-submit-receipt', function() {
		validateForm()
	})

	function validateForm() {
		var errors = [];

		var payment_method = $('.select-payment-method').val()
		if (!payment_method) {
			errors.push('- Please Select Payment Method<br>')
		}

		var amount_to_pay = $('#amount-to-pay').val()
		if (!amount_to_pay) {
			errors.push('- Please Enter Amount<br>')
		}

		if (payment_method == 'Bank Transfer') {
			var input = $('.field-bank-transfer :input').filter(function() { return this.value === ""; });
		} else if (payment_method == 'Cash' || payment_method == 'NETs') {
			var input = $('.field-net-cash :input').filter(function() { return this.value === ""; });
		} else if (payment_method == 'Cheque') {
			var input = $('.field-cheque :input').filter(function() { return this.value === ""; });
		}

		if (payment_method) {
			input.each(function() {
				if (this.required) {
					if ($(this).is(":visible")) {
						errors.push('- Please Enter ' + this.title + '<br>')
					}
				}
			});
		}

		if (errors.length > 0) {
			return toastr.error(errors, 'Error validation form')
		}
		$( "#form-generate-receipt" ).submit();
	}

	$(document).on('hide.bs.modal', '#generate-receipt-modal', function() {
		$('.select-payment-method').val('')
		$('.additional-payment-fields').hide()
		$('.additional-payment-fields input').val('')
		$('.generate-receipt-note').val('')
		$('#amount-to-pay').val('')
		$('#pay-total-selected-amount').prop('checked', false)
		$('#amount-to-pay').val('')
	})

});
</script>
