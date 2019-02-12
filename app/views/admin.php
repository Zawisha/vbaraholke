<?php $this->layout('layout') ;
//session_start();
if(!isset($_SESSION))
{
    session_start();
}

if(!isset($_SESSION ["auth_logged_in"])){
    header("Location: /login.php");
}
if(!$_SESSION['admin'])
{
    header("Location: /");
}

else
{
    ?>
    <div class="container">
        <div class="row ">
        <div class="col-xl-12 col-lg-9 col-md-8 usermenu">
        <div class="col-xl-3 col-lg-5 col-md-8 chpass workwithposts">
            <a href="showallusers">Работа с пользователями</a>
        </div>
        <div class="col-xl-3 col-lg-5 col-md-8 logout workwithuser">
            <a href="adminposts">Работа с постами</a>
        </div>
    </div>
        </div>
    </div>
<?php



}
?>