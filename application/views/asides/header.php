<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<?php $page['app_name'] = isset($page['app_name']) ? $page['app_name'] : "ASA Web App"; ?>
<?php $page['title'] = isset($page['title']) ? $page['title'] . " | " . $page['app_name'] : $page['app_name']; ?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <title><?php echo $page['title']; ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo assets_url('img/favicon.ico'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <?php echo app_head(); ?>
    
    <?php if (isset($page['scripts']) && !empty($page['scripts'])) { ?>
        <?php 
           foreach ($page['scripts'] as $script) {
               echo $script();
           } 
        ?>
    <?php } ?>
    <style>
        .select2-selection__arrow {
            display: none
        }
    </style>
</head>