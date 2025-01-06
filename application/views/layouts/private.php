<?php echo $yield_header; ?>

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">
    <?php echo $yield_navbar_top; ?>

    <?php echo $yield_navbar_side; ?>
    
    <?php echo $yield_alert; ?>
    
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <?php echo $yield; ?>
    </div>
    
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    
    <?php echo $yield_footer; ?>
</body>

</html>