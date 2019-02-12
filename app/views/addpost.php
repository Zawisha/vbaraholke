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

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>


<div class="container">
    <div class="row addnewpost  justify-content-center">

        <div class=" stepbystep1">
            1
        </div>
        <div >
            <hr class="hr1">
        </div>
        <div class=" stepbystep2">
            2
        </div>
        <div >
            <hr class="hr2">
        </div>
        <div class=" stepbystep3">
            3
        </div>
        <div class="col-12 addnewuserposttext ">
           <form action="/uplimgfuser" method="post" enctype="multipart/form-data" id="uploadImages" onsubmit='return validate()' name = 'validateform'>
    Название товара (краткое название товара в именительном падеже)
        </div>
        <div class="col-12 addnewuserpostinput">
     <textarea class="input " rows="3" cols="40" type="text" name="header" placeholder="от 3 до 50 знаков"></textarea><span style='color:red' id='namef'></span>
        </div>
        <div class="col-12 addnewuserposttext">
            Описание товара
        </div>
        <div class="col-12 addnewuserpostinput">
<!--    <textarea class="input changeinput" rows="10" cols="60" type="text" name="description" placeholder="от 15 до 3000 знаков"></textarea><span style='color:red' id='descriptionf'></span>-->
            <textarea class="input changeinput" rows="10" type="text" name="description" placeholder="от 15 до 3000 знаков"></textarea><span style='color:red' id='descriptionf'></span>

        </div>
        <div class="col-12 addnewuserposttext">
    Цена товара. (в белорусских рублях)
        </div>
        <div class="col-12 addnewuserpostinput">
      <input class="input" type="text" name="price" name="header" placeholder="до 7 знаков">.руб</textarea><span style='color:red' id='pricef'></span>
        </div>
        <div class="col-12 addnewuserposttext">
    Выберите область
        </div>
        <div class="col-12 addnewuserpostinput">
   <select name="region">
            <option disabled>Выберите область</option>
            <?php

            foreach($cities as $reg)
            { ?>
                <option value="<?php echo $reg['number'];?>" <?php if(isset($_GET['city'])) { if ($_GET['city']==$reg['number']) echo 'selected="selected"' ;}?>><?php echo $reg['region'];?>
                <?php
            }
            ?>
        </select>
        </div>
        <div class="col-12 addnewuserposttext">
    Ваш город
        </div>
        <div class="col-12 addnewuserpostinput">
     <input class="input" type="text" name="city"><span style='color:red' id='cityf'></span>
        </div>
        <div class="col-12 addnewuserposttext">
   Выберите категорию
        </div>
        <div class="col-12 addnewuserpostinput">
    <select name="category">
            <option disabled>Выберите категорию</option>
            <?php
            foreach ( $categories as $reg)
            { ?>
                <option value="<?php echo $reg['number'];?>" <?php if(isset($_GET['category'])) { if ($_GET['category']==$reg['number']) echo 'selected="selected"' ;}?>><?php echo $reg['categorytov'];?></option>
                <?php
            }
            ?>

        </select>
    </option><span style='color:red' id='catf'></span>
        </div>
        <div class="col-12 addnewuserposttext">
    Состояние товара
        </div>
        <div class="col-12 addnewuserpostinput">
    <select name="condition">
        <option value="1">Новый</option>
        <option value="0">Б/у</option>
        </select>
        </div>

    <div class="col-12 addnewuserpostsubmit">
        <input type="submit" value="Далее" >
    </div>
</form>
    </div>
</div>



<script>

     function validate() {
         var x=document.forms['validateform']['header'].value;
         var y=document.forms['validateform']['description'].value;
         var price=document.forms['validateform']['price'].value;
         var city=document.forms['validateform']['city'].value;
         var category=document.forms['validateform']['category'].value;

         if (x.length==0) {

             document.getElementById('namef').innerHTML='*данное поле обязательно для заполнения';
             return false;
         }
         else
         {
             document.getElementById('namef').innerHTML='';
         }
          if(x.length<3&&x.length!=0)
         {
             document.getElementById('namef').innerHTML='*слишком короткое название,минимум 3 символа';
             return false;
         }
          else
          {
              document.getElementById('namef').innerHTML='';
          }
         if(x.length>50)
         {
             document.getElementById('namef').innerHTML='*слишком длинное название, максимум 50 символов';
             return false;
         }
         else
         {
             document.getElementById('namef').innerHTML='';
         }
         if (y.length==0) {
             document.getElementById('descriptionf').innerHTML='*данное поле обязательно для заполнения';
             return false;
         }
         else
         {
             document.getElementById('descriptionf').innerHTML='';
         }
         if (y.length<15||y.length>3000&&y.length!=0) {
             document.getElementById('descriptionf').innerHTML='*описание должно быть больше 15 и меньше 3000 символов';
             return false;
         }
         else
         {
             document.getElementById('descriptionf').innerHTML='';
         }

         if(isNaN(parseFloat(price))&&!isFinite(price))
         {
             document.getElementById('pricef').innerHTML='*введите число';
             return false;
         }
         else
         {
             document.getElementById('pricef').innerHTML='';
         }
         if(price.length==0)
         {
             document.getElementById('pricef').innerHTML='*введите цену';
             return false;
         }
         else
         {
             document.getElementById('pricef').innerHTML='';
         }
         if(price.length>7)
         {
             document.getElementById('pricef').innerHTML='*максимум 7 знаков';
             return false;
         }
         else
         {
             document.getElementById('pricef').innerHTML='';
         }
         if (city.length==0) {

             document.getElementById('cityf').innerHTML='*данное поле обязательно для заполнения';
             return false;
         }
         else
         {
             document.getElementById('cityf').innerHTML='';
         }
         if(city.length<2&&city.length!=0)
         {
             document.getElementById('cityf').innerHTML='*слишком короткое название,минимум 2 символа';
             return false;
         }
         else
         {
             document.getElementById('cityf').innerHTML='';
         }
         if(city.length>50)
         {
             document.getElementById('cityf').innerHTML='*слишком длинное название, максимум 50 знаков';
             return false;
         }
         else
         {
             document.getElementById('cityf').innerHTML='';
         }

         if (category==0)
         {
             document.getElementById('catf').innerHTML='*выберите категорию';
             return false;
         }
         else
         {
             document.getElementById('catf').innerHTML='';
         }
     }

</script>

