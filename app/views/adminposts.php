<?php
 $this->layout('layout') ;
//session_start();
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

if(isset($totalitems))
{
    ?>
    <div class="container ">
        <div class="row ">
            <div class="col-12 noposts">
      Нет постов
            </div>
        </div>
    </div>
<?php
}
else {
    ?>
<div class="container ">
    <form action="/changeposts" method="POST">

        <?php
        foreach ($posts as $post) {
            ?>
                <div class="row adminposts ">
                    <div class="col-12 d-md-flex">
        <?php
            $imgflag = 0;
            foreach ($images as $img) {
                if ($post['idpost'] == $img["postid"]) {
                    $imgflag = 1;
                    ?>
                <div class="col-4">
                        <img src="/images/posts/<?php echo $img["id"] ?>.jpg" class="imgpost1">
                </div>
                    <?php
                    break;
                }
            }
            if ($imgflag == 0) {
                ?>
                    <div class="col-4 ">
                <img src="/images/posts/<?php echo '0' ?>.jpg" class="imgpost1">
                    </div>
                <?php
            }
            ?>
<div class="col-8 admintext" >
                <div class="col-12 ">
            Перейти на объявление
            <a href="/showpost?numb=<?php echo $post['idpost'] ?>"><?php echo $post['header'] ?></a>
                </div>
                <div class="col-12 ">
                            <?php echo 'Id пользователя: '.$post['userid'] ;?>
                </div>
                <div class="col-12 ">
                            <?php    echo 'Заголовок: '. $post['header'] ;?>
                </div>
                <div class="col-12 ">
                            <?php   echo 'Цена: '.$post['price'] ;?>
                </div>
                <div class="col-12 ">
                            <?php echo  'Описание: '.$post['description'];?>
                </div>
                <div class="col-12 ">
                            <?php  if($post['new']=='1') echo 'Новый';
                                    else echo 'Б/У'; ?>
                </div>
                <div class="col-12 ">
                            <?php
                            switch($post['region'])
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
                <div class="col-12 ">
                            <?php    echo 'Город:'. $post['city'];?>
                </div>
                <div class="col-12 ">
                            <?php     echo date('Y-m-d',$post['timeadd'] ) ;?>
                </div>
                <div class="col-12 ">
                            <?php
                            switch($post['section'])
                            {
                                case 0 :
                                    echo "Все категории";
                                    break;
                                case 1 :
                                    echo "Всё для дома";
                                    break;
                                case 2 :
                                    echo "Недвижимость";
                                    break;
                                case 3 :
                                    echo "Компьютеры и ноутбуки";
                                    break;
                                case 4 :
                                    echo "Для детей и мам";
                                    break;
                                case 5 :
                                    echo "Автомобили и запчасти";
                                    break;
                                case 6 :
                                    echo "Всё для ремонта";
                                    break;
                                case 7 :
                                    echo "Сад и огород";
                                    break;
                                case 8 :
                                    echo "Спорт, туризм";
                                    break;
                                case 9 :
                                    echo "Свадьба, праздники";
                                    break;
                                case 10 :
                                    echo "Услуги";
                                    break;
                                case 11 :
                                    echo "Животные";
                                    break;
                                case 12 :
                                    echo "Женская одежда";
                                    break;
                                case 13 :
                                    echo "Обувь женская";
                                    break;
                                case 14 :
                                    echo "Аксессуары, украшения";
                                    break;
                                case 15 :
                                    echo "Косметика";
                                    break;
                                case 16 :
                                    echo "Одежда мужская";

                                    break;
                                case 17 :
                                    echo "Обувь мужская";
                                    break;
                            }


                            ?>
                </div>
                <div class="col-12 ">
            <select name="<?php echo('status' . $post['idpost']) ?>">
                    <option disabled>Выберите статус</option>
                    <option value="0">Не принять</option>
                    <option value="1" <?php echo 'selected="selected"'; ?> >Принять</option>
                </select>
                </div>
</div>
                    </div>
                </div>
            <?php

        }
        ?>

        <input type="hidden" name="adminposts" value="<?php echo(base64_encode(serialize($posts))); ?>">
        <button type="submit">Подтвердить настройки постов</button>

    </form>
    <div class="container">
        <div class="row paginator d-flex justify-content-center ">
    <?php
    echo $paginator;
}
    ?>
        </div>
    </div>


