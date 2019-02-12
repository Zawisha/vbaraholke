<?php $this->layout('layout') ;
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

else
{

?>
<div class="container">
        <form action="/changeusers" method="POST">

        <?php

        foreach ($users

        as $user) {
        ?>
                <div class="row addnewpost  justify-content-center">
                    <div class="userframe">
                <div class="col-12 addnewuserposttext">
                    <?php
                    echo 'id=';
                    echo $user['id'] . '<br>';
                    echo $user['email'] . '<br>';
                    echo $user['username'] . '<br>';
                    ?>
                </div>
                <div class="col-12 addnewuserpostinput">
                    <p><select name="<?php echo('status' . $user['id']) ?>">
                            <option disabled>Выберите статус</option>
                            <option value="0" <?php if ($user['status'] == '0') {
                                echo 'selected="selected"';
                            } ?> >Нормальный
                            </option>
                            <option value="6" <?php if ($user['status'] == '6') {
                                echo 'selected="selected"';
                            } ?> >Забанен
                            </option>
                        </select></p>
                </div>

                <div class="col-12 addnewuserpostinput">
                    <p><select name="<?php echo('verified' . $user['id']) ?>">
                            <option disabled>Верификация(по email)</option>
                            <option value="0" <?php if ($user['verified'] == '0') {
                                echo 'selected="selected"';
                            } ?> >Не верифицирован
                            </option>
                            <option value="1"<?php if ($user['verified'] == '1') {
                                echo 'selected="selected"';
                            } ?> >Верифицирован
                            </option>
                        </select></p>
                </div>
                <div class="col-12 addnewuserpostinput">
                    <p><select name="<?php echo('roles_mask' . $user['id']) ?>">
                            <option disabled>Дать админку</option>
                            <option value="0" <?php if ($user['roles_mask'] == '0') {
                                echo 'selected="selected"';
                            } ?> >Простой юзер
                            </option>
                            <option value="1" <?php if ($user['roles_mask'] == '1') {
                                echo 'selected="selected"';
                            } ?> >Админ
                            </option>
                        </select></p>
                </div>
                <div class="col-12 addnewuserposttext">
                    <?php
                    echo 'date=';
                    echo date('d-m-Y', $user['last_login']);
                    ?>
     </div>
                            </div>
                </div>
            <?php
                    }}
                    ?>
        <input type="hidden" name="users" value="<?php echo (base64_encode(serialize($users))); ?>">


                            <div class="col-12 addnewuserpostsubmit">
        <button  type="submit">Подтвердить настройки</button>
                            </div>
    </form>
                        <div class="container">
                            <div class="row paginator d-flex justify-content-center ">
                                <?php
                                echo $paginator;

                                ?>
                            </div>
                        </div>


    </div>
</div>

