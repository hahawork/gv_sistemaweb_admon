<!DOCTYPE html><html><head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
  $('#ajax_bt').click(function() {
  $('#ajax_div').html('<img src="../dist/images/loading_setting.gif" />');
  setTimeout(function(){$('#ajax_div').load("ajax-page.php");}, 2000);
  //you can delete setTimeout function to see directly the ajax response
  //$('#ajax_div').load("ajax-page.php");
  });
});  
</script>
</head>
<body>
<form> 
<input type="button" id="ajax_bt" value="LOAD EXAMPLE" />
<div id="ajax_div" style="padding:10px; text-align: center; width: 100%; height: 100%;">
  Where the ajax content will appear after loading
</div>
</form>
</body></html>
