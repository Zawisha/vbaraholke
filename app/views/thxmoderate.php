<?php $this->layout('layout') ;
//session_start();
if(!isset($_SESSION))
{
    session_start();
}

if(!isset($_SESSION ["auth_logged_in"])){
    header("Location: /login.php");
}
?>
<div class="container ">
    <div class="row addnewpost  justify-content-center">
        <div class=" stepbystep1">
            1
        </div>
        <div >
            <hr class="hr1">
        </div>
        <div class=" stepbystep1">
            2
        </div>
        <div >
            <hr class="hr1">
        </div>
        <div class=" stepbystep1">
            3
        </div>
    </div>
<div class="row  col-12  addimgrow ">
    <div class="thxmod col-12 justify-content-center">
Спасибо. Ваш пост принят и проходит модерацию.
 </div>
 </div>
 </div>