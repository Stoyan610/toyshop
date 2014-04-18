<h2>Редактированите приоритетов или удаление картинок данной игрушки</h2>
<h3><a href='product.php?act=addimg&id=%productid%'>Добавить изображение</a></h3>
<form name='change' action='imgchtoy.php' method='post'>
  %imglist%
  <br /><br />
  <input type='hidden' name='count' value='%count%' />
  <input type='submit' name='changing' value='Подтверждаю изменения' />
</form><br />
<form name='cancel' action='imgchtoy.php' method='post'>
  <input type='submit' name='cancel' value='Отмена' />
</form>
