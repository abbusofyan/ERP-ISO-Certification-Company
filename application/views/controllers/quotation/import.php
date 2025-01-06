<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Quotation</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('quotation/import'); ?>">Import</a>
                            </li>
                            <li class="breadcrumb-item">
								Create
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
                    <div class="card-body">
                        <?php echo form_open_multipart(site_url('quotation/import/'.$type), ['autocomplete' => 'off', 'id' => 'form-quotation']); ?>
                            <div class="form-group">
                                <label>file</label>
                                <input type="file" class="form-control" name="file" value="">
                            </div>
                            <button type="submit" class="btn btn-primary" name="button">Submit</button>
            			<?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
