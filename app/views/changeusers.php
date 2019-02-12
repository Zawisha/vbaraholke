<?php
 $this->layout('layout') ;
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
?>

<div class="container ">
    <div class="row  col-12  addimgrow ">
        <div class="thxmod col-12 justify-content-center">

            <?php
            if (!empty($users)) {
                foreach ($users as $user) {
                    echo $user . '<br>';
                }
            }
            ?>
        </div>
    </div>
</div>


<div class="container">
    <div class="row ">
        <div class="col-12 error ">
<a href=<?php echo $urlref ?>>Вернуться назад</a>
        </div>
    </div>
</div>
