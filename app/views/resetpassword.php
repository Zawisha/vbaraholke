
<?php $this->layout('layout') ;
?>
<div class="container ">
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
?>

        <div class="col-6 newpasswordform col-md-8 col-12">
            <form action="/respassword" method="POST">
                <div class="col-12  oldpass col-sm-12 ">
                    Введите email
                    <input class="input" type="email" name="email">
                </div>
                <div class="col-12 newpassubmit d-flex justify-content-center">
                    <button  type="submit">Восстановить пароль</button>
                </div>
            </form>
        </div>
    </div>
</div>



