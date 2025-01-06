<?php if (!empty($alert)) { ?>
    <script type="text/javascript">
    $(document).ready(function() {
        toastr['<?php echo $alert['status']; ?>'](<?php echo json_encode($alert['description']); ?>, '<?php echo ucfirst($alert['status']); ?>!');
    });
    </script>
<?php } ?>