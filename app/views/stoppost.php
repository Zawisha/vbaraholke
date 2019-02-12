<?php $this->layout('layout') ;
if(!isset($_SESSION))
{
    session_start();
}
if(!isset($_SESSION ["auth_logged_in"])){
    header("Location: /login.php");
}
?>