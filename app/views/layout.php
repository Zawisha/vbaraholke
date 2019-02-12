<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vbaraholke</title>
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Corben" rel="stylesheet">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/profile.css">
    <script src="jquery/jquery-3.3.1.min.js"></script>

    <link href="css/fotorama.css" rel="stylesheet">
    <script src="css/fotorama.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

    <link href="css/jquery.formstyler.css" rel="stylesheet" />
    <link href="css/jquery.formstyler.theme.css" rel="stylesheet" />
    <link href="css/jquery.formstyler.min.css" rel="stylesheet" />
    <script src="css/jquery.formstyler.js"></script>

    <script src="css/jquery.mask.min.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-128379110-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-128379110-1');
    </script>


</head>
<body>
<header id="header" class="header">
    <div class="container-fluid head">

        <div class="row headimg">
            <div class="col-lg-2 ">
                <a href="/" >  <img src="images/logo.png" alt="logo" class="logo"></a>
            </div>
            <div class="col-lg-10">
                <div class="logo__text">
                    <a href="/" >   Vbaraholke.by</a>
                </div>
            </div>
        </div>


        <div class="info col-12">

            <div class="row justify-content-center">


                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-3 addob ">
                    <a href="/addpost">ПОДАТЬ ОБЪЯВЛЕНИЕ</a>
                </div>

                <?php if(isset($_SESSION ["auth_logged_in"])){
                      ?>
                    <div id="message" class="col-xl-3 col-lg-4 newmesscss">
                    </div>
<?php  if(($_SERVER['REQUEST_URI'])!=('/account.php')) { ?>
                      <div class="col-12 col-sm-6 col-md-6 menu  col-lg-3 col-xl-4 d-lg-flex justify-content-lg-end ">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-4 menuitem2 justify-content-center">
                              <a href="account.php">Профиль</a>
                          </div>
                      </div>


                      <?php
                  }
                  }
                else
                {
                    ?>
                    <div class="col-12 col-sm-6 col-md-6 menu  col-lg-8 col-xl-8 d-lg-flex justify-content-lg-end ">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 menuitem ">
                            <a href="registration.php">Регистрация</a>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 menuitem1 ">
                            <a href="login.php">Войти</a>
                        </div>

                    </div>

                    <?php
                }

                ?>

            </div>
        </div>

    </div>
</header>




<?=$this->section('content')?>



<div class="container infosec">
    <div class="row justify-content-center justify-content-md-start ">
        <div class="col-5 justify-content-center col-lg-3 contacts">
            <a href="/contacts">Контакты</a>
        </div>
        <div class="col-6 justify-content-center col-lg-3 rules">
            <a href="/rules">Правила</a>
        </div>
    </div>
</div>
<div class="container footer">
    <div class="row">
        <div class="col-lg-12">
            <div class="credits ">
                Бесплатная доска объявлений.<br> Ресублика Беларусь 2018.
            </div>
        </div>
    </div>

</div>
</body>
</html>

<script>


    function loadnew_messes() {
        $.ajax({
            type: "POST",
            url: "loadnmess",
            data: {
                myid:<?php echo $_SESSION["auth_user_id"] ?>,
            },
            success: function(data1)
            {
                var duce = jQuery.parseJSON(data1);
                // console.log(duce);
                 if(duce!=0)
                 {
                      var val = 'У Вас новое сообщение';
                      var val1 = '*';

                     if ($('.message').length > 0) {
                         {}
                     }
                     else {
                         $("#message").show();
                         $("#message").append("<div class=\"col-12 message\"><a href=\"showmessages.php\"><span class='ram'>" + val + "</span><span class='ram1'>" + val1 + "</span></a></div>");
                     }

                 }
                 else {
                     $("#message").hide();
                 }
            }
        });
    }



    $("#message").hide();
    setInterval("loadnew_messes();", 2000);


</script>

