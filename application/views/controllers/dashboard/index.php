<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
			<div class="d-flex justify-content-between">
				<div class="pl-2">
					<div class="row breadcrumbs-top">
						<h2 class="content-header-title float-left mb-0">Dashboard</h2>
					</div>
				</div>
			</div>
        </div>
    </div>
    <div class="content-body">
    </div>
</div>


<script type="text/javascript">
$(document).ready(function () {

	var _PDF_DOC,
    _CURRENT_PAGE,
    _TOTAL_PAGES,
    _PAGE_RENDERING_IN_PROGRESS = 0,
    _CANVAS = document.querySelector('#pdf-canvas');

	async function showPDF(pdf_url) {
		document.querySelector("#pdf-loader").style.display = 'block';

		// get handle of pdf document
		try {
			_PDF_DOC = await pdfjsLib.getDocument({ url: pdf_url });
		}
		catch(error) {
			alert(error.message);
		}

		// total pages in pdf
		_TOTAL_PAGES = _PDF_DOC.numPages;

		// Hide the pdf loader and show pdf container
		document.querySelector("#pdf-loader").style.display = 'none';
		document.querySelector("#pdf-contents").style.display = 'block';
		document.querySelector("#pdf-total-pages").innerHTML = _TOTAL_PAGES;

		// show the first page
		showPage(1);
	}

	// load and render specific page of the PDF
	async function showPage(page_no) {
		_PAGE_RENDERING_IN_PROGRESS = 1;
		_CURRENT_PAGE = page_no;

		// disable Previous & Next buttons while page is being loaded
		document.querySelector("#pdf-next").disabled = true;
		document.querySelector("#pdf-prev").disabled = true;

		// while page is being rendered hide the canvas and show a loading message
		document.querySelector("#pdf-canvas").style.display = 'none';
		document.querySelector("#page-loader").style.display = 'block';

		// update current page
		document.querySelector("#pdf-current-page").innerHTML = page_no;

		// get handle of page
		try {
			var page = await _PDF_DOC.getPage(page_no);
		}
		catch(error) {
			alert(error.message);
		}

		// original width of the pdf page at scale 1
		var pdf_original_width = page.getViewport(1).width;

		// as the canvas is of a fixed width we need to adjust the scale of the viewport where page is rendered
		var scale_required = _CANVAS.width / pdf_original_width;

		// get viewport to render the page at required scale
		var viewport = page.getViewport(scale_required);

		// set canvas height same as viewport height
		_CANVAS.height = viewport.height;

		// setting page loader height for smooth experience
		document.querySelector("#page-loader").style.height =  _CANVAS.height + 'px';
		document.querySelector("#page-loader").style.lineHeight = _CANVAS.height + 'px';

		var render_context = {
			canvasContext: _CANVAS.getContext('2d'),
			viewport: viewport
		};

		// render the page contents in the canvas
		try {
			await page.render(render_context);
		}
		catch(error) {
			alert(error.message);
		}

		_PAGE_RENDERING_IN_PROGRESS = 0;

		// re-enable Previous & Next buttons
		document.querySelector("#pdf-next").disabled = false;
		document.querySelector("#pdf-prev").disabled = false;

		// show the canvas and hide the page loader
		document.querySelector("#pdf-canvas").style.display = 'block';
		document.querySelector("#page-loader").style.display = 'none';
	}

	// click on the "Previous" page button
	document.querySelector("#pdf-prev").addEventListener('click', function() {
		if(_CURRENT_PAGE != 1)
			showPage(--_CURRENT_PAGE);
	});

	// click on the "Next" page button
	document.querySelector("#pdf-next").addEventListener('click', function() {
		if(_CURRENT_PAGE != _TOTAL_PAGES)
			showPage(++_CURRENT_PAGE);
	});

	if ($('.datatable-application-form-template').length > 0) {
        let csrf = $('.datatable-application-form-template').data('csrf');
        let dtUrl = $('.datatable-application-form-template').data('url');
		var filterData = '';

        var table = $('.datatable-application-form-template')
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
						}
                    },
                },
                order: [[0, 'asc']],
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [],
                columns: [
                    {
                        data: 'name',
                    },
                    {
                        data: 'created_on',
                    },
                    {
                        data: 'tools',
                        searchable: false,
                        orderable: false,
                    },
                ]
            });

        $('div.head-label').html('<h6 class="mb-0">List of Application Form Template</h6>');
    }


	$(document).on('click', '.view-template', function() {
		var templateId = $(this).attr('data-id');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/application_form_template/get_by_id")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				template_id: templateId,
			}
		}).done(function(data) {
			showPDF(data.url);
		});
		$('#view-template-modal').modal('toggle');
	})


    // delete client
    $(document).on('click', '.delete-template', function() {
        let templateId = $(this).attr('data-id');

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
                    url: <?php echo json_encode(site_url("api/application_form_template/delete")); ?>,
                    type: "POST",
                    data: {
                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                        template_id: templateId,
                    }
                }).done(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Client has been deleted.',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    }).then(function (result) {
                        window.location.href = "<?php echo site_url('application-form-template'); ?>";
                    });
                });
            }
        });
    });


    // submit form
    var form = $("#form-client");
    var loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle">Loading...</span>';

    $("#submit").click(function (e) {
        form.unbind("submit");
        form.submit(function (e) {
            $("#submit").html(loading).prop('disabled', true);
        });
    });


	$(document).on('click', '.view-notes', function() {
		var clientId = $(this).attr('data-id');
		$('#notes-client-id').val(clientId);
		$('#modals-slide-in').modal('toggle');
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/get_notes")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: clientId,
			}
		}).done(function(data) {
			$('.notes').remove()
			data.forEach((item, i) => {
				var html = '<div class="row notes">';
					html += '<div class="col-md-1">';
					html += '<i data-feather="upload" class="mr-1"></i>';
					html += '</div>';
					html += '<div class="col-md-11">';
					html += '<p>'+item.user+' <br>'+item.role+' </p>';
					html += '</div>';
					html += '<div class="col-12">';
					html += item.note + '<br>' + item.created_on + '<hr>';
					html += '</div>';
					html += '</div>';
				$('.notes-section').append(html)
			});
		});
	})

});
</script>
