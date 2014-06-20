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
			<td><img id='arrowleft' class='dim' height='70px' width='30px' src='%site_url%pictures/arrowleft.png' title='Предыдущая' alt='Назад' /></td>
			<td>
				<div id='gap'>
					<div id='toynails'>	<!-- Игрушки, соответствующие мультфильму, и ссылки на страницу игрушки  -->
						%activetoynail%	<!-- Шаблон вставки в ряд ноготка данной игрушки. -->
						%toynails%	<!-- Шаблон вставки в ряд ноготков игрушек, соответствующих мультфильму. -->
					</div>
				</div>
			</td>
			<td><img id='arrowright' class='dim' height='70px' width='30px' src='%site_url%pictures/arrowright.png' title='Следующая' alt='Вперёд' /></td>
		</tr>
	</table>
</div>
	
<script type='text/javascript' src='%site_url%js/changer.js'></script>
<script type='text/javascript' src='%site_url%js/sights.js'></script>

<div id='item'>
	<h1>%toyname%</h1>
	<table border='0' cellspacing='0' cellpadding='3px' align='center'>
		<tr>
			<td colspan='3'>
				<img id='big' width='%width%px' height='%height%px' src='%site_url%pictures/toy400x400/%toyfilename%.jpg' title='%toyname%' alt='%toyname%' />
			</td>
			<td valign='top'>
				<div id='small'>
					<img id='main' width='70px' height='70px' src='%site_url%pictures/toy70x70/%toyfilename%.jpg' alt='%toyname%' onclick='change("#main")' />
					%sights%	<!-- Шаблон вставки ноготков других видов данной игрушки. -->
				</div>
			</td>
		</tr>
		<tr class='bg'>
			<td id='topleft'>Цена:</td>
			<td id='price'>%price% руб</td>
			<td>Наличие:</td>
			<td id='topright'>%onstock% шт</td>
		</tr>
		<tr class='bg'>
			<td></td>
			<td></td>
			<td>Срок исполнения:</td>
			<td>%howlong%</td>
		</tr>
		<tr class='bg'>
			<td>Вес:</td>
			<td>%weight% кг</td>
			<td>Габариты:</td>
			<td>%dimension%</td>
		</tr>
		<tr class='bg'>
			<td id='bottomleft'>Материал:</td>
			<td>%composition%</td>
			<td>Происхождение:</td>
			<td id='bottomright'>%country%</td>
		</tr>
    <tr>
      <td colspan='2'>
        <form name='to_cart' action='#' method='post'>
          <input type='hidden' name='page' value='toyitem' />
          <input type='hidden' name='toyid' value='%toyid%' />
          <input type='hidden' name='toyname' value='%toyname%' />
          <input type='hidden' name='toyprice' value='%price%' />
          <input type='image' src='%site_url%pictures/add_button.png' name='add' />
        </form>
      </td>
