<?php $this->layout('layout') ;
if(!isset($_SESSION))
{
    session_start();

}
?>


<div class="container choose_search">
    <div class="row category">
        <div class="col-12 col-lg-6">
            <div class="search">
                <form action="/" method="GET" id="myform">
                    <div class="text__choose  d-flex">
                        <div class="textcat">Выберите категорию</div>
                        <div class="d-flex">
                            <select name="category" class="sel0">
                                <option disabled>Выберите категорию</option>
                                <?php
                                foreach ( $categories as $reg)
                                { ?>
                                    <option  value="<?php echo $reg['number'];?>" <?php if(isset($_GET['category'])) { if ($_GET['category']==$reg['number']) echo 'selected="selected"' ;}?>><?php echo $reg['categorytov'];?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="search ">
                <div class="ch__region">
                    <div class="text__choose d-flex">
                        <input type="hidden" name="page" value="1">
                        <div class="textcat1">Выберите область</div>
                        <div class=" d-flex">
                            <select name="city" class="sel1 ">
                                <option disabled>Выберите область</option>
                                <?php

                                foreach($cities as $reg)
                                { ?>
                                    <option value="<?php echo $reg['number'];?>" <?php if(isset($_GET['city'])) { if ($_GET['city']==$reg['number']) echo 'selected="selected"' ;}?>><?php echo $reg['region'];?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row inputs d-flex justify-content-center ">
        <div class="col-12 inp d-flex justify-content-center col-lg-3 ">
            <input name="sortby" type="radio" value="0"  <?php if(isset($_GET['sortby'])&&($_GET['sortby'])==0){echo 'checked="checked"';}?>>
            <div class="text__input">По цене</div>
        </div>
        <div class="inp col-12 d-flex justify-content-center col-lg-3">
            <input name="sortby" type="radio" value="1" <?php if(!isset($_GET['sortby'])||($_GET['sortby'])==1){echo 'checked="checked"';}?>>
            <div class="text__input">По дате</div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="search d-flex justify-content-center ">
            <button  type="submit" class="form__btn">ВЫБРАТЬ</button>
        </div>
    </div>

</form>
</div>


<?php
if(isset($zeroitems))
{
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
foreach($posts as $post) {

    ?>
    <div class="container">
    <div class="row posts ">
        <div class="col-12 col-md-12 d-md-flex">
          <div class="col-12  d-flex justify-content-center col-md-2 col-lg-2 ">
    <?php
    $flag='0';
   foreach ($images as $img)
{

    if ($post['idpost']==$img["postid"])
    {
        $flag='1';
        ?>
              <a href="/showpost?numb=<?php echo $post['idpost'] ?>"><img src="images/posts/<?php  echo $img["id"];?>.jpg" alt="imgpost" class="imgpost" ></a>
          </div>
<?php
    break;
    }

}
if ($flag!='1')
            {
          ?>
            <a href="/showpost?numb=<?php echo $post['idpost'] ?>"><img src="images/posts/0.jpg" alt="imgpost" class="imgpost" ></a>
                      </div>
       <?php
               }

    ?>



              <div class="col-12  justify-content-center col-md-6 d-md-block col-lg-5 headerref ">
           <div class="col-12 d-flex justify-content-center  justify-content-lg-start">
            <a href="/showpost?numb=<?php echo $post['idpost'] ?>"><?php echo $post['header'] ?></a>
           </div>
            <div class="col-12  descr ">
                <div class="col-12 d-flex justify-content-center justify-content-md-start time__add">
                   <?php echo 'Добавлено:'. date('Y-m-d',$post['timeadd'] ) ;?>
                </div>
                <div class="col-12 d-flex justify-content-center justify-content-md-start region">
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
                <div class="col-12 d-flex justify-content-center justify-content-md-start city">
                    <?php echo $post['city']; ?>
                </div>
                <div class="col-12 d-flex justify-content-center justify-content-md-start condition">
                    <?php  if($post['new']=='1') echo 'Состояние: Новый';
                    else echo 'Состояние: Б/У'; ?>
                </div>
            </div>
               </div>
            <div class="col-12 d-flex justify-content-center col-md-3 align-self-md-end  price ">
                <?php echo $post['price'].'р' ;?>
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
        ?>
 </div>
    </div>
<?php } ?>
<script >
    $(document).ready(function(){
    $(".posts").hide();
    $(".posts").show("slide", {}, 2000);
         // $('select').styler();
    });
</script>




