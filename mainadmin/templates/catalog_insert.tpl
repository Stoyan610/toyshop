<h2>%title%</h2>
<form name='adding' action='adding.php' method='post' onSubmit='return mustbe()'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr></tr><tr>
      <td style='stl'>Название</td>
      <td>
        <input id='req1' type='text' name='Name' value='' /> (* - обязательно)
      </td>
    </tr>
    <tr>
      <td style='stl'>Описание</td>
      <td>
        <input type='text' name='Description' value='' size='100' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Ключевые слова</td>
      <td>
        <input type='text' name='Keywords' value='' size='50' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Приоритет</td>
      <td>
        <input type='text' name='Priority' value='' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Дата публикации</td>
      <td>
        <input type='text' id='pick' name='PublishFrom' value='' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Изображение
        <input id='imageid' type='hidden' name='Image_ID' value='0' />
      </td>
      <td id='pictures' ondblclick='galery()'>
        <span>Для выбора изображения дважды кликни здесь</span>
        <div id='hide0' hidden>%pictpath%</div>
        <div id='hide1' hidden>%str_id%</div>
        <div id='hide2' hidden>%str_pic%</div>
      </td>
    </tr>
    <tr>
      <td colspan='2' align='right'>
        <input type='hidden' name='choice' value='catalog' />
        <input type='submit' name='add' value='Подтверждаю добавление' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='catalog' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />