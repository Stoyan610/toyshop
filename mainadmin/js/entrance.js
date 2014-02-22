function entrance() {
  
  if ($("form").attr('name') != null) return;
  
  var permit = "<form name='admin' action='admin_controller.php' method='post'><table cellpadding='0' cellspacing='0' border='1'><tr><td>Login: </td><td><input type='text' name='log' value='' /></td></tr><tr><td>Password: </td><td><input type='password' name='pass' value='' /></td></tr><tr align='right'><td colspan='2'><input type='submit' name='send' value='ENTER' /></td></tr></table></form>";
  $('#emp').after(permit);
}