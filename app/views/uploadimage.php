<?php

if(!isset($_SESSION))
{
    session_start();

}
$this->layout('layout') ;?>

Файл должен быть типа jpg или png. Максимальный размер 3 мб.

<form enctype="multipart/form-data" action="uploadavaimage" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
    Отправить этот файл: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>

<?php

if (isset($zero))
{echo $zero;}


