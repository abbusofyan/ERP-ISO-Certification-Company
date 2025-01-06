<?php
    $uri = explode('/', $this->uri->uri_string());
    $uri[1] = (isset($uri[1])) ? $uri[1] : '';
?>

<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="#">
                    <span class="brand-logo">
                        <img class="img-fluid" width="120px" src="<?php echo assets_url('img/logo.png'); ?>" alt="Logo" />
                    </span>
                    <h2 class="brand-text">ASA</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
			<li class="<?php echo ($uri[0] == 'dashboard' || $uri[0] == '') ? 'active-menu' : ''; ?> nav-item">
				<a class="d-flex align-items-center" href="<?php echo site_url('dashboard'); ?>"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span></a>
			</li>

			<?php if (can('read-client')): ?>
				<li class="mr-0  <?php echo ($uri[0] == 'client') ? 'active-menu' : ''; ?> nav-item">
					<a class="d-flex align-items-center" href="<?php echo site_url('client'); ?>"><i data-feather="briefcase"></i><span class="menu-title text-truncate" data-i18n="Client">Client</span></a>
				</li>
			<?php endif; ?>

			<?php if (can(['read-application-form', 'read-application-form-template'])): ?>
				<li class="<?php echo ($uri[0] == 'application-form') || $uri[0] == 'application-form-template' ? 'open' : ''; ?> nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="folder-plus"></i><span class="menu-title text-truncate" data-i18n="job">Application Form</span></a>
					<ul class="menu-content">
						<?php if(can('read-application-form')) : ?>
							<li class="mr-0 <?= $uri[0] == 'application-form' ? 'active-menu' : ''; ?>"><a class="d-flex align-items-center" href="<?php echo site_url('application-form'); ?>"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Listing">All</span></a></li>
						 <?php endif; ?>

						 <?php if(can('read-application-form-template')) : ?>
							<li class="mr-0 <?= $uri[0] == 'application-form-template' ? 'active-menu' : ''; ?>"><a class="d-flex align-items-center" href="<?php echo site_url('application-form-template'); ?>"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Listing">Template</span></a></li>
						 <?php endif; ?>
					</ul>
				</li>
			<?php endif; ?>

			<?php if (can(['read-quotation', 'read-certification-scheme', 'read-accreditation'])): ?>
				<li class="<?php echo ($uri[0] == 'quotation' || $uri[0] == 'certification-scheme' || $uri[0] == 'accreditation') ? 'open' : ''; ?> nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="file"></i><span class="menu-title text-truncate" data-i18n="job">Quotation</span></a>
					<ul class="menu-content">
						<li class="mr-0 <?= $uri[0] == 'quotation' ? 'active-menu' : ''; ?>"><a class="d-flex align-items-center" href="<?php echo site_url('quotation'); ?>"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Listing">All</span></a>
						</li>
						<?php if (can('read-certification-scheme')): ?>
							<li class="mr-0 <?= $uri[0] == 'certification-scheme' ? 'active-menu' : ''; ?>"><a class="d-flex align-items-center" href="<?php echo site_url('certification-scheme'); ?>"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Listing">Certification Scheme</span></a>
							</li>
						<?php endif; ?>
						<?php if (can('read-accreditation')): ?>
							<li class="mr-0 <?= $uri[0] == 'accreditation' ? 'active-menu' : ''; ?>"><a class="d-flex align-items-center" href="<?php echo site_url('accreditation'); ?>"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Preview">Accreditation</span></a>
							</li>
						<?php endif; ?>
					</ul>
				</li>
			<?php endif; ?>

			<?php if (can(['read-invoice', 'read-finance-summary'])): ?>
        		<li class="<?php echo ($uri[0] == 'invoice' || $uri[0] == 'finance-summary') ? 'open' : ''; ?> nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="dollar-sign"></i><span class="menu-title text-truncate" data-i18n="job">Invoice</span></a>
					<ul class="menu-content">
						<?php if (can('read-invoice')): ?>
							<li class="mr-0 <?php echo ($uri[0] == 'invoice' && $uri[1] != 'finance-summary') ? 'active-menu text-primary' : ''; ?>"><a class="d-flex align-items-center" href="<?= site_url('invoice') ?>"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Listing">All</span></a>
							</li>
						<?php endif; ?>
						<?php if (can('read-finance-summary')): ?>
							<li class="mr-0 <?php echo ($uri[0] == 'finance-summary') ? 'active-menu' : ''; ?>"><a class="d-flex align-items-center" href="<?= site_url('finance-summary') ?>"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Preview">Finance Summary</span></a>
							</li>
						<?php endif; ?>
					</ul>
  				</li>
      		<?php endif; ?>

			<?php if (can('read-user')): ?>
				<li class="<?php echo ($uri[0] == 'user') ? 'open' : ''; ?> nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="user"></i><span class="menu-title text-truncate" data-i18n="job">User Management</span></a>
					<ul class="menu-content">
						<li class="mr-0 <?php echo ($uri[0] == 'user' && $uri[1] != 'role') ? 'active-menu' : ''; ?>"><a class="d-flex align-items-center" href="<?php echo site_url('user'); ?>"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Listing">User</span></a>
						</li>
						<li class="mr-0 <?php echo ($uri[0] == 'user' && $uri[1] == 'role') ? 'active-menu' : ''; ?>"><a class="d-flex align-items-center" href="<?php echo site_url('user/role'); ?>"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Preview">Role</span></a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
        </ul>
    </div>
</div>
