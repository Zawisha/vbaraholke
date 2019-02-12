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

<form action="/validaddnumberandemail" method="POST">
    <div class="container  ">
        <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">
            <div class="newpasswordform col-md-8 col-12">
                <form action="/register" method="POST">
                    <div class="newpass col-sm-12 d-sm-flex ">
                        <div class="col-12 col-sm-6 newpass1">Введите email</div>
                        <div class="col-12 col-sm-6 newpass2"><input class="input" type="email" name="email" id="email"></div>
                    </div >

                    <div class="col-12 corectemail " id="valid">

                    </div>

                    <div class=" newpass col-sm-12 d-sm-flex ">
                        <div class="col-12 col-sm-6 newpass1">Мобильный телефон</div>
                        <div class="col-12 col-sm-6 newpass2">
                            <input id="phone-number" name="phone-number" type="text" placeholder="+XXX(XX)-XXX-XX-XX">
                        </div>
                    </div>
                    <div class="col-12 newpassubmit d-flex justify-content-center ">
                        <button  type="submit">Ввести</button>
                    </div>
            </div>

        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#email').blur(function() {
            if($(this).val() != '') {
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
                if(pattern.test($(this).val())){
                    $('#valid').text('');
                    $(this).css({'border' : '1px solid #008000'});
                }
                else
                {
                    $('#valid').text('Введите корректный Email');
                }
            }
            else {
                // Предупреждающее сообщение
                $(this).css({'border' : '1px solid #ff0000'});
                $('#valid').text('Вы не ввели Email');
            }
        });

    });


</script>
<script>
    $(document).ready(function(){
        $('#phone-number').mask('+000(00)-000-00-00');
    })
</script>