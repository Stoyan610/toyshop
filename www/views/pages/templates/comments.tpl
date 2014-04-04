<br />
<h2>Отзывы о нас</h2>
<div id='comment'> <!-- Отзывы о компании. Добавляются в базу данных и берутся оттуда -->
	<table width='100%' align='center' cellpadding='5px' cellspacing='0px' border='0'>
		<colgroup>
			<col width='100px' span='1' />
			<col span='1' />
		</colgroup>
		<tr><td colspan='2'></td></tr>
		%commenttext% 		<!-- Шаблон добавления рядов в таблицу с комментариями. -->
  </table>
  <form style='text-align: right;' name='lvc' action='#' method='get'>
    <input type='hidden' name='page' value='comments' />
    <input class='dim' type='image' name='comment' src='%site_url%pictures/comment_button.png' height='70' width='210' />
  </form>
</div>