<?php

namespace App\controllers;

use App\components\Database;
use Intervention\Image\ImageManager;
use League\Plates\Engine;
use Respect\Validation\Validator;

class ImageController
{


    private $views;

    private $profileController;

    public $id;

    private $imageManager;

    public $message ;

    private $database;

    private $validationController;

    public $status;


    public function __construct(Engine $views, ProfileController  $profileController, Database $database, ValidationController $validationController)
    {

        $this->views = $views;
        $this->profileController = $profileController;
        $this->database = $database;
        $this->validationController = $validationController;
    }


    public function avaimage()
    {

        echo $this->views->render('uploadimage');
    }


    public function uploadAvaImage()
    {
        $this->message='ok';
      if(($_FILES['userfile']['size'])==0)

      { $this->message='Выберите файл для загрузки, допустимые форматы jpg, png';}

else {
    if ((($_FILES['userfile']['type']) == 'image/jpeg') || (($_FILES['userfile']['type']) == 'image/png')|| (($_FILES['userfile']['type']) == 'image/jpg'))
    {
        $this->message=='ok';

    }
    else {
        $this->message = 'Допустимые форматы jpg, png';
    }
}

        if($this->message!='ok') {
       echo $this->views->render('account',['zero'=>$this->message]);
      }
        else {
            $this->message='1';
            $this->id = $this->profileController->userID();
            $uploaddir = 'images/ava/' . $this->id . '.jpg';
            $r = $_FILES['userfile']['tmp_name'];
            move_uploaded_file($r, "$uploaddir");
            $manager = new ImageManager(array('driver' => 'gd'));
            $img = $manager->make('images/ava/' . $this->id . '.jpg');
            $img->save('images/ava/' . $this->id . '.jpg');
            echo $this->views->render('account',['zero'=>$this->message]);
        }
        }

    function redirect($path)
    {
        header("Location: $path");
        exit;
    }


    public function gotoPost()
    {
        if(isset($_SERVER ["HTTP_REFERER"])) {
            $urlref = ($_SERVER ["HTTP_REFERER"]);
        }
        else
            $urlref ='/';

        if(!isset($_GET['numb']))
        {
            header("Location: /");
        }

        else {
            $postnumber = $this->database->SelectPost('posts', $_GET['numb']);
$username=$this->database->SelectUserNameById('users', $postnumber['0']['userid']);
            if (empty($postnumber))
            {
                header("Location: /");
            }
            //вытаскиваю все картинки
else {

    $images = $this->database->SelectOnePostImage('images', $_GET['numb']);
    $this->status = $this->profileController->getRoleAdmin();
$categortovarov = $this->database->CategoriiTovarov($postnumber['0']['section']);

    echo $this->views->render('viewpost', ['id' => $postnumber, 'url1' => $urlref,
        'images' => $images, 'status' => $this->status, 'username' =>$username, 'categortovarov'=>$categortovarov]);
}
}
    }

    public function valFormPost()
    {
        $param_header=  $_POST['header'];
        $param_description =  $_POST['description'];
        $param_price =  $_POST['price'];
        $param_region =  $_POST['region'];
        $param_city =  $_POST['city'];
        $param_category = $_POST['category'];
        $param_condition =  $_POST['condition'];

            echo $this->views->render('addimagepost',['param_header'=>$param_header,'param_description'=>$param_description,'param_price'=>$param_price,'param_region'=>$param_region,
                'param_city'=>$param_city,'param_category'=>$param_category,'param_condition'=>$param_condition]);

//        }

    }

   
    public function uplImgJs()
    {

        //получаю id юзера
       $id=$this->profileController->userId();
        $time=time();
        //загружаю все значения в бд
       $this->database->insertAllPostParams($_POST['param_header'],$_POST['param_description'],$_POST['param_price'],$_POST['param_region'],$_POST['param_city'],$_POST['param_category'],$_POST['param_condition'], $id,$time);

//получаю количество загруженных фото
        //флаг валидации загруженных фото
        $flags=0;
        $countphoto=(count($_FILES["images"]["name"]))-1;
        //проверка на количество фоток
        if ($countphoto<6) {

            for ($i = 0; $i <= $countphoto; $i++) {
                if ((($_FILES['images']['type'][$i]) != 'image/jpeg') && (($_FILES['images']['type'][$i]) != 'image/png') && (($_FILES['images']['type'][$i]) != 'image/jpg')) {
                    $flags = 1;
                }
            }
//проверка на размер изображения
            for ($i = 0; $i <= $countphoto; $i++) {
                if ((($_FILES['images']['size'][$i]) > 2000000) || (($_FILES['images']['size'][$i]) == 0)) {
                    $flags = 1;
                }
            }

        }
        else
        {
            $flags = 1;
        }
            if($flags==0) {

//получаю последний номер поста
                $postnumber = $this->database->SelectLast();

                for ($i = 0; $i <= $countphoto; $i++) {
                    $this->database->insertPost($postnumber);

                    $lastImg = $this->database->SelectLastImage();
                    $this->id = $lastImg;
//                    $uploaddir = '../app/images/posts/' . $this->id . '.jpg';
                    $uploaddir = 'images/posts/' . $this->id . '.jpg';
                    $r = $_FILES["images"]["tmp_name"][$i];
                    move_uploaded_file($r, "$uploaddir");
                }
            }
            else
                {echo $this->views->render('error');}
    }


    public function thxmod()
    {
        echo $this->views->render('thxmoderate');
    }

    public function error()
    {
        echo $this->views->render('error');
    }

    public function avajs()
    {
     echo $numarray=(count($_FILES['images']["name"]))-1;
        if ((($_FILES['images']['type']['0']) != 'image/jpeg') && (($_FILES['images']['type']['0']) != 'image/png') && (($_FILES['images']['type']['0']) != 'image/jpg')) {
                    $flags = 1;
                }
//проверка на размер изображения
                if ((($_FILES['images']['size']['0']) > 2000000) || (($_FILES['images']['size']['0']) == 0)) {
                    $flags = 1;
                }
        $id=$this->profileController->userId();
                $uploaddir = 'images/ava/' . $id . '.jpg';
                $r = $_FILES["images"]["tmp_name"][$numarray];
                move_uploaded_file($r, "$uploaddir");
    }
}