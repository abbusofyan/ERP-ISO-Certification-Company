<input type="hidden" name="type" class="type">
<div class="form-training">
	<?php include 'includes_training/quote_detail_form.php'; ?>
	<?php include 'includes_training/training_form.php'; ?>
	<?php include 'includes_training/fee_form.php'; ?>
</div>

<script>
$('.transfer-related-field').hide()

$(document).on('keyup', '#training_referred_by', function(e) {
	var referrer = $(this).val()
	$.ajax({
		beforeSend  : function(request) {
			request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
		},
		url: <?php echo json_encode(site_url("api/referrer/get_like")); ?>,
		type: "POST",
		data: {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			name:referrer,
		}
	}).done(function(res) {
		$("#training_referred_by").autocomplete({
			source: res
		});
	})
})

$(".training-assesment_fee_attachments").on("change", function() {
  var files = Array.from(this.files)
  var fileName = files.map(f =>{return f.name}).join(", ")
	$('.training-selected-file').text(fileName)
});
// $(document).on('change', '.client_country', function() {
// 	var country = $(this).val()
// 	if(country == 'Singapore') {
// 		$('.training_assesment_fee_transportation').val('N/A')
// 	} else {
// 		$('.training_assesment_fee_transportation').val('To be paid by the Client')
// 	}
// })

</script>
