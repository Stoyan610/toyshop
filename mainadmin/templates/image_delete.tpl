<h2>Это изображение будет удалено</h2>
<table name='delpic' cellspacing='0' cellpadding='3' border='1'>
  <tr class='stl0' align='center'>
    <td>ID</td>
    <td>Тип</td>
    <td>Изображение</td>
    <td>Имя файла</td>
    <td>Ширина</td>
    <td>Высота</td>
    <td>Описание</td>
  </tr>
  <tr align='center'>
    <td>%imageid%</td>
    <td>%imagekind%</td>
    <td>
      <img src='%pictpath%%pictname%.jpg' alt='%pictname%' width='%minwidth%' height='%minheight%' />
    </td>
    <td>%pictname%</td>
    <td>%imagewidth%</td>
    <td>%imageheight%</td>
    <td>%imagealt%</td>
  </tr>
</table><br />
<form name='delete' action='delimage.php' method='post'>
  <input type='hidden' name='del' value='%imageid%' />
  <input type='submit' name='delete' value='Подтверждаю удаление' />
</form><br />
<form name='cancel' action='delimage.php' method='post'>
  <input type='submit' name='cancel' value='Отмена' />
</form>