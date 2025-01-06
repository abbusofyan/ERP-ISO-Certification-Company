<div class="content-wrapper container-xxl p-0">
	<div class="content-header row">
		<div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">Finance Summary</h2>
					</div>
				</div>
				<div class="p-0">
					<button data-toggle="modal" data-target="#filter-modal" class="btn btn-light border-danger text-primary">
						<i data-feather="filter" class="mr-1"></i> Filter
					</button>
				</div>
			</div>
		</div>
	</div>
    <div class="content-body">
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table class="datatables-basic table datatable-summary" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/finance_summary")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
									<th></th>
                                    <th>Client Name</th>
                                    <th>Quote No</th>
                                    <th>Invoice(s)</th>
                                    <th>Invoice Type</th>
									<th>Date Created</th>
									<th>Status</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include 'includes_/generate_receipt_modal.php'; ?>
<?php include 'includes_/filter.php'; ?>
<?php include 'includes_/history_modal.php'; ?>

<script type="text/javascript">
$(document).ready(function () {
	$('.flatpickr-basic').flatpickr();
	$('.select2').select2()
	$('.field-bank-transfer').hide()
	$('.field-net-cash').hide()
	$('.field-cheque').hide()

	let dtUrl = $('.datatable-summary').data('url');
    if ($('.datatable-summary').length > 0) {
        let csrf = $('.datatable-summary').data('csrf');
		var filterData = '';
        var table = $('.datatable-summary')
            .DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
				"initComplete": function(){
					const website_icon = $('.website-icon');
					website_icon.html(feather.icons['external-link'].toSvg());

					const contact_icon = $('.contact-icon');
					contact_icon.html(feather.icons['eye'].toSvg());

					const flag_icon = $('.flag-icon');
					flag_icon.html(feather.icons['flag'].toSvg());
				},
                ajax: {
                    url: dtUrl,
                    type: 'POST',
						data: {
                        [csrf.name]: csrf.value,
						filter: function() {
							return filterData
						},
                    },
                },
                order: [[0, 'desc']],
                dom: '<"head-label"><"dt-action-buttons text-right"B><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [],
                columns: [
					{
						data: 'invoice_id',
						searchable: false,
						visible: false
					},
					{
						data: 'client_name',
						render: function(data, type, row) {
							return row.client_name_formatted
						}
					},
					{
						data: 'quote_number',
						render: function(data, type, row) {
							return '<p><b class="text-danger">'+data+'</b></p>' + row.quote_status
						}
					},
					{
						data: 'combined_invoice_number',
						render: function(data, type, row) {
							return row.invoice_number
						}
					},
					{
						data: 'invoice_type'
					},
					{
						data: 'combined_invoice_date_created',
						render: function(data, type, row) {
							return row.date_created_formatted
						}
					},
					{
						data: 'combined_invoice_status',
						render: function(data, type, row) {
							return row.invoice_status_formatted
						}
					},
                    {
                        data: 'action',
                        searchable: false,
                        orderable: false,
                    },
					{
						data: 'combined_certification_scheme',
						visible: false
					},
					{
						data: 'combined_invoice_amount',
						visible: false
					},
					{
						data: 'contact_name',
						visible: false
					},
					{
						data: 'contact_mobile',
						visible: false
					},
					{
						data: 'contact_phone',
						visible: false
					},
					{
						data: 'contact_email',
						visible: false
					},
					{
						data: 'client_uen',
						visible: false
					},
					{
						data: 'client_phone',
						visible: false
					},
					{
						data: 'client_fax',
						visible: false
					},
					{
						data: 'client_email',
						visible: false
					},
					{
						data: 'address',
						visible: false
					},
					{
						data: 'address_2',
						visible: false
					},
					{
						data: 'country',
						visible: false
					},
					{
						data: 'postal_code',
						visible: false
					},
					{
						data: 'combined_invoice_number',
						visible: false
					},
                ]
            });
    }

	var total_amount = 0;
	$(document).on('click', '.generate-receipt', function() {
		var quotationId = $(this).attr('data-id');
		var url = $(this).attr('data-url');

		$('#generate-receipt-modal').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/quotation/get_invoices")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				quotation_id: quotationId,
			}
		}).done(function(response) {
			var invoices = response.data.invoices
			$('.select-invoice').remove()
			$('.payment-method').val('')
			$('#amount-to-pay').val('')

			$('.additional-payment-fields').hide()

			$('.additional-payment-fields input').val('')

			$('.generate-receipt-note')

			total_amount = 0;
			$.each(invoices, function(key, invoice) {
		      if (invoice.status !== 'Paid') {
		        var div = $("<div>").addClass("form-group select-invoice");

		        var customControl = $("<div>").addClass("custom-control custom-checkbox");

		        var checkbox = $("<input>")
		          .attr({
		            type: "checkbox",
		            class: "custom-control-input invoice-amount",
		            id: "invoice-amount-" + key,
		            name: "invoice_id[]",
		            "data-invoice-amount": invoice.amount-invoice.paid,
		            value: invoice.id,
		            checked: true
		          });

		        var label = $("<label>")
		          .addClass("custom-control-label")
		          .attr("for", "invoice-amount-" + key)
		          .html(
		            invoice.number +
		              " | " +
		              money_number_format(invoice.amount - invoice.paid, 0) +
		              " | " +
		              invoice_status_badge(invoice.status)
		          );
		        customControl.append(checkbox, label);
		        div.append(customControl);

		        $("#invoices-container").append(div);

                total_amount += parseInt(invoice.amount-invoice.paid)
		      }
		    });

			$('#total-selected-amount').text(money_number_format(total_amount, 0))

			$('#form-generate-receipt').attr('action', url)
		});
	})


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

	$('.finance_summary_history_template').hide()
	$(document).on('click', '.view-history', function() {
		var quotationId = $(this).attr('data-id');
		$('#history-modal').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/finance_summary/get_history")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				quotation_id: quotationId,
			}
		}).done(function(data) {
			$('.history-section .template').remove()
			data.forEach((item, i) => {
				var el = $('.finance_summary_history_template').children().clone()
					.find('.client_name').html(item.client_name).end()
					.find('.invoice_number').html(item.invoice_number).end()
					.find('.invoice_date').html(item.invoice_date).end()
					.find('.invoice_type').html(item.invoice_type).end()
					.find('.invoice_amount').html(item.invoice_amount).end()
					.find('.invoice_status').html(item.invoice_status).end()
					.find('.paid_date').html(item.paid_date).end()
					.find('.payment_mode').html(item.payment_method).end()
					.find('.receipt_date').html(item.receipt_date).end()
					.find('.receipt_amount').html(item.receipt_amount).end()
					.find('.modified_by').html(item.modified_by).end()
					.find('.receipt_status').html(item.receipt_status).end()
					.appendTo('.history-section:last');
				if (i != 0) {
					el.find('.current-data-label').remove().end()
				}
			});
		});
	})

	$(".btn-filter").click(function() {
		filterData = $('.form-filter').serialize();
	    table.ajax.url( dtUrl ).load();
	});

	$(document).on('change', '#invoice-status-all', function() {
		var invoice_status_all = $(this).is(':checked')
		$('.filter-invoice-status').prop('checked', invoice_status_all)
	})

	$(document).on('change', '.filter-invoice-status', function() {
		var values = [];
		$('.filter-invoice-status').each(function(i, obj) {
			var checked = $(obj).is(':checked')
			values.push(checked)
		});
		if (values.every(Boolean)) {
			$('#invoice-status-all').prop('checked', true)
		} else {
			$('#invoice-status-all').prop('checked', false)
		}
	})

	$(document).on('change', '#certification-cycle-all', function() {
		var certification_cycle_all = $(this).is(':checked')
		$('.filter-certification-cycle').prop('checked', certification_cycle_all)
	})

	$(document).on('change', '.filter-certification-cycle', function() {
		var values = [];
		$('.filter-certification-cycle').each(function(i, obj) {
			var checked = $(obj).is(':checked')
			values.push(checked)
		});
		if (values.every(Boolean)) {
			$('#certification-cycle-all').prop('checked', true)
		} else {
			$('#certification-cycle-all').prop('checked', false)
		}
	})

	$(".btn-clear-filter").click(function() {
		$('.select2').val([]).change();
		$('input').each(function(index,data) {
		   var value = $(this).val();
		   var type = $(this).attr('type');
			switch (type) {
				case 'checkbox':
					$(this).prop('checked', false)
					break;
				case 'text':
					$(this).val('')
					break;
				default:
			}
		});
		filterData = '';
	    table.ajax.url( dtUrl ).load();
	});

	$(document).on('change', '.payment-method', function() {
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

	$(document).on('click', '.btn-submit-receipt', function() {
		validateForm()
	})

	function validateForm() {
		var errors = [];

		var payment_method = $('.payment-method').val()
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

});
</script>
