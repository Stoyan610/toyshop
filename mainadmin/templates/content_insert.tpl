<h2>Добавление новой записи - информации</h2>
<form name='adding' action='adding.php' method='post'>
  <table name='adding' cellspacing='0' cellpadding='5' border='1'  width='100%'>
    <tr>
      <td style='stl'>Выбрать категорию</td>
      <td>
        <select name='Category'>
          %options%
        </select>
      </td>
      <td style='stl'>или ввести новую категорию</td>
      <td>
        <input type='text' name='new_Cat' value='' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Заголовок</td>
      <td>
        <input type='text' name='Title' value='' size='40' />
      </td>
      <td style='stl'>Краткое содержание</td>
      <td>
        <input type='text' name='Brief' value='' size='80' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Полное содержание</td>
      <td colspan='3'>
        <div id='editor'></div>
      </td>
    </tr>
    <tr>
      <td style='stl'>Ревизия</td>
      <td>
        <select name='Revision'>
          <option selected value='1'>Действует</option>
          <option value='0'>В запасе</option>
        </select>
      </td>
      <td style='stl'>Дата публикации</td>
      <td>
        <input type='text' id='pick' name='PublishFrom' value='' />
      </td>
    </tr>
    <tr>
      <td colspan='4' align='right'>
        <input type='hidden' name='choice' value='content' />
        <input type='submit' name='add' value='Подтверждаю добавление' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='adding.php' method='post'>
  <table name='cancel' cellspacing='0' cellpadding='5' border='1' width='100%'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='content' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />