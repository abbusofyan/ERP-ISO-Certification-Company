<?php
    $current_user = $this->session->userdata();
    $user = $this->user_model->with('file')->with('group')->get($current_user['user_id']);
?>

<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ml-auto">
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name font-weight-bolder">
                            <?php echo $user->first_name.' '.$user->last_name; ?>
                        </span>
                        <span class="user-status">
                            <?php echo (isset($user->group)) ? ucfirst($user->group->name) : 'N/A'; ?>
                        </span>
                    </div>
                    <span class="avatar">
                        <img class="round" src="<?php echo (isset($user->file)) ? $user->file->url : assets_url('img/blank-profile.png'); ?>" alt="avatar" height="40" width="40">
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="<?php echo site_url('logout'); ?>"><i class="mr-50" data-feather="power"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
