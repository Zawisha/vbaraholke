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
Вы авторизованы на сайте. Спасибо.
        </div>
    </div>
</div>