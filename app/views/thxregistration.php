<?php $this->layout('layout') ;
//session_start();
if(!isset($_SESSION))
{
    session_start();
}
?>
<div class="container ">
    <div class="row  col-12  addimgrow ">
        <div class="thxmod col-12 justify-content-center">
            Вам на почту выслано письмо для подтверждения регистрации.
        </div>
    </div>
</div>