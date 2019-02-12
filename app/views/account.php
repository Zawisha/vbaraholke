<?php $this->layout('layout') ;
if(!isset($_SESSION))
{
    session_start();
}
if(!isset($_SESSION ["auth_logged_in"])){
    header("Location: /login.php");
}
    if (isset($_SESSION['admin'])&&($_SESSION['admin']=='1'))
    {

    ?>
<div class="container">
    <div class="row ">
        <div class="col-12 adminka">
            <a href="admin">Админка</a>
        </div>
    </div>
</div>
<?php
}

?>
    <div class="container">
        <div class="row name">
            <div class="col-12 username">
                <?php
                echo $_SESSION['auth_username'];
                ?>
            </div>
        </div>

        <form action="/avajsload" method="post" enctype="multipart/form-data" id="uploadImages">
        <div class="row userprof ">
            <div class="col-xl-3 col-lg-3 col-md-4 userava " id='refresh'>
            <label for="addImages" ><img src="images/ava/<?php

                if (file_exists('images/ava/'.$_SESSION['auth_user_id'].'.jpg')) {
                    echo $_SESSION['auth_user_id'];
                }
                else
                {
                    echo '0';
                }

                ?>.jpg<?php echo'?rnd'.rand(1,100000)?> " id="imgavatar" /></label>
            <input type="file" id="addImages" name="addImages" /><br />
            </div>
            <input type="hidden" name="param_array" value="<?php echo implode(',',$param_array); ?>">

            <ul id="uploadImagesList">
                <li class="item template">
            <span class="img-wrap">
                 <div class="col-xl-3 col-lg-3 col-md-4 userava " id='refresh'>
                <label for="addImages" id="firstlabel"><img src="image.jpg" alt="123"></label>
                      <input type="file" id="addImages" name="addImages" /><br />
                </div>
            </span>

                </li>
            </ul>

            </div>

        </form>
                <div class="col-xl-12 col-lg-9 col-md-8 usermenu">
            <div class="col-xl-3 col-lg-5 col-md-8 chpass">
                <a href="/changepass">Поменять пароль</a>
            </div>
                    <div class="col-xl-3 col-lg-5 col-md-8 chpass">
                        <a href="/validaddnumberandemail">Поменять Email и телефон</a>
                    </div>
            <div class="col-xl-3 col-lg-5 col-md-8 logout">
                   <a href="logout.php">Выйти</a>
            </div>
                </div>
        </div>
    </div>


<div class="container">
    <div class="row ">
        <div class="col-12 myob">
            <a href="/userposts">Мои объявления</a>
        </div>
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

        $('#addImages').on('change', function () {
            var files = this.files;
            var flag = 0;

            for (var i = 0; i < 1; i++) {
                flag++;
                var file = files[i];
                ;
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
           var element=document.getElementsByClassName('img-wrap')[0];
            var element1=document.getElementsByClassName('item')[0];
           if(element) {

               element.remove();
              element1.remove();
           }

            var reader = new FileReader();
            reader.addEventListener('load', function(event) {
                var img = document.createElement('img');
                var itemPreview = itemPreviewTemplate.clone();
                itemPreview.find('.img-wrap img').attr('src', event.target.result);
                itemPreview.data('id', file.name);
                imagesList.append(itemPreview);
                queue[file.name] = file;
                var formData = new FormData();
                for (var id in queue) {
                    formData.append('images[]', queue[id]);
                    console.log(id);
                }
                console.log(formData);
                $.ajax({
                    url: '/avajsload',
                    type: 'POST',
                    data: formData,
                    async: true,
                    success: function (data) {
                        console.log(data);
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
            reader.readAsDataURL(file);
            document.getElementById('refresh' ).style.display = 'none';
        }
    });
</script>




