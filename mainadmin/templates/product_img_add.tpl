<h2>Добавление картинок к данной игрушке</h2>
<form name='change' action='imgchtoy.php' method='post'>
  <div id='toys'>
    %imglist%
  </div><br />
  <input type='text' name='Priority' value='0' size='5' /> - Приоритет<br /><br />
  <input type='hidden' name='toy_id' value='%toyid%' />
  <input id='imageid' type='hidden' name='toyimg_id' value='' />
  <input type='submit' name='adding' value='Подтверждаю выбор изображения' />
</form><br />
<form name='cancel' action='imgchtoy.php' method='post'>
  <input type='submit' name='cancel' value='Отмена' />
</form>