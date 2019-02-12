<?php $this->layout('layout') ;

if(!isset($_SESSION))
{
    session_start();
}

if(isset($_SESSION ["auth_logged_in"])){
    header("Location: /");
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
?>
    <div class=" newpasswordform col-md-8 col-12 ">
            <form action="/log" method="POST">
                <div class="newpass col-sm-12 d-sm-flex ">
                    <div class="col-12 col-sm-6 newpass1">Введите логин или email</div>
                    <div class="col-12 col-sm-6 newpass2"><input class="input" type="text" name="email"></div>
                </div>
                <div class=" newpass col-sm-12 d-sm-flex">
                    <div class="col-12 col-sm-6 newpass1">Введите пароль</div>
                    <div class="col-12 col-sm-6 newpass2"><input class="input" type="password" name="password"></div>
                </div>



                <div class="col-12 newpassubmit d-flex justify-content-center">
                    <button  type="submit">Залогиниться</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container ">
    <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">

        <div class="col-6 newpasswordform col-md-8 col-12">
            <div class="col-12 newpassubmit d-flex justify-content-center">
              <?php  echo $link = '<a href="' . $url . '?' . urldecode(http_build_query($params)) . '">
Войти через Вконтакте<img src="images/vk.png" alt="vk" class="vk"></a>'?>

            </div>

        </div>


    </div>
</div>


<div class="container ">
    <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">

        <div class="col-6 newpasswordform col-md-8 col-12">
            <div class="col-12 newpassubmit d-flex justify-content-center">
                <a href="/resetpassword">Восстановить пароль</a>
            </div>

        </div>


    </div>
</div>
<script >
    $(document).ready(function(){
        $('select, input').styler();
    });
</script>