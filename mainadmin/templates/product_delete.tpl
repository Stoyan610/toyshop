<h2>Эта запись будет удалена</h2>
<table name='deleting' cellspacing='0' cellpadding='3' border='1'>
  <tr class='stl0' align='center'>
    <td>ID</td>
    <td>Название</td>
    <td>Фото</td>
    <td>Мульт.</td>
    <td>Описание</td>
    <td>Ключевые слова</td>
    <td>Пр-тет</td>
    <td>Дата публикации</td>
    <td>Цена</td>
    <td>Есть</td>
    <td>Сроки</td>
    <td>Страна<br />Материал<br />Размеры<br />Вес</td>
    <td>Поп-сть</td>
  </tr>
  <tr align='center'>
    <td>%toyid%</td>
    <td>%toyname%</td>
    <td>
      <img src='%pictpath%toy70x70/%pictname%.jpg' alt='%pictname%' width='70' height='70' />
      <br />(%num% фото)
    </td>
    <td>%multname%</td>
    <td>%description%</td>
    <td>%keywords%</td>
    <td>%toypriority%</td>
    <td>%publishfrom%</td>
    <td>%toyrice%</td>
    <td>%quantity%</td>
    <td>%deadline%</td>
    <td>%manufacture%<br />%material%<br />%dimension%<br />%weight%</td>
    <td>%toypopularity%</td>
  </tr>
  </tr>
</table><br />
<form name='deleting' action='deleting.php' method='post'>
  <input type='hidden' name='del' value='%toyid%' />
  <input type='hidden' name='choice' value='product' />
  <input type='submit' name='delete' value='Подтверждаю удаление' />
</form><br />
<form name='cancel' action='deleting.php' method='post'>
  <input type='hidden' name='choice' value='product' />
  <input type='submit' name='cancel' value='Отмена' />
</form>