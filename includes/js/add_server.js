<script>
function testServer(){
	$('#response').html('<img src="includes/img/ajax-loader.gif" />');
	var ip = $('input[name=ip]').val();
	var port = $('input[name=port]').val();
	$.post('check_server.php', { ip: ip, port:port }, function(data) {
		$('#response').html(data).fadeIn();
	})
}
$(document).ready(function(){
	$('#votifierCheck').on('change', function(){
		 if ($('#votifierCheck').prop('checked')) {
			 $('#votifier').fadeIn();
		 } else {
			 $('#votifier').fadeOut();
		 }
	});
});
</script>