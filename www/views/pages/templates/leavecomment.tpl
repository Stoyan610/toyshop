<br />
<h2>Отзывы о нас</h2>
<div id='comment'> <!-- Новый отзывы о компании -->
	<form name='leavecom' action='leavecomment.php' method='post'>
    <table width='100%' align='center' cellpadding='5px' cellspacing='0px' border='0'>
      <colgroup>
        <col width='250px' span='1' />
        <col span='1' />
      </colgroup>
      <tr valign='top' align='middle'>
        <td class='surname' height='30px'>Ваше имя<br /><input type='text' name='author'size='50' value='' /></td>
        <td class='cloud1' rowspan='3'><span>Ваш отзыв</span><br /><textarea name='com_content' rows='20' cols='80'></textarea></td>
      </tr>
      <tr valign='top'><td class='cloud2' height='30px'><input type='hidden' name='issuedate' value='%issuedate%' /></td></tr>
      <tr><td align="center"><input type='image' name='new_comment' src='%site_url%pictures/comment_button.png' height='70' width='210' /></td></tr>
      <tr><td id="instr" colspan='2'><div><p>Мы оставляем за собой право исправить орфографические и пунктуационные ошибки, после чего Ваш отзыв будет опубликован на сайте</p></div><div><p>Отзывы, содержащие нецензурные выражения и криминальный слэнг, опубликовываться не будут.</p><p>Просим понять и не обижаться</p></div></td></tr>
    </table>
  </form>
</div>