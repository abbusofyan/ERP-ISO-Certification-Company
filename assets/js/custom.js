function money_number_format(number, decimals = 2) {
    return '$ ' + number.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function invoice_status_badge(status) {
    var badgeStyle = {
        'New': 'bg-light text-success border-success',
        'Due': 'bg-light text-dark-blue border-dark-blue',
        'Late': 'bg-white border-orange text-orange',
        'Partially Paid': 'bg-blue',
        'Paid': 'bg-orange',
        'Cancelled': 'bg-red',
        'Draft': 'bg-grey border-dark text-dark'
    };

    return '<span class="badge badge-pill ' + badgeStyle[status] + '">' + status + '</span>';
}

$(document).ready(function() {
  // this is for the browser that doesnt support type number
  $(document).on('keypress', 'input[type="number"]', function(evt) {
	  var ASCIICode = (evt.which) ? evt.which : evt.keyCode;
	  if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) {
		  return false;
	  }
  })

  // to prevent user to paste non numeric string into number only field
  $('input[type="number"]').on('paste', function(evt) {
    evt.preventDefault();
    var clipboardText = (evt.originalEvent || evt).clipboardData.getData('text');
    var numericText = clipboardText.replace(/[^0-9]/g, '');
    $(this).val(numericText);
	});
})
