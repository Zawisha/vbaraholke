<?php $this->layout('layout') ;
if(!isset($_SESSION))
{
    session_start();

}
if((isset($_SESSION["auth_user_id"]))&&($_SESSION["auth_user_id"]==$userid))
{
    header("location: /");
}
//var_dump($username);
?>

<div class="container profilecontainer">
    <div class="row name">
        <div class="col-12 username">
            <?php
            echo( $username['0']['username']);
            ?>
        </div>
    </div>

        <div class="row userprof ">
            <div class="col-xl-3 col-lg-3 col-md-4 userava " id='refresh'>
                <img src="images/ava/<?php
                    if (file_exists('images/ava/'.$userid.'.jpg')) {
                        echo $userid;
                    }
                    else
                    {
                        echo '0';
                    }

                    ?>.jpg " id="imgavatar" />

</div>


    <div class="col-8">
        <div class="col-12">
            Область: <?php  echo( $username['0']['region']); ?>
        </div>
        <div class="col-12">
            Город: <?php  if($username['0']['city']!=null){ echo( $username['0']['city']); }
            else
            {
                echo 'не указан';
            }
            ?>
        </div>
        <div class=" col-md-4 viewposttext3" id="calluser">
         позвонить
        </div>
    </div>

</div>


<div class="container">
    <div class="row ">
        <div class="col-12 myob">
            <a href="/notmineuserposts?id=<?php echo $userid ?>">Все объявления пользователя</a>
        </div>
    </div>
</div>

    <div class="container">
        <div class="row name justify-content-center">
            <div class="col-12 col-sm-8 ">
                <div id="messages">
                </div>
            </div>
            <div class="col-12 col-sm-8 justify-content-center">
                <form id="pac_form" action="">
                    <textarea name="comment" id="mess_to_send"></textarea>
                    <input type="submit" value="Отправить">
                </form>
            </div>

        </div>
    </div>

    <script>

        $(document).ready(function () {
            $("#pac_form").submit(Send);
        });

            function Send() {
                var mess=$("#mess_to_send").val();
                 $.ajax({
                     type: "POST",
                     url: "chat",
                     data: {
                         mess:mess,
                         myid:<?php echo $_SESSION["auth_user_id"] ?>,
                         hisid:<?php echo $userid ?>,
                     },
                         // Выводим то что вернул PHP
                     success: function(data)
                     {
                         //Очищаем форму ввода сообщения
                         $("#mess_to_send").val('');
                         // по поле ввода сообщения ставим фокус
                         $("#mess_to_send").focus();
                         //вызываем функцию отображения сообщений
                         loadmy_messes(data);
                     }
                 });
                return false;
            }

        function loadmy_messes(data)
        {
            var loadmymes = jQuery.parseJSON(data);
            $("#messages").append("<div class=\"col-6 mydate\">" + loadmymes['0'] + "</div>");
            $("#messages").append("<div class=\"col-11 col-md-6 mymessage\"><span class='ramka'>"+loadmymes['1']+"</span></div>");
            $("#messages").scrollTop(90000);
        }


        function loadhis_messes() {
        $.ajax({
                type: "POST",
                url: "hismesses",

                data: {
                    myid:<?php echo $_SESSION["auth_user_id"] ?>,
                    hisid:<?php echo $userid ?>,
                },
                success: function(data1)
                {
                    var duce = jQuery.parseJSON(data1);
                    $.each(duce,function (index,value) {
                        var flag = 0;
                     $.each(value, function (ind,val) {
                         if((ind=='id')&&(Number (val)>Number (lastid)))
                         {
                             lastid = val;
                             flag = 1;
                         }

                         if((ind=='time')&&(flag==1)) {
                             $("#messages").append("<div class=\"col-11 col-md-6 offset-md-6 hisdate\">" + val + "</div>");

                         }
                         if((ind=='message')&&(flag==1))
                         {
                             $("#messages").append("<div class=\"col-11 col-md-6 offset-md-6 hismessage\"><span class='ramka1'>"+val+"</span></div>");
                             $("#messages").scrollTop(90000);
                             flag = 0;
                         }
                                                      })
                    })
                }
            });
        }


        function loadallmesses() {
            $.ajax({
                type: "POST",
                url: "allloadmesses",
                async: false,
                data: {
                    myid:<?php echo $_SESSION["auth_user_id"] ?>,
                    hisid:<?php echo $userid ?>,
                },
                success: function(data2)
                {
                    var duce = jQuery.parseJSON(data2);
                    $.each(duce,function (index,value) {
                        var flag = 0;
                        var myidflag = 0;
                        $.each(value, function (ind,val) {
                            if((ind=='id')&&(Number (val)>Number (lastid)))
                            {
                                lastid = val;
                                flag = 1;
                            }
                            if(ind=='myid')
                            {
                                if(val == <?php echo $_SESSION["auth_user_id"] ?>) {
                                    myidflag = 1;
                                }
                                else
                                {
                                    myidflag = 0;
                                }
                            }
                            if(ind=='time')
                            {
                                if(myidflag==1) {
                                    $("#messages").append("<div class= \"mydate\">" + val + "</div>");
                                }
                                if(myidflag==0) {
                                    $("#messages").append("<div class=\"hisdate\">" + val + "</div>");
                                }
                            }
                            if((ind=='message')&&(flag==1)&&(myidflag==1))
                            {
                                $("#messages").append("<div class=\"col-11 col-md-6 mymessage\"><span class='ramka'>"+val+"</span></div>");
                                $("#messages").append("<br>");
                                flag = 0;
                            }
                            if((ind=='message')&&(flag==1)&&(myidflag==0))
                            {
                                $("#messages").append("<div class=\"col-11 col-md-6 offset-md-6 hismessage\"><span class='ramka1'>"+val+"</span></div>");
                                flag = 0;
                            }
                        })
                    })
                    $("#messages").scrollTop(90000);
                }
            });
        }


       var lastid =0;
        //скрипт подгрузки сообщений
        loadallmesses();
        setInterval("loadhis_messes();", 2000);
    </script>

    <script>
        calluser.onclick = function() {
            $.ajax({
                url: '/calluser',
                type: 'POST',
                data:   {
                    myid:<?php echo $userid ?>,
                },
                success: function (data) {
                    document.getElementById('calluser').innerHTML= "<a href='tel:"+data+"'>"+data+"</a>";
                },

            });

        };
        </script>


