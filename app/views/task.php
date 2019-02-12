
<?php $this->layout('layout') ;?>
<form enctype="multipart/form-data" action="/image" method="POST">
     <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
    Отправить этот файл: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>
<?php
?>






