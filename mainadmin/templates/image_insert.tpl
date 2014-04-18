<h2>Новая запись - изображение</h2>
<form name='addimage' action='addimage.php' method='post' enctype='multipart/form-data' onSubmit='return mustbeimg()'>
  <table name='addpicture' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td class='stl'>Загружаемый файл<br />(только формата <b>jpg</b>)</td>
      <td>
        <input id='file' type='file' name='ImageFile' onchange='checkjpg()' />
      </td>
    </tr>
    <tr>
      <td class='stl'>Тип изображения</td>
      <td>
        <input type='radio' name='Type' value='Мультфильм' checked />Постер мультфильма<br />
        <input type='radio' name='Type' value='Игрушка' />Фото игрушки
      </td>
    </tr>
    <tr>
      <td class='stl'>Описание</td>
      <td><input id='alt' type='text' name='Alt' value='' /> (* - обязательно)</td>
    </tr>
    <tr>
      <td class='stl'>Имя файла изображения<br />(латиницей)</td>
      <td>
        <input id='filename' type='text' name='FileName' value='' /> (* - обязательно)
      </td>
    </tr>
    <tr>
      <td colspan='2' align='right'>
        <input onmouseover='valid()' type='submit' name='add' value='Подтверждаю добавление' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='addimage.php' method='post'>
  <table name='addpicture' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td align='right'>
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />