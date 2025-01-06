<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">User</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('user'); ?>">User</a>
                            </li>
                            <li class="breadcrumb-item active">Details
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
                    <div class="card-header border-bottom">
                        <h4 class="card-title">User Information</h4>
                    </div>
                    <div class="card-body">
                        <br>
                        <div class="row">
                            <div class="col-xl-6 col-lg-12 d-flex flex-column justify-content-between border-container-lg">
                                <div class="user-avatar-section">
                                    <div class="d-flex justify-content-start">
                                        <img class="img-fluid rounded" src="<?php echo (isset($user->file)) ? $user->file->url : assets_url('img/blank-profile.png'); ?>" width="120" alt="User profile" />
                                        <div class="d-flex flex-column ml-1">
                                            <div class="d-flex align-items-center user-total-numbers">
                                                <div class="user-info mb-1">
                                                    <h4 class="mb-0"><?php echo $user->first_name.' '.$user->last_name; ?></h4>
                                                    <br>
                                                    <div class="d-flex align-items-center mr-2">
                                                        <div class="color-box bg-light-primary">
                                                            <i data-feather="file-text" class="text-primary"></i>
                                                        </div>
                                                        <div class="ml-1">
                                                            <h5 class="mb-0">0</h5>
                                                            <small>Jobs Done</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap">
                                                <a href="<?php echo site_url('user') . $user->id . '/form'; ?>" class="btn btn-primary">Edit</a>
                                                <button class="btn btn-outline-danger ml-1 delete-sa" data-id="<?php echo $user->id; ?>">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-12 mt-2 mt-xl-0">
                                <div class="user-info-wrapper">
                                    <div class="d-flex flex-wrap">
                                        <div class="user-info-title text-primary">
                                            <i data-feather="user" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Name</span>
                                        </div>
                                        <p class="card-text mb-0"><?php echo $user->first_name.' '.$user->last_name; ?></p>
                                    </div>
                                    <div class="d-flex flex-wrap my-50">
                                        <div class="user-info-title text-primary">
                                            <i data-feather="mail" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Email</span>
                                        </div>
                                        <p class="card-text mb-0"><?php echo $user->email; ?></p>
                                    </div>
                                    <div class="d-flex flex-wrap my-50">
                                        <div class="user-info-title text-primary">
                                            <i data-feather="star" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Role</span>
                                        </div>
                                        <p class="card-text mb-0"><?php echo $user->group->description; ?></p>
                                    </div>
                                    <div class="d-flex flex-wrap">
                                        <div class="user-info-title text-primary">
                                            <i data-feather="phone" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Contact</span>
                                        </div>
                                        <p class="card-text mb-0"><?php echo $user->contact; ?></p>
                                    </div>
                                    <div class="d-flex flex-wrap my-50">
                                        <div class="user-info-title text-primary">
                                            <i data-feather="flag" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Status</span>
                                        </div>
                                        <p class="card-text mb-0"><?php echo $user->status; ?></p>
                                    </div>
                                    <div class="d-flex flex-wrap my-50">
                                        <div class="user-info-title text-primary">
                                            <i data-feather="log-in" class="mr-1"></i>
                                            <span class="card-text user-info-title font-weight-bold mb-0">Last Login</span>
                                        </div>
                                        <p class="card-text mb-0"><?php echo ($user->last_login) ? $user->last_login : '-'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <table class="datatables-basic table datatable-user-attachment" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/user_attachment")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>" data-user-id="<?php echo $user->id; ?>">
                        <thead>
                            <tr>
                                <th data-priority="1">Date</th>
                                <th data-priority="3">File Attached</th>
                                <th data-priority="2" width="100"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal modal-slide-in fade" id="modals-slide-in">
            <div class="modal-dialog sidebar-sm">
                <?php echo form_open_multipart($form['action'], ['autocomplete' => 'off', 'id' => 'form-attachment', 'class' => 'modal-content pt-0']); ?>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title">Upload Attachment File</h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <div class="form-group">
                            <label class="form-label" for="name">File Name <span class="text-danger">*</span></label>
                            <?php echo form_input($form['name']); ?>
                        </div>
                        <div class="form-group">
                            <label for="basicInputFile">Attachment File <span class="text-danger">*</span></label>
                            <?php echo form_input($form['attachment']); ?>
                        </div>
                        <button type="submit" id="submit" form="form-attachment" class="btn btn-primary data-submit mr-1">Upload</button>
                        <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    if ($('.datatable-user-attachment').length > 0) {
        let csrf   = $('.datatable-user-attachment').data('csrf');
        let dtUrl  = $('.datatable-user-attachment').data('url');
        let userId = $('.datatable-user-attachment').data('user-id');

        $('.datatable-user-attachment')
            .DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: {
                    url: dtUrl,
                    type: 'POST',
                    data: {
                        [csrf.name]: csrf.value,
                        user_id: userId
                    },
                },
                order: [[0, 'asc']],
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
                displayLength: 10,
                buttons: [{
                    text: feather.icons['plus'].toSvg({ class: 'mr-50 font-small-4' }) + 'Upload Attachment',
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
                        data: 'created_on',
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'tools',
                        searchable: false,
                        orderable: false,
                    },
                ]
            });

        $('div.head-label').html('<h6 class="mb-0">Attachment</h6>');
    }


    // delete user
    var deleteUser = $('.delete-sa');
    var curUserID  = <?php echo $this->session->userdata()['user_id']; ?>;

    deleteUser.on('click', function () {
        let userId = $(this).attr('data-id');

        if (userId == curUserID) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Not allowed to delete your own account!',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        } else {
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
                        url: <?php echo json_encode(site_url("api/user/delete")); ?>,
                        type: "POST",
                        data: {
                            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                            user_id: userId,
                        }
                    }).done(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'User has been deleted.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        }).then(function (result) {
                            // redirect to user listing page
                            window.location.href = "<?php echo site_url('user'); ?>";
                        });
                    });
                }
            });
        }
    });


    // submit form
    var form = $("#form-attachment");
    var loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle">Loading...</span>';

    $("#submit").click(function (e) {
        form.unbind("submit");
        form.submit(function (e) {
            $("#submit").html(loading).prop('disabled', true);
        });
    });
});
</script>
