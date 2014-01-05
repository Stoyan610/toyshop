<div id='multlist'>		<!-- Кадр из мультфильма и ссылка на следующий уровень каталога  -->
	%activefilm%	<!-- Шаблон вставки данного мультфильма. -->
	%hiddenfilms%	<!-- Шаблон вставки остальных мультфильмов. -->
</div>

<div id='changer'>
	<table width='100%' cellpadding='0' cellspacing='0' border='0'>
		<colgroup>
			<col width='30px' span='1' />
			<col span='1' />
			<col width='30px' span='1' />
		</colgroup>
		<tr valign='middle'>
			<td><img id='arrowleft' class='dim' height='70px' width='30px' src='%site_url%/pictures/arrowleft.png' title='Предыдущая' alt='Назад' /></td>
			<td>
				<div id='gap'>
					<div id='toynails'>	<!-- Игрушки, соответствующие мультфильму, и ссылки на страницу игрушки  -->
						%activetoynail%	<!-- Шаблон вставки в ряд ноготка данной игрушки. -->
						%toynails%	<!-- Шаблон вставки в ряд ноготков игрушек, соответствующих мультфильму. -->
					</div>
				</div>
			</td>
			<td><img id='arrowright' class='dim' height='70px' width='30px' src='%site_url%/pictures/arrowright.png' title='Следующая' alt='Вперёд' /></td>
		</tr>
	</table>
</div>
	
<script type='text/javascript' src='%site_url%/js/changer.js'></script>

<div id='item'>
	<h1>%toyname%</h1>
	<table border='0' cellspacing='0' cellpadding='3px' align='center'>
		<tr>
			<td colspan='3'>
				<img id='big' width='400px' height='400px' src='%site_url%/pictures/toysbig/%toyname%.jpg' title='%toyname%' alt='%toyname%' />
			</td>
			<td valign='top'>
				<div id='small'>
					<img id='main' width='70px' height='70px' src='%site_url%/pictures/toyssmall/%toyname%.jpg' alt='%toyname%' onclick='change("#main")' />
					%sights%	<!-- Шаблон вставки ноготков других видов данной игрушки. -->
				</div>
			</td>
		</tr>
		<tr class='bg'>
			<td id='topleft'>Цена:</td>
			<td id='price'>%price% руб.</td>
			<td>Наличие:</td>
			<td id='topright'>%onstock% шт.</td>
		</tr>
		<tr class='bg'>
			<td></td>
			<td></td>
			<td>Срок исполнения:</td>
			<td>%howlong%</td>
		</tr>
		<tr class='bg'>
			<td>Вес:</td>
			<td>%weight% кг.</td>
			<td>Габариты:</td>
			<td>%dimentions%</td>
		</tr>
		<tr class='bg'>
			<td id='bottomleft'>Материал:</td>
			<td>%composition%</td>
			<td>Происхождение:</td>
			<td id='bottomright'>%country%</td>
		</tr>
	</table>
	<br />
	
<script type='text/javascript' src='%site_url%/js/sights.js'></script>
	
	<form name='to_cart' action='%site_url%/toyitem.php' method='get'>
		<input type='submit' name='add' value='Добавить в корзину 1 штуку' />
	</form>
	<br />