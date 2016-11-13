<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
	function post_data() {
		var mydata = $('#xml_data').val();
		position_url="push_alerts.php";
		$.ajax ({
			type: "POST",
			url: position_url,
			data: mydata,
			success: function(result) { 
				alert('done');
			}
		});
	}// function
</script>

<form action="" id="post_xml_form" id="post_xml_form">
    <textarea name="xml_data" id="xml_data" cols="100" rows="40"></textarea>
	<input type="button" name="submit" value="save" onclick="javascript:post_data();">
</form>
