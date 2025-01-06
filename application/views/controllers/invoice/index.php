<div class="content-wrapper container-xxl p-0">
	<div class="content-header row">
		<div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">Invoice</h2>
					</div>
				</div>
				<div class="p-0">
					<div class="dropdown">
						<?php if ($current_user['group_id'] == 1): ?>
							<button data-toggle="modal" data-target="#notification-modal" class="btn btn-light border-danger text-primary notification">
								<i data-feather="bell"></i>
								<?php if ($notifications): ?>
									<span class="badge ">.</span>
								<?php endif; ?>
							</button>
						<?php endif; ?>
						<button data-toggle="modal" data-target="#filter-modal" class="btn btn-light border-danger text-primary">
							<i data-feather="filter" class="mr-1"></i> Filter
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="content-body">
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table class="datatables-basic table datatable-invoice" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/invoice")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
                                    <th data-priority="1" width="15%">Invoice No</th>
                                    <th data-priority="3" width="10%">Date</th>
                                    <th data-priority="4">Client Name</th>
                                    <th data-priority="5" width="18%">Certification Scheme</th>
									<th data-priority="6">Invoice Type</th>
									<th data-priority="7">
										<span id="toggleColumnButton">Amount</span>
									</th>
									<th data-priority="8">Notes</th>
                                    <th data-priority="2" width="5%">Action</th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
                                </tr>
                            </thead>
							<tfoot>
								<tr>
									<th data-priority="1" width="15%"></th>
                                    <th data-priority="3" width="10%"></th>
                                    <th data-priority="4"></th>
                                    <th data-priority="5" width="18%"></th>
									<th data-priority="6"></th>
									<th data-priority="7">
									</th>
									<th data-priority="8"></th>
                                    <th data-priority="2" width="5%"></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
					        </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </section>

		<?php include 'includes_/notification_modal.php'; ?>
		<?php include 'includes_/filter.php'; ?>
		<?php include 'includes_/note_modal.php'; ?>
		<?php include 'includes_/history_modal.php'; ?>

    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.flatpickr-basic').flatpickr();
	$('.select2').select2()

	let dtUrl = $('.datatable-invoice').data('url');
    if ($('.datatable-invoice').length > 0) {
        let csrf = $('.datatable-invoice').data('csrf');
		var filterData = '';
        var table = $('.datatable-invoice')
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
					beforeSend: function(){
			          $('.datatable-invoice > tbody').html(
			            '<tr class="odd">' +
			              '<td valign="top" colspan="8" class="dataTables_empty">Loading&hellip;</td>' +
			            '</tr>'
			          );
			        },
                },
				"footerCallback": function (tfoot, data, start, end, display) {
			        var api = this.api();
			        var p = api.column(5).data().reduce(function (a, b) {
			            return parseInt(a) + parseInt(b);
			        }, 0)
					$('.total-amount').remove()
			        $(api.column(5).footer()).append('<span class="hidden total-amount">'+p.toLocaleString('en-US', { style: 'currency', currency: 'USD' })+'</span>');
			    },
                order: [[0, 'desc']],
                dom: '<"head-label"><"dt-action-buttons text-right"B><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [],
                columns: [
					{
						data: 'invoice_number',
                        render: function(data, type, row) {
							return row.invoice_number_formated
                        }
                    },
					{
                        data: 'invoice_date',
                    },
					{
						data: 'client_name',
					},
					{
						data: 'certification_scheme',
					},
					{
						data: 'invoice_type',
					},
					{
						data: 'amount',
						orderable: false,
						render: function(data, type, row) {
							return '<span class="amount hidden">'+row.amount_formatted+'</span>'
                        }

					},
					{
						data: 'note'
					},
                    {
                        data: 'tools',
                        searchable: false,
                        orderable: false,
                    },
					{
						data: 'invoice_number',
						visible: false
					},
					{
						data: 'combined_certification_scheme',
						visible: false
					},
					{
						data: 'uen',
						visible: false
					},
					{
						data: 'client_email',
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
						data: 'contact_fax',
						visible: false
					},
                ]
            });

			var amount_hidden = true;
			$('#toggleColumnButton').on('click', function () {
				if (amount_hidden) {
					$('.amount').removeClass('hidden')
					$('.total-amount').removeClass('hidden')
					amount_hidden = false;
				} else {
					$('.amount').addClass('hidden')
					$('.total-amount').addClass('hidden')
					amount_hidden = true;
				}
			});
    }


    $(document).on('click', '.delete-sa', function() {
		if ('<?= can("delete-application-form") ?>') {
			let invoiceId = $(this).attr('data-id');

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
	                    url: <?php echo json_encode(site_url("api/invoice/delete")); ?>,
	                    type: "POST",
	                    data: {
	                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
	                        invoice_id: invoiceId,
	                    }
	                }).done(function() {
	                    Swal.fire({
	                        icon: 'success',
	                        title: 'Deleted!',
	                        text: 'Invoice has been deleted.',
	                        customClass: {
	                            confirmButton: 'btn btn-success'
	                        }
	                    }).then(function (result) {
	                        window.location.href = "<?php echo site_url('invoice'); ?>";
	                    });
	                });
	            }
	        });
		} else {
			Swal.fire({
				icon: 'error',
				title: 'Error!',
				text: 'Action not allowed.',
				customClass: {
					confirmButton: 'btn btn-success'
				}
			})
		}


    });


    $(document).on('click', '.edit-modal', function() {
		var id = $(this).data('id')

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/certification_scheme/get")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				certification_scheme_id: id,
			}
		}).done(function(data) {
			$('.certification_name').val(data.name)
			$('.certification_id').val(data.id)
			$('#edit-certification-scheme-form').attr('action', '<?= site_url("certification-scheme/update/") ?>'+data.id)
			$('#edit-certification-scheme-modal').modal('toggle')
		});
	})


	$(document).on('click', '.view-notes', function() {
		var invoiceId = $(this).attr('data-id');
		$('#note-invoice-id').val(invoiceId);
		$('#note-modal').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/invoice/get_notes")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				invoice_id: invoiceId,
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
		$('#note-invoice-id').val(invoiceId)
	})


	$('.invoice_history_template').hide()
	$(document).on('click', '.view-history', function() {
		var invoiceId = $(this).attr('data-id');
		$('#notes-client-id').val(invoiceId);
		$('#history-modal').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/invoice/get_history")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				invoice_id: invoiceId,
			}
		}).done(function(data) {
			const sortedData = Object.values(data).sort((a, b) => b.id - a.id);
			$('.history-section .template').remove()
			for (const key in sortedData) {
				var item = sortedData[key]
				var el = $('.invoice_history_template').children().clone()
					.find('.client_name').html(item.client.name).end()
					.find('.address').html(item.address.address).end()
					.find('.address_2').html(item.address.address_2).end()
					.find('.postal_code').html(item.address.postal_code).end()
					.find('.salutation').html(item.contact.salutation).end()
					.find('.contact_name').html(item.contact.name).end()
					.find('.contact_email').html(item.contact.email).end()
					.find('.contact_mobile').html(item.contact.mobile).end()
					.find('.invoice_status').html(item.status).end()
					.find('.audit_fixed_date').html(item.audit_fixed_date).end()
					.find('.follow_up_date').html(item.follow_up_date).end()
					.find('.amount').html(item.amount).end()
					.find('.modified_by').html(item.updated_by).end()
					.appendTo('.history-section:last');
				if (key != 0) {
					el.find('.current-data-label').remove().end()
				}
			}

			// data.forEach((item, i) => {
			// 	var el = $('.invoice_history_template').children().clone()
			// 		.find('.client_name').html(item.client.name).end()
			// 		.find('.address').html(item.address.address).end()
			// 		.find('.address_2').html(item.address.address_2).end()
			// 		.find('.postal_code').html(item.address.postal_code).end()
			// 		.find('.salutation').html(item.contact.salutation).end()
			// 		.find('.contact_name').html(item.contact.name).end()
			// 		.find('.contact_email').html(item.contact.email).end()
			// 		.find('.contact_mobile').html(item.contact.mobile).end()
			// 		.find('.invoice_status').html(item.status).end()
			// 		.find('.audit_fixed_date').html(item.audit_fixed_date).end()
			// 		.find('.follow_up_date').html(item.follow_up_date).end()
			// 		.find('.modified_by').html(item.updated_by).end()
			// 		.appendTo('.history-section:last');
			// 	if (i != 0) {
			// 		el.find('.current-data-label').remove().end()
			// 	}
			// });
		});
	})

	$(document).on('click', '.btn-decline-invoice', function() {
		var url = $(this).attr('data-url');
		$('#form-invoice-rejection-note').attr("action", url)
		$('#rejection-invoice-modal').modal('toggle');
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

	$('.btn-cancel-note').click(function() {
		$('.note').val('')
	})

});
</script>
