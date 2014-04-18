<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>%title_of_admin%</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<link rel='stylesheet' type='text/css' href='%admin_url%styles/general.css' />
<link rel='stylesheet' type='text/css' href='%admin_url%styles/jquery-ui-1.10.4.custom.min.css' />
<link rel='stylesheet' type='text/css' href='%admin_url%styles/elrte.min.css' media='screen' charset='utf-8'>
<script type='text/javascript' src='%admin_url%js/jquery-1.10.2.min.js'></script>
<script type='text/javascript' src='%admin_url%js/jquery-ui-1.10.4.custom.min.js'></script>
<script type='text/javascript' src='%admin_url%js/jquery-1.6.1.min.js' charset='utf-8'></script>
<script type='text/javascript' src='%admin_url%js/jquery-ui-1.8.13.custom.min.js' charset='utf-8'></script>
<script type='text/javascript' src='%admin_url%js/pick.js'></script>
<script type='text/javascript' src='%admin_url%js/misc.js'></script>
<script type='text/javascript' src='%admin_url%js/elrte.min.js' charset='utf-8'></script>
<script type='text/javascript' src='%admin_url%js/i18n/elrte.ru.js' charset='utf-8'></script>
<script type='text/javascript'>
	$(function(){
    $("#pick").datepicker();
  });
</script>
<script type='text/javascript' charset='utf-8'>
  $().ready(function() {
    var opts = {
      cssClass : 'el-rte',
      lang     : 'ru',
      height   : 250,
      toolbar  : 'complete',
      cssfiles : ['css/elrte-inner.css']
    }
    $('#editor').elrte(opts);
  });
</script>
</head>
<body>

  <ul id='mainul'>
    %list_item%
  </ul>
  