<br />
<h2>Отзывы о нас</h2>
<div id='comment'> <!-- Отзывы о компании. Добавляются в базу данных и берутся оттуда -->
	<table width='100%' align='center' cellpadding='5px' cellspacing='0px' border='0'>
		<colgroup>
			<col width='100px' span='1' />
			<col span='1' />
		</colgroup>
		<tr><td colspan='2' align='right'>
			<form name='lvc' action='leavecomment.php' method='post'><button name='send'>Оставить свой отзыв.</button></form>
		</td></tr>
		<tr><td></td><td></td></tr>
		%commenttext% 		<!-- Шаблон добавления рядов в таблицу с комментариями. -->
	</table>
</div>