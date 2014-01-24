<h2>Игрушки - герои мультфильма "Мадагаскар"</h2>
<div id='multlist'>		<!-- Кадр из мультфильма и ссылка на следующий уровень каталога  -->
	%activefilm%	<!-- Шаблон вставки данного мультфильма. -->
	%hiddenfilms%	<!-- Шаблон вставки остальных мультфильмов. -->
</div>

<div id='multtoys'>
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
							%toynails%	<!-- Шаблон вставки в ряд ноготков игрушек, соответствующих мультфильму. -->
						</div>
					</div>
				</td>
				<td><img id='arrowright' class='dim' height='70px' width='30px' src='%site_url%/pictures/arrowright.png' title='Следующая' alt='Вперёд' /></td>
			</tr>
		</table>
	</div>
	
<script type='text/javascript' src='%site_url%/js/changer.js'></script>
	
	<div id='toylist'>	<!-- Игрушки, соответствующие мультфильму, и ссылки на страницу игрушки  -->
		%toysoffilm%	<!-- Шаблон вставки игрушек, соответствующих мультфильму. -->
	</div>	
</div>