<h2>Новая запись - игрушка</h2>
<form name='adding' action='adding.php' method='post' onSubmit='return mustbe()'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td style='stl'>Название</td>
      <td>
        <input id='req1' type='text' name='Name' value='' /> (* - обязательно)
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
      <td style='stl'>Цена</td>
      <td>
        <input type='text' id='prc' name='Price' value=''  onblur='checknum("prc")' />
      </td>
    </tr>
    <tr>
      <td style='stl'>В наличии</td>
      <td>
        <input type='text' name='Quantity' value='' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Сроки</td>
      <td>
        <input type='text' name='Deadline' value='' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Страна</td>
      <td>
        <input type='text' name='Manufacture' value='' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Материал</td>
      <td>
        <input type='text' name='Material' value='' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Размеры</td>
      <td>
        <input type='text' name='Dimension' value='' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Вес</td>
      <td>
        <input type='text' id='wgt' name='Weight' value='' onblur='checknum("wgt")' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Популярность</td>
      <td>
        <input type='text' name='Popularity' value='' />
      </td>
    </tr>
    <tr>
      <td colspan='2' align='right'>
        <input type='hidden' name='choice' value='product' />
        <input type='submit' name='add' value='Подтверждаю добавление' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='adding.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='product' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />