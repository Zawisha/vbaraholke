
<?php $this->layout('layout') ;
//session_start();
if(!isset($_SESSION))
{
    session_start();
}

?>

<div class="container logemail">
    <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">
        <?php if (isset($error))
        {
            ?>
            <div class="col-6 newpasswordform col-md-8 col-12">
                <div class="col-12 newpassubmit d-flex justify-content-center">
                    <?php echo $error; ?>
                </div>

            </div>
            <?php
        }
        else
        {
        ?>
        <div class=" newpasswordform col-md-8 col-12 ">
            <form action="/newpassword" method="POST">
                <input type="hidden" name="selector" value=<?php $selector ?>>
                <input type="hidden" name="token" value=<?php $token ?>>
                <div class="newpass col-sm-12 d-sm-flex ">
                    <div class="col-12 col-sm-6 newpass1">Введите новый пароль</div>
                    <div class="col-12 col-sm-6 newpass2"><input class="input" type="password" name="password"></div>
                </div>
                <div class="col-12 newpassubmit d-flex justify-content-center">
                    <button  type="submit">Обновить пароль</button>
                </div>
            </form>
        </div>
        <?php
        }

        ?>



    </div>
</div>
