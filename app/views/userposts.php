<?php
if(!isset($_SESSION))
{
    session_start();
}
$this->layout('layout') ;


if(isset($zero)){
    ?>
    <div class="container">
    <div class="row zeroposts ">
        <div class="col-12 d-flex justify-content-center">
    Нет объявлений
    </div>
    </div>
    </div>
    <?php
}
else{
foreach ($posts as $post) {
    ?>
    <div class="container <?php echo $post['idpost'] ?> ">
        <div class="row posts ">
            <div class="col-12 col-md-12 d-md-flex">
                <div class="col-12  d-flex justify-content-center col-md-2 col-lg-2 ">
                    <?php
                    $flag = '0';
                    foreach ($images

                    as $img)
                    {

                    if ($post['idpost'] == $img["postid"])
                    {
                    $flag = '1';
                    ?>
                    <img src="images/posts/<?php echo $img["id"]; ?>.jpg" alt="imgpost" class="imgpost">
                </div>
                <?php
                break;
                }

                }
                if ($flag != '1')
                {
                ?>
                <img src="images/posts/0.jpg" alt="imgpost" class="imgpost">
            </div>
            <?php
            }

            ?>


            <div class="col-12  justify-content-center col-md-6 d-md-block col-lg-5 headerref ">
                <div class="col-12 d-flex justify-content-center ">
                    <a href="/showpost?numb=<?php echo $post['idpost'] ?>"><?php echo $post['header'] ?></a>
                </div>
                <div class="col-12 col-md-12 descr ">
                    <div class="col-12 d-flex justify-content-center justify-content-md-start time__add">
                        <?php
                        echo 'Добавлено:'. date('Y-m-d',$post['timeadd'] ) ;
                       ?>
                    </div>
                    <div class="col-12 d-flex justify-content-center justify-content-md-start region">
                        <?php  switch($post['region'])
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
                        } ?>
                    </div>
                    <div class="col-12 d-flex justify-content-center justify-content-md-start city">
                        <?php echo $post['city']; ?>
                    </div>
                    <div class="col-12 d-flex justify-content-center justify-content-md-start condition">
                        <?php

                        if($post['accept']==1)
                        {
                            ?>
                        <div class="accepttext">
                            Опубликовано
                    </div>
                        <?php
                        }
                        else
                        {
                            ?>
                            <div class="accepttext1">
                                На модерации
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-center col-md-3 align-self-md-end  price id='elem' ">
                <?php echo $post['price'] . 'р'; ?>
            </div>


        </div>
        <div class="row deletepost ">
            <div class="col-12 d-flex justify-content-center ">
                <input type="submit" value="удалить пост" name=<?php echo $post['idpost'] ?>>
            </div>
        </div>
    </div>

    </div>

    <?php
}
?>
<div class="container">
    <div class="row paginator d-flex justify-content-center ">
        <?php
        echo $paginator;
        }
        ?>
    </div>
</div>

<script>

    var arrpost = [];
    var elems = document.getElementsByTagName('input');
    for (var i = 0; i < elems.length; i++) {
        elems[i].addEventListener('click', func);
    }

    function func() {
        var numb = (this.name);
        $("." + numb).hide(1000);
        arrpost.push(this.name);
        $.ajax  ({
            type:'POST',
            url:'../test',
            data:{'z':numb},
            success:function(data){
                                }
                });
    }
</script>