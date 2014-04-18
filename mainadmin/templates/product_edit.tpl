<h2>Изменение данных записи - игрушки</h2>
<form name='editing' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td style='stl'>ID</td>
      <td>
        <input type='text' name='ID' value='%toyid%' readonly />
      </td>
    </tr>
    <tr>
      <td style='stl'>Название</td>
      <td>
        <input type='text' name='Name' value='%toyname%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Мультфильм</td>
      <td>
        <select name='Catalog_ID'>
          %options%
        </select>
      </td>
    </tr>
    <tr>
      <td style='stl'>Описание</td>
      <td>
        <input type='text' name='Description' value='%description%' size='100' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Ключевые слова</td>
      <td>
        <input type='text' name='Keywords' value='%keywords%' size='50' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Приоритет</td>
      <td>
        <input type='text' name='Priority' value='%toypriority%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Дата публикации</td>
      <td>
        <input type='text' id='pick' name='PublishFrom' value='%publishfrom%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Цена</td>
      <td>
        <input type='text' id='prc' name='Price' value='%toyprice%'  onblur='checknum("prc")' />
      </td>
    </tr>
    <tr>
      <td style='stl'>В наличии</td>
      <td>
        <input type='text' name='Quantity' value='%toyquantity%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Сроки</td>
      <td>
        <input type='text' name='Deadline' value='%deadline%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Страна</td>
      <td>
        <input type='text' name='Manufacture' value='%manufacture%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Материал</td>
      <td>
        <input type='text' name='Material' value='%toymaterial%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Размеры</td>
      <td>
        <input type='text' name='Dimension' value='%toydimension%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Вес</td>
      <td>
        <input type='text' id='wgt' name='Weight' value='%toyweight%' onblur='checknum("wgt")' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Популярность</td>
      <td>
        <input type='text' name='Popularity' value='%toypopularity%' />
      </td>
    </tr>
    <tr>
      <td colspan='2' align='right'>
        <input type='hidden' name='choice' value='product' />
        <input type='submit' name='edit' value='Подтверждаю изменения' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='product' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />