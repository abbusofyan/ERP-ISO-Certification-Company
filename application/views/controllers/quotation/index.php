<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">Quotation</h2>
	                    <div class="breadcrumb-wrapper">
	                        <ol class="breadcrumb">
	                            <li class="breadcrumb-item"><a href="<?php echo site_url('quotation'); ?>">Quotation</a>
	                            </li>
	                            <li class="breadcrumb-item active">Listing
	                            </li>
	                        </ol>
	                    </div>
					</div>
				</div>
				<div class="p-0">
					<div class="dropdown">
						<?php if ($current_user['group_id'] == 1): ?>
							<button data-toggle="modal" data-target="#notification-modal" class="btn btn-light text-primary border-danger notification">
								<i data-feather="bell"></i>
								<?php if ($notifications): ?>
									<span class="badge ">.</span>
								<?php endif; ?>
							</button>
						<?php endif; ?>
						<a href="<?= site_url('quotation/create') ?>" class="btn btn-primary">
							<i data-feather="plus" class="mr-1"></i> Create New Quotation
						</a>
					</div>
				</div>
			</div>
        </div>
    </div>
	<div class="card">
		<div class="card-body">
			<div class="d-flex justify-content-between">
				<a data-status="All" class="badge-quote-status"><span class="badge badge-pill border-primary text-primary">All</span></a>
				<span>|</span>
				<?php foreach ($quotation_status as $status): ?>
                    <a class="badge-quote-status" data-status="<?= $status->name ?>"><?= quotation_status_badge($status->name) ?></a>
					<span>|</span>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
    <div class="content-body">
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table class="datatables-basic table datatable-quotation" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/quotation")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
                            <thead>
                                <tr>
                                    <th data-priority="1">Quote Number</th>
                                    <th data-priority="3">Company Name</th>
                                    <th data-priority="4">Certification Scheme</th>
	                                <th data-priority="5">Certificate Cycle</th>
                                    <th data-priority="6">Quote Date</th>
  									<th data-priority="8">Date Confirmed</th>
  									<th data-priority="9">Notes</th>
                                    <th data-priority="2" width="100">Action</th>
  									<th></th>
  									<th>contact name</th>
  									<th>contact mobile</th>
  									<th>contact phone</th>
  									<th>contact email</th>
  									<th>client uen</th>
  									<th>client phone</th>
  									<th>client email</th>
  									<th>client address</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>


		<?php include 'includes_/notification_modal.php'; ?>
		<?php include 'includes_/filter.php'; ?>
		<?php include 'includes_/note_modal.php'; ?>
		<?php include 'includes_/history_modal.php'; ?>
		<?php include 'includes_/memo_modal.php'; ?>

    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.flatpickr-basic').flatpickr();
  var topBarStatus = '';
  var selectedMonth = '';
  var selectedYear = '';

  const $selectElement = $('<select class="form-control datatable-filter-month">' +
    '<option value="">All</option>' +
    '<option value="01">January</option>' +
    '<option value="02">February</option>' +
    '<option value="03">March</option>' +
    '<option value="04">April</option>' +
    '<option value="05">May</option>' +
    '<option value="06">June</option>' +
    '<option value="07">July</option>' +
    '<option value="08">August</option>' +
    '<option value="09">September</option>' +
    '<option value="10">October</option>' +
    '<option value="11">November</option>' +
    '<option value="12">December</option>' +
  '</select>');

  const $containerElement = $('<div class="col mt-1 pr-0">' +
    '<div class="pull-right"></div>' +
  '</div>');
  $containerElement.find('.pull-right').append($selectElement);

  selectedYear = new Date().getFullYear();
  const $selectYearElement = $('<select class="form-control datatable-filter-year"></select>');
  const $optionYear = $(`<option value="">All</option>`);
  $selectYearElement.append($optionYear);

  for (let i = 2016; i <= selectedYear; i++) {
    const $optionYear = $(`<option value="${i}">${i}</option>`);
    $selectYearElement.append($optionYear);
  }

  const $containerYearElement = $('<div class="col mt-1 ml-0">' +
    '<div class="pull-right"></div>' +
  '</div>');
  $containerYearElement.find('.pull-right	').append($selectYearElement);

  function setSelectedYear() {
    selectedYear = new Date().getFullYear();
    $selectYearElement.val(selectedYear);
  }

  function setSelectedMonth() {
    const currentDate = new Date();
  	selectedMonth = currentDate.getMonth() + 1;
		selectedMonth = selectedMonth.toString().padStart(2, '0'); // Returns a number from 0 to 11
    $selectElement.val(selectedMonth);
  }

  setSelectedMonth()
  setSelectedYear()
	// setDateFilter();

	// function setDateFilter() {
	// 	$('#date_created_start').flatpickr({
	// 		"minDate": new Date(selectedYear, parseInt(selectedMonth, 10)-1, 1),
  // 		"maxDate": new Date(selectedYear, parseInt(selectedMonth, 10), 0),
	// 	})
  //
	// 	$('#date_created_end').flatpickr({
	// 		"minDate": new Date(selectedYear, parseInt(selectedMonth, 10)-1, 1),
  // 		"maxDate": new Date(selectedYear, parseInt(selectedMonth, 10), 0),
	// 	})
	// }

	let dtUrl = $('.datatable-quotation').data('url');
    if ($('.datatable-quotation').length > 0) {
        let csrf = $('.datatable-quotation').data('csrf');
		var filterData = '';
        var table = $('.datatable-quotation')
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
						month: function() {
							return selectedMonth
						},
						year: function() {
							return selectedYear
						},
                        status: function() {
                          return topBarStatus
                        }
                    },
					beforeSend: function(){
			          $('.datatable-quotation > tbody').html(
			            '<tr class="odd">' +
			              '<td valign="top" colspan="9" class="dataTables_empty">Loading&hellip;</td>' +
			            '</tr>'
			          );
			        },
                },
				"language": {
					"lengthMenu": "Show _MENU_",
		        },
                order: [[0, 'desc']],
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex bd-highlight"<"bd-highlight ml-1"l><"bd-highlight filter-month"><"bd-highlight filter-year"><"ml-auto mr-1 bd-highlight"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
				buttons: [{
					text: feather.icons['filter'].toSvg({ class: 'mr-50 font-small-4' }) + ' Filter',
					className: 'create-new btn btn-white border-primary',
					attr: {
						'data-toggle': 'modal',
						'data-target': '#filter-modal-slide-in'
					},
					init: function (api, node, config) {
						$(node).removeClass('btn-secondary');
					}
				}],
                columns: [
					{
						data: 'id',
                        render: function(data, type, row) {
							return '<p><b class="text-danger">'+row.quote_number_formatted+'</b></p>' + row.quote_status
                        }
                    },
					{
						data: 'client_name',
                        render: function(data, type, row) {
							return '<p>'+data+'</p>' + row.client_status
                        }
                    },
					{
                        data: 'certification_scheme',
                    },
					{
                        data: 'certificate_cycle',
                        // render: function(data, type, row) {
                        //     if (row.quote_type == 'Training') {
                        //         return 'Training';
                        //     } else if (row.quote_type == 'Bizsafe') {
                        //         return row.combined_certification_scheme;
                        //     } else {
                        //         return data;
                        //     }
                        // }
                    },
					{
						data: 'quote_date',
					},
					{
						data: 'confirmed_on',
					},
					{
						data: 'note',
					},
					{
                        data: 'tools',
                        searchable: false,
                        orderable: false,
						render: function(data, type, row) {
							if (row.flagged == 1) {
								return '<div class="row"><div class="toggle-flag-icon flag mr-2" data-id="'+row.id+'" data-flag="1"><img class="flag-icon img-fluid rounded" src="<?php echo assets_url('img/flag.png'); ?>" /></div>'+data+'</div>';
							} else {
								return '<div class="row"><div class="toggle-flag-icon flag mr-2" data-id="'+row.id+'" data-flag="0"><i class="flag-icon" style="color:red"></i></div>'+data+'</div>';
							}
                        }
                    },
					{
						data: 'quote_number',
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
						data: 'uen',
						visible: false
					},
					{
						data: 'client_phone',
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
						data: 'client_name_history',
						visible: false
					},
          {
						data: 'combined_certification_scheme',
						visible: false
					},
          {
						data: 'combined_accreditation',
						visible: false
					},
          {
						data: 'combined_training_type',
						visible: false
					}
                ]
            });

        $('div.head-label').html('<h6 class="mb-0">Quotation Listing</h6>');



		$("#DataTables_Table_0_wrapper .filter-month").append($containerElement);
		$("#DataTables_Table_0_wrapper .filter-year").append($containerYearElement);

    }

	$(document).on('change', '.datatable-filter-month', function() {
		selectedMonth = $(this).val()
	    // if (selectedMonth) {
	    //   setDateFilter()
	    // }
	    filterData = topBarStatus = '';
	    table.ajax.url( dtUrl ).load();
	    $('#date_created_start').val('')
	    $('#date_created_end').val('')
	})

	$(document).on('change', '.datatable-filter-year', function() {
		selectedYear = $(this).val()
	    if (selectedYear) {
	      // setDateFilter()
	      if (selectedYear == 2016) {
	        $('.datatable-filter-month option[value="01"], .datatable-filter-month option[value="02"], .datatable-filter-month option[value="03"]').hide();
	        $('.datatable-filter-month').val('04')
	        selectedMonth = '04'
	      } else {
	        $('.datatable-filter-month option[value="01"], .datatable-filter-month option[value="02"], .datatable-filter-month option[value="03"]').show();
	      }
	    }
    	filterData = topBarStatus = '';
		table.ajax.url( dtUrl ).load();
		$('#date_created_start').val('')
		$('#date_created_end').val('')
	})

	$(document).on('click', '.view-notes', function() {
		var quotationId = $(this).attr('data-id');
		$('#notes-client-id').val(quotationId);
		$('#note-modal').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/quotation/get_notes")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				quotation_id: quotationId,
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
		$('#note-quotation-id').val(quotationId)
	})


	var quotation_id_for_history = '';
	$(document).on('click', '.view-history', function() {
		$('.history-type-section').hide()
		quotation_id_for_history = $(this).attr('data-id');
		showQuotationHistory(quotation_id_for_history) // by default history popup will show quotation history
		$('#history-modal').modal('toggle');
		$('#select-history').val('quotation')
	})

	function setISOHistory(data, index) {
		var certification_scheme = '';
		data.certification_scheme_arr.forEach((item, i) => {
			var accreditation = '';
			if(data.accreditation_arr[i]) {
				accreditation = '('+data.accreditation_arr[i]+')'
			}
			certification_scheme += '- ' + item + accreditation + '<br>'
		});

		var el = $('.quotation_iso_history').children().clone()
					.find('.quote_number').html(data.number).end()
					.find('.modification_date').html(data.updated_on).end()
					.find('.modified_by').html(data.updated_by).end()
					.find('.quote_date').html(data.quote_date).end()
					.find('.quote_status').html(data.status).end()
					.find('.total_employee').html(data.address.total_employee).end()
					.find('.confirmation_date').html(data.confirmed_on).end()
					.find('.cycle').html(data.certification_cycle.name).end()
					.find('.certification_scheme').html(certification_scheme).end()
					.find('.scope').html(data.scope).end()
					.find('.num_of_sites').html(data.num_of_sites).end()
					.find('.stage_audit').html(data.stage_audit ? money_number_format(parseInt(data.stage_audit)) : '').end()
					.find('.surveillance_year_1').html(data.surveillance_year_1 ? money_number_format(parseInt(data.surveillance_year_1)) : '').end()
					.find('.surveillance_year_2').html(data.surveillance_year_2 ? money_number_format(parseInt(data.surveillance_year_2)) : '').end()
					.find('.transportation').html(data.transportation).end();

        $('.additional-col').remove()
        $('.col-stage-audit').show();
        $('.col-surveillance-year-1').show();
        $('.col-surveillance-year-2').show();

        if (data.certification_cycle.name == 'Transfer 2nd Year Surveillance') {
            $('.col-stage-audit').hide();
            $('.col-surveillance-year-1').hide();
            $('.row-fee-iso').append('<div class="col additional-col"></div><div class="col additional-col"></div>')
        }
        if (data.certification_cycle.name == 'Transfer 1st Year Surveillance') {
            $('.col-stage-audit').hide();
            $('.row-fee-iso').append('<div class="col additional-col"></div>')
        }
    	if (index != 0) {
    		el.find('.current-data-label').remove().end()
    	}
    	el.appendTo('.quotation-history-section:last');
	}

	function setBizsafeHistory(data, index) {
    	var certification_scheme = '';
		data.certification_scheme_arr.forEach((item, i) => {
			var accreditation = '';
			if(data.accreditation_arr[i]) {
				accreditation = '('+data.accreditation_arr[i]+')'
			}
			certification_scheme += '- ' + item + accreditation + '<br>'
		});
		var el = $('.quotation_bizsafe_history').children().clone()
					.find('.quote_number').html(data.number).end()
					.find('.modification_date').html(data.updated_on).end()
					.find('.modified_by').html(data.updated_by).end()
					.find('.quote_date').html(data.quote_date).end()
					.find('.quote_status').html(data.status).end()
					.find('.total_employee').html(data.address.total_employee).end()
					.find('.confirmation_date').html(data.confirmed_on).end()
					.find('.cycle').html(data.certification_cycle.name).end()
					.find('.certification_scheme').html(certification_scheme).end()
					.find('.scope').html(data.scope).end()
					.find('.num_of_sites').html(data.num_of_sites).end()
					.find('.audit_fee').html(money_number_format(parseInt(data.audit_fee))).end();
					if (index != 0) {
						el.find('.current-data-label').remove().end()
					}
					el.appendTo('.quotation-history-section:last');
	}

	function setTrainingHistory(data, index) {
	    var training_type = '';
		data.training_type_arr.forEach((item, i) => {
			training_type += '- ' + item + '<br>'
		});
		var el = $('.quotation_training_history').children().clone()
					.find('.quote_number').html(data.number).end()
					.find('.modification_date').html(data.updated_on).end()
					.find('.modified_by').html(data.updated_by).end()
					.find('.quote_date').html(data.quote_date).end()
					.find('.quote_status').html(data.status).end()
					.find('.total_employee').html(data.address.total_employee).end()
					.find('.confirmation_date').html(data.confirmed_on).end()
					.find('.invoice_to').html(data.invoice_to).end()
					.find('.training_type').html(training_type).end()
          			.find('.training_description').html(nl2br(data.training_description)).end()
					.find('.total_amount').html(money_number_format(parseInt(data.total_amount))).end()
					.find('.discount').html(money_number_format(parseInt(data.discount))).end()
					.find('.payment_terms').html(data.payment_terms).end()
					.find('.transportation').html(data.transportation).end()
					.find('.duration').html(data.duration).end();
					if (index != 0) {
						el.find('.current-data-label').remove().end()
					}
					el.appendTo('.quotation-history-section:last');
	}


	$(document).on('click', '.view-main-contact-btn', function() {
		var contactId = $(this).attr('data-contact-id');
		var quotationId = $(this).attr('data-client-id');
 		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/get_main_contact")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				contact_id: contactId,
				quotation_id: quotationId
			}
		}).done(function(data) {
			$('#main-contact-phone').text(data.main_contact.phone)
			$('#main-contact-fax').text(data.main_contact.fax)
			$('#main-contact-email').text(data.main_contact.email)
			$('#main-contact-website').text(data.main_contact.website)

			$('#main-contact-client-id').val(quotationId)

			$('.contact-options').remove();

			data.contact.forEach((item, i) => {
				var selected = '';
				if (item.id == data.main_contact.id) {
					selected = 'selected';
				}
				var html = '<option class="contact-options" '+selected+' value="'+item.id+'">'+item.name+' ( ' + item.position + ' )</option>';

				$('#main-contact-option').append(html)
			});
		});
	})


	$(document).on('click', '.flag', function() {
		var quotationId = $(this).attr('data-id');
		var flag = $(this).attr('data-flag');
		var parent = $(this);

		if (flag == 0) {
			var icon = '<img class="flag-icon img-fluid rounded" src="<?php echo assets_url('img/flag.png'); ?>" />';
			var flagged = 1;
		} else {
			var icon = '<i class="flag-icon" style="color:red;"></i>';
			var flagged = 0;
		}

 		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/quotation/flag")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				quotation_id: quotationId,
				flagged: flagged
			}
		}).done(function(data) {
			parent.children('.flag-icon').remove()
			parent.prepend(icon)
			parent.attr('data-flag', flagged)
		});
	})


	$( document ).ajaxComplete(function() {
		$('.flag-icon').html(feather.icons['flag'].toSvg());
	});


	$(".btn-filter").click(function() {
		filterData = $('.form-filter').serialize();
    $('.datatable-filter-month').val('')
    $('.datatable-filter-year').val('')
    selectedMonth = selectedYear = topBarStatus = ''
    table.ajax.url( dtUrl ).load();
	});


	$(".btn-clear-filter").click(function() {
	    topBarStatus = ''
		clearFilter()
	    setSelectedMonth()
	    setSelectedYear()
	    table.ajax.url( dtUrl ).load();
	});


	$(document).on('click', '.btn-view-memo', function() {
		var memoId = $(this).data('memo-id');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/memo/get")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				memo_id: memoId,
			}
		}).done(function(data) {
			$('#memo-message').html(data)
			$('#notification-memo-modal').modal('toggle')
		});
	})

	$(document).on('change', '#quote_type_all', function() {
		var quote_type_all = $(this).is(':checked')
		$('.quotation-type-filter').prop('checked', quote_type_all)
	})

	$(document).on('change', '.quotation-type-filter', function() {
		var values = [];
		$('.quotation-type-filter').each(function(i, obj) {
			var checked = $(obj).is(':checked')
			values.push(checked)
		});
		if (values.every(Boolean)) {
			$('#quote_type_all').prop('checked', true)
		} else {
			$('#quote_type_all').prop('checked', false)
		}
	})

	$(document).on('change', '#certification_cycle_all', function() {
		var certification_cycle_all = $(this).is(':checked')
		$('.certification-cycle-filter').prop('checked', certification_cycle_all)
	})

	$(document).on('change', '.certification-cycle-filter', function() {
		var values = [];
		$('.certification-cycle-filter').each(function(i, obj) {
			var checked = $(obj).is(':checked')
			values.push(checked)
		});
		if (values.every(Boolean)) {
			$('#certification_cycle_all').prop('checked', true)
		} else {
			$('#certification_cycle_all').prop('checked', false)
		}
	})

	$(document).on('change', '#accreditation_all', function() {
		var accreditation_all = $(this).is(':checked')
		$('.accreditation-filter').prop('checked', accreditation_all)
	})

	$(document).on('change', '.accreditation-filter', function() {
		var values = [];
		$('.accreditation-filter').each(function(i, obj) {
			var checked = $(obj).is(':checked')
			values.push(checked)
		});
		if (values.every(Boolean)) {
			$('#accreditation_all').prop('checked', true)
		} else {
			$('#accreditation_all').prop('checked', false)
		}
	})

	$(document).on('change', '#quote_status', function() {
		var quote_status_all = $(this).is(':checked')
		$('.quote-status-filter').prop('checked', quote_status_all)
	})

	$(document).on('change', '.quote-status-filter', function() {
		var values = [];
		$('.quote-status-filter').each(function(i, obj) {
			var checked = $(obj).is(':checked')
			values.push(checked)
		});
		if (values.every(Boolean)) {
			$('#quote_status').prop('checked', true)
		} else {
			$('#quote_status').prop('checked', false)
		}
	})

	$(document).on('change', '#client_status_all', function() {
		var client_status_all = $(this).is(':checked')
		$('.client-status-filter').prop('checked', client_status_all)
	})

	$(document).on('change', '.client-status-filter', function() {
		var values = [];
		$('.client-status-filter').each(function(i, obj) {
			var checked = $(obj).is(':checked')
			values.push(checked)
		});
		if (values.every(Boolean)) {
			$('#client_status_all').prop('checked', true)
		} else {
			$('#client_status_all').prop('checked', false)
		}
	})

	$(document).on('change', '#select-history', function() {
		var history_type = $(this).val()
		$('.history-type-section').hide()
		if (history_type == 'quotation') {
			showQuotationHistory(quotation_id_for_history)
		}

		if (history_type == 'client') {
			showClientHistory(quotation_id_for_history)
		}

		if (history_type == 'address') {
			showAddressHistory(quotation_id_for_history)
		}

		if (history_type == 'contact') {
			showContactHistory(quotation_id_for_history)
		}
	})

	function showQuotationHistory(quotationId) {
		$('.quotation_iso_history').hide()
		$('.quotation_bizsafe_history').hide()
		$('.quotation_training_history').hide()
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/quotation/get_history")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				quotation_id: quotationId,
			}
		}).done(function(data) {
			$('.quotation-history-section .template').remove()
			data.forEach((item, i) => {
				if (item.type == 'ISO') {
					setISOHistory(item, i)
				}

				if (item.type == 'Bizsafe') {
					setBizsafeHistory(item, i)
				}

				if (item.type == 'Training') {
					setTrainingHistory(item, i)
				}
			});
			$('.quotation-history').show()
		});
	}

	function showClientHistory(quotationId) {
		$('.client-template').hide()
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/quotation/get_client_history")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				quotation_id: quotationId,
			}
		}).done(function(data) {
			$('.client-history-section .template').remove()
			data.forEach((item, i) => {
				var el = $('.client-template').children().clone()
					.find('.uen').html(item.uen).end()
					.find('.modification_date').html(item.updated_on).end()
					.find('.modified_by').html(item.updated_by).end()
					.find('.quote_date').html(item.quote_date).end()
					.find('.client_status').html(item.status).end()
					.find('.name').html(item.name).end()
					.find('.address').html(item.address).end()
					.find('.website').html(item.website).end()
					.find('.email').html(item.email).end()
					.find('.phone').html(item.phone).end()
					.find('.fax').html(item.fax).end()
					.find('.total_employee').html(item.total_employee).end();
					if (i != 0) {
						el.find('.current-data-label').remove().end()
					}
					el.appendTo('.client-history-section:last');
			});
			$('.client-history').show()
		});
	}

	function showContactHistory(quotationId) {
		$('.contact-template').hide()
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/quotation/get_contact_history")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				quotation_id: quotationId,
			}
		}).done(function(data) {
			$('.contact-history-section .template').remove()
			data.forEach((item, i) => {
				var el = $('.contact-template').children().clone()
					.find('.modification_date').html(item.updated_on).end()
					.find('.modified_by').html(item.updated_by).end()
					.find('.contact_status').html(item.status).end()
					.find('.salutation').html(item.salutation).end()
					.find('.position').html(item.position).end()
					.find('.department').html(item.department).end()
					.find('.name').html(item.name).end()
					.find('.email').html(item.email).end()
					.find('.phone').html(item.phone).end()
					.find('.fax').html(item.fax).end()
					.find('.mobile').html(item.mobile).end();
					if (i != 0) {
						el.find('.current-data-label').remove().end()
					}
					el.appendTo('.contact-history-section:last');
			});
			$('.contact-history').show()
		});
	}

	function showAddressHistory(quotationId) {
		$('.address-template').hide()
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/quotation/get_address_history")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				quotation_id: quotationId,
			}
		}).done(function(data) {
			$('.address-history-section .template').remove()
			data.forEach((item, i) => {
				var el = $('.address-template').children().clone()
					.find('.modification_date').html(item.updated_on).end()
					.find('.modified_by').html(item.updated_by).end()
					.find('.address').html(item.address).end()
					.find('.address_2').html(item.address_2).end()
					.find('.postal_code').html(item.postal_code).end()
					.find('.country').html(item.country).end();
					if (i != 0) {
						el.find('.current-data-label').remove().end()
					}
					el.appendTo('.address-history-section:last');
			});
			$('.address-history').show()
		});
	}

  function clearFilter() {
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
		let dtUrl = $('.datatable-quotation').data('url');
		filterData = '';
  }

  $(document).on('click', '.badge-quote-status', function() {
    topBarStatus = $(this).data('status')
    clearFilter()
    if (topBarStatus) {
      $('.datatable-filter-month').val('')
      $('.datatable-filter-year').val('')
      selectedMonth = selectedYear = ''
    } else {
      setSelectedMonth()
      setSelectedYear()
    }
    table.ajax.url( dtUrl ).load();
	})

  $("#DataTables_Table_0_filter .form-control").on('keyup', function() {
    var val = $(this).val()
    topBarStatus = 'All'
    if (val) {
      $('.datatable-filter-month').val('')
      $('.datatable-filter-year').val('')
      selectedMonth = selectedYear = ''
    }

    // if (!val && !topBarStatus && !filterData) {
    //   setSelectedMonth()
    //   setSelectedYear()
    // }
  })

	$(document).on('click', '.view-memo', function() {
		var quotationId = $(this).attr('data-id');
		$('#memo-modal').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/quotation/get_memo")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				quotation_id: quotationId,
			}
		}).done(function(data) {
			$('.memos').remove()
			data.forEach((item, i) => {
				const html = `
				<div class="memos">
				  <div class="d-flex justify-content-between">
				    <div>
				      <b class="memo_number text-danger">`+item.number+`</b><br>
				      <span class="memo_date">`+item.created_on+`</span>
				    </div>
				    <a href="<?= site_url('memo/download') ?>`+item.id+`" target="_blank" class="btn-download"><i data-feather="download" class="download-memo-icon mr-1"></i></a>
				  </div>
				  <b class="memo_status">`+item.status+`</b><br>
				  <hr>
				</div>
				`;
				$('.memo-section').append(html)
				$('.download-memo-icon').replaceWith(feather.icons['download'].toSvg());
			});
		});
	})

	$('.btn-cancel-note').click(function() {
		$('.note-field').val('')
	})

    function nl2br (str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

    $(document).on('click', '.delete-sa', function() {
        if ('<?= can("delete-quotation") ?>') {
            let quotationId = $(this).attr('data-id');

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
                        url: <?php echo json_encode(site_url("api/quotation/delete")); ?>,
                        type: "POST",
                        data: {
                            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                            quotation_id: quotationId,
                        }
                    }).done(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Quotation has been deleted.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        }).then(function (result) {
                            window.location.href = "<?php echo site_url('quotation'); ?>";
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


});
</script>
