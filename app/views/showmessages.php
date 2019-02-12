<?php $this->layout('layout') ;
if(!isset($_SESSION))
{
    session_start();

}
?>

<div class=" container listmescontainer ">
    <div class="row justify-content-center">
        <div id="listmessage" class="col-10 ">
        </div>
    </div>
</div>


<script>
    function loadnew_messes() {
        $.ajax({
            type: "POST",
            url: "allloadnmess",
            data: {
                myid:<?php echo $_SESSION["auth_user_id"] ?>,
            },
            success: function(data1)
            {
                var duce = jQuery.parseJSON(data1);
                 console.log(duce);
                var val = 'sadsadasdasdsadsad';
                $.each(duce,function (index,value) {
                     console.log(value['id']);
                    $("#listmessage").append("<div class=\"col-12 meslist\"><a href=\"user?id="+value['id']+"\"><span class='ram'>" + value['nick'] + "</span></a></div>");
                })
            }
        });
    }
    loadnew_messes();
</script>
