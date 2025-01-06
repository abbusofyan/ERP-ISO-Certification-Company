<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; <?php echo date('Y'); ?><strong><a class="ml-25" href="https://www.asasg.com/" target="_blank">Advanced System Assurance Pte Ltd</a></strong></span><span class="float-md-right d-none d-md-block">Web App By:<strong><a class="ml-25" href="https://www.webimp.com.sg/" target="_blank">WEBIMP</a></strong></span></p>
</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>

<?php echo app_tail(); ?>

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>