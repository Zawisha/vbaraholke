<?php $this->layout('layout') ;
if(!isset($_SESSION))
{
    session_start();
}
?>
<div class="container">
    <div class="row zeroposts ">
        <div class="col-12  justify-content-center">
        <div class="col-12 d-flex" ><p>Подавая объявление, пользователь полностью соглашается с данными правилами
                и условиями пользования сервисом.</p></div>
            <div class="col-12 d-flex" > <p>Объявление не должно нарушать законодательство Республики Беларусь.</p></div>
                <div class="col-12 d-flex" >   <p>Запрещены объявления коммерческой деятельности</p></div>
                    <div class="col-12 d-flex" ><p>Запрещена перепродажа товаров из интернет магазинов и площадок</p></div>
                        <div class="col-12 d-flex" > <p>Администрация имеет право отклонить объявление без указания причины</p></div>
        </div>
    </div>
</div>