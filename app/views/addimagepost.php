<?php  $this->layout('layout') ;
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


<div class="container ">
    <div class="row addnewpost  justify-content-center">
    <div class=" stepbystep1">
        1
    </div>
    <div >
        <hr class="hr1">
    </div>
    <div class=" stepbystep1">
        2
    </div>
    <div >
        <hr class="hr1">
    </div>
    <div class=" stepbystep3">
        3
    </div>
    </div>

<div class="row  col-12 justify-content-center addimgrow ">

    <div class="inputimg col-12">
<form action="/uplimgfuserpost" method="post" enctype="multipart/form-data" id="uploadImages">
<input type="file" id="addImages1" multiple="">
    <input type="hidden" name="param_header" value="<?php echo $param_header; ?>">
    <input type="hidden" name="param_description" value="<?php echo $param_description; ?>">
    <input type="hidden" name="param_price" value="<?php echo $param_price; ?>">
    <input type="hidden" name="param_region" value="<?php echo $param_region; ?>">
    <input type="hidden" name="param_city" value="<?php echo $param_city; ?>">
    <input type="hidden" name="param_category" value="<?php echo $param_category; ?>">
    <input type="hidden" name="param_condition" value="<?php echo $param_condition; ?>">
    </div>

    <div class="inputimg1 col-12">
<ul id="uploadImagesList">
    <li class="item template">
            <span class="img-wrap">
                <img src="image.jpg" alt="">
            </span>
        <span class="delete-link" title="Удалить">Удалить</span>
    </li>
</ul>
    </div>

<div class="imgbutton col-12">
    <input type="submit" value="Отправить" >
</div>
</form>
</div>
</div>
<script>
jQuery(document).ready(function ($) {
    var maxFileSize = 2 * 1024 * 1024; // (байт) Максимальный размер файла (2мб)
    var queue = {};
    var form = $('form#uploadImages');
    var imagesList = $('#uploadImagesList');
    var itemPreviewTemplate = imagesList.find('.item.template').clone();
    itemPreviewTemplate.removeClass('template');
    imagesList.find('.item.template').remove();
    $('#addImages1').on('change', function () {
        var files = this.files;
        var flag = 0;
        for (var i = 0; i < files.length; i++) {
            flag++;
            var file = files[i];
            ;
            var item = $('.item');
            if (item.length >= 5) {
                alert( 'Максимум 5 изображений' );
                continue;
            }
            if(flag>=6)
                {
                 continue;
                }
            if ( !file.type.match(/image\/(jpeg|jpg|png)/) ) {
                alert( 'Фотография должна быть в формате jpg, png ' );
                continue;
            }
            if ( file.size > maxFileSize ) {
                alert( 'Размер фотографии не должен превышать 2 Мб' );
                continue;
            }
            preview(files[i],flag);
        }
        this.value = '';
    });

    // Создание превью
    function preview(file,flag) {
        flag++;
        var reader = new FileReader();
        reader.addEventListener('load', function(event) {
            var img = document.createElement('img');
            var itemPreview = itemPreviewTemplate.clone();
            itemPreview.find('.img-wrap img').attr('src', event.target.result);
            itemPreview.data('id', file.name);
            imagesList.append(itemPreview);
            queue[file.name] = file;
        });
        reader.readAsDataURL(file);
    }

    // Удаление фотографий
    imagesList.on('click', '.delete-link', function () {
        var item = $(this).closest('.item'),
            id = item.data('id');
        delete queue[id];
        item.remove();
    });

    // Отправка формы
    form.on('submit', function(event) {
        var formData = new FormData(this);
        for (var id in queue) {
            formData.append('images[]', queue[id]);
        }
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            // async: true,
            success: function (res) {
                location = "/thxmoderate"
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    });
});
</script>
