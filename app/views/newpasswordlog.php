<?php $this->layout('layout') ;
if(!isset($_SESSION))
{
    session_start();

}
if(!isset($_SESSION ["auth_logged_in"])){
    header("Location: /login.php");
}
?>




<div class="container ">
    <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">

        <div class="col-6 newpasswordform col-md-8 col-12">
<form action="/newpasslog" method="POST">
        <div class="col-12   oldpass col-sm-12">
    Введите старый пароль
    <input class="input" type="password" name="oldPassword">
        </div>
        <div class="col-12 newpass col-sm-12">
    Введите новый пароль&nbsp
<input class="input" type="password" name="newPassword">
        </div>
        <div class="col-12 newpassubmit d-flex justify-content-center">
                        <button  type="submit">Поменять пароль</button>
        </div>
</form>
        </div>


    </div>
</div>
