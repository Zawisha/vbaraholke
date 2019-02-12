<?php $this->layout('layout') ;
//session_start();
if(!isset($_SESSION))
{
    session_start();
}

?>


<div class="container ">
<?php
if($status) {
    ?>
            <div class="row ">
                <div class="col-12 adminka ">
    <a href="/delete?numbpost=<?php echo $id['0']['idpost']; ?>">Удалить как админ</a>
                </div>
            </div>

    <?php
}

?>

    <div class="row userpostcolm">
        <div class="col-12 comeback">
<a href=<?php echo $url1 ?>>Вернуться назад</a>

        </div>

<div class="col-12 headerpost">
    <?php
    echo $id['0']['header'];
    ?>
</div>
    <div class="col-12 ">
        <div class="fotorama" data-width="500" data-ratio="400/240" data-max-width="100% ">
    <?php
    if (!empty($images)) {
        foreach ($images as $img) {
            ?>
                <img src="/images/posts/<?php echo $img["id"] ?>.jpg" class="viewpostimg" alt="imgpost" >
            <?php
        }
    }
    ?>
        </div>
</div>
        <div class="col-12">
        <?php
        if (empty($images)) {
            {
                ?>
                <img src="/images/posts/<?php echo '0' ?>.jpg"class="viewpostimg" alt="imgpost" >
                <?php
            }
        }
    ?>
        </div>

    </div>
</div>
            <div class="col-12 viewposttext1 ">
                <a href="/user?id=<?php echo $id['0']['userid']; ?>"> <?php
                    echo 'Опубликовал(а) '. $username['0']['username'];
                    ?></a>
     </div>

<div class="col-12 viewposttext1 ">

<?php
echo $id['0']['description'];

    ?>
</div>
<div class="col-12 viewposttext ">
<?php

                    switch($id['0']['region'])
                    {
                        case 0 :
                            echo "Вся Беларусь";
                            break;
                        case 1 :
                            echo "Минская область";
                            break;
                        case 2 :
                            echo "Гродненская область";
                            break;
                        case 3 :
                            echo "Брестская область";
                            break;
                        case 4 :
                            echo "Витебская область";
                            break;
                        case 5 :
                            echo "Могилёвская область";
                            break;
                        case 6 :
                            echo "Гомельская область";
                            break;
                    }
                    ?>
</div>
<div class="col-12 viewposttext ">
    <?php
    echo'Город: '. $id['0']['city'];
    ?>
</div>

<div class="col-12 viewposttext ">
   <?php
if($id['0']['new']=='1') echo 'Состояние: Новый';
else echo 'Состояние: Б/У';
?>
</div>

<div class="col-12 viewposttext ">
    <?php
    echo 'Категория: ' . $categortovarov['0']['categorytov'];
    ?>
</div>


<div class="col-12 viewposttext ">
    <?php
    echo 'Добавлено:'. date('Y-m-d',$id['0']['timeadd'] ) ;
    ?>
</div>

<div class="col-12 viewposttext ">
    <?php
    echo 'Цена: '. $id['0']['price'] . 'р';
    ?>
</div>
<div class=" col-12  d-flex justify-content-center stic">
<div class="col-6 col-sm-4 viewposttext2 ">
    <a href="/user?id=<?php echo $id['0']['userid']; ?>">
    Написать
        </a>
</div>

    <div class="col-6 col-sm-4 viewposttext3 " id="call">
           Позвонить
    </div>

</div>

</div>

<script>
    call.onclick = function() {
        $.ajax({
            url: '/calluser',
            type: 'POST',
            data:   {
                myid:<?php echo $id['0']['userid'] ?>,
            },
            success: function (data) {
                document.getElementById('call').innerHTML = "<a href='tel:"+data+"'>"+data+"</a>";
            },
        });
    };
</script>