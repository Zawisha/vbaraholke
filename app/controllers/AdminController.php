<?php

namespace App\controllers;

use League\Plates\Engine;
use App\components\Database;
use JasonGrimes\Paginator;


class AdminController
{


    private $views;

    private $database;

    public $arrimage=[];

    public function __construct(Engine $views, Database $database)
    {
        $this->views = $views;
        $this->database = $database;

    }

    public function showUsers()
    {

        if (isset($_GET['page'])) {
            $currentPage = $_GET['page'];
        } else {
            $currentPage = '1';
        }
        $itemsPerPage = 8;

        $urlPattern = '/showallusers?page=(:num)';
        $totalItems =$this->database->getCountUsers();
        $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
        $users =  $this->database->getAllUsers($currentPage);
        echo $this->views->render('showallusers', ['users' =>  $users, 'paginator'=>$paginator]);

    }

    public function changeusersLikeAdmin()
    {
        $urlref =($_SERVER ["HTTP_REFERER"]);

      $users=unserialize(base64_decode($_POST["users"]));
$arruser=[];
        for ($i = 0; $i <= 7; $i++) {

            //нахожу id человека
            if(isset($users[$i]['id'])) {
                $numb = $users[$i]['id'];

                if ($users[$i]["status"] != $_POST["status" . $numb]) {
                    $arruser[] = ('поменял' . $numb . 'status');
                    $this->database->adminupdateUser('users', 'status', $_POST["status" . $numb], 'id', $numb);
                }

                if ($users[$i]["verified"] != $_POST["verified" . $numb]) {
                    $arruser[] = ('поменял' . $numb . 'верификацию');
                    $this->database->adminupdateUser('users', 'verified', $_POST["verified" . $numb], 'id', $numb);
                }

                if ($users[$i]["roles_mask"] != $_POST["roles_mask" . $numb]) {
                    $arruser[] = ('поменял' . $numb . 'админку');
                    $this->database->adminupdateUser('users', 'roles_mask', $_POST["roles_mask" . $numb], 'id', $numb);
                }

            }
        }
        echo $this->views->render('changeusers', ['users' =>  $arruser, 'urlref'=>$urlref]);

    }

public function adminpost()
{

    if (isset($_GET['page'])) {
        $currentPage = $_GET['page'];
    } else {
        $currentPage = '1';
    }
    $urlPattern = '/adminposts?page=(:num)';
    $itemsPerPage = 8;
    $totalItems =$this->database->getCountNotAvPost();

    if($totalItems!='0') {
        $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

        //вытаскиваю все посты
        $posts = $this->database->getAllPostsLikeAdmin($currentPage);


        foreach ($posts as $key => $postimage) {
            $this->arrimage[] = $postimage['idpost'];
        }

        $images = $this->database->SelectImages('images', $this->arrimage);


        echo $this->views->render('adminposts', ['posts' => $posts, 'paginator' => $paginator, 'images' => $images]);
    }
    else
    {
        echo $this->views->render('adminposts', ['totalitems'=>$totalItems]);

    }

}

public function changePostsLikeAdmin()
{
     $posts_arr[]=$_POST;
    foreach ($posts_arr as $elem) {
        foreach ($elem as $key=>$elem1) {


            if($elem1=='1')
            {
                preg_match_all('#status([0-9]+)#', $key, $m);
                $this->database->updatePost($m[1][0]);
            }
            if($elem1=='0')
            { preg_match_all('#status([0-9]+)#', $key, $m);
                $this->database-> deleteOnePost($m[1][0]);
            }

                }
        }
        $this->adminpost();

    }



}