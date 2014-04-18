<a class='stl' href='image.php?act=add'>Добавить изображение</a><br /><br />
<form name='getkind' action='image.php' method='get'>
  <table name='getkind' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td class='stl'>Выбор типа изображений<br />для вывода в таблице</td>
      <td>
        <input type='radio' name='act' value='get_cat' />Мультфильмы<br />
        <input type='radio' name='act' value='get_prod' />Игрушки<br />
        <input type='radio' name='act' value='get_all' checked/>Все изображения
      </td>
    </tr>
    <tr>
      <td colspan='2' align='right'>
        <input type='submit' name='gettable' value='Подтверждение выбора' />
      </td>
    </tr>
  </table>
</form><br />