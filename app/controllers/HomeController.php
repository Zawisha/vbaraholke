<?php

namespace App\controllers;



use App\components\Database;
use JasonGrimes\Paginator;
//шаблонизатор
use League\Plates\Engine;
use PDO;


class HomeController
{



    private $views;

    private $queryFactory;

    private $database;

    private $paginator;

    public $arrimage=[];

    private $profileController;

    public $status;

    private $userstatus;

    private $PDO;

    public function __construct(Engine $views, Database $database, ProfileController $profileController, PDO $PDO)
    {

        $this->views = $views;
        $this->database = $database;

        $this->profileController = $profileController;
        $this->PDO = $PDO;
    }


    public function show()
    {

      $myTask = "TORA";
     echo $this->views->render('task', ['tora' => $myTask]);

    }
public function image()

{
    $myTask = "TORA";
    echo $this->views->render('image', ['tora' => $myTask]);
}



// домашний контроллер от индекса
    public function index()
    {
        $auth = new \Delight\Auth\Auth($this->PDO);
        $this->userstatus=$auth->isBanned();
if(($this->userstatus)) {
    $auth->logOut();
}
else {
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    //ищу город
    $city = isset($_GET['city']) ? $_GET['city'] : '';
    //ищу категорию
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    $sortby = isset($_GET['sortby']) ? $_GET['sortby'] : 1;

    $cities = $this->database->Select('regions');
    $categories = $this->database->Select('category');


    $itemsPerPage = 8;
    $currentPage = $page;
    $urlPattern = '/?page=(:num)&city=' . $city . '&category=' . $category . '&sortby=' . $sortby;

    $totalItems = $this->database->getCount('posts', $city, $category);
    //защита. если введут левые параметры в адресную строку на место page

    if ($page <= '0') {
        echo $this->views->render('home', ['zeroitems' => '0', 'cities' => $cities, 'categories' => $categories]);

    } else {


        if (ceil(($totalItems / $itemsPerPage)) < $page) {
            echo $this->views->render('home', ['zeroitems' => '0', 'cities' => $cities, 'categories' => $categories]);
        } else {
            if ($totalItems == '0') {
                echo $this->views->render('home', ['zeroitems' => '0', 'cities' => $cities, 'categories' => $categories]);
            } else {
                $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
                $paginator->setMaxPagesToShow(3);
                $posts = $this->database->SelectAll('posts', $currentPage, $city, $category, $sortby);

                //вытаскиваю картинки

                foreach ($posts as $key => $postimage) {
                    $this->arrimage[] = $postimage['idpost'];
                }


                $images = $this->database->SelectImages('images', $this->arrimage);

                echo $this->views->render('home', ['posts' => $posts, 'paginator' => $paginator, 'cities' => $cities, 'categories' => $categories, 'images' => $images,
                ]);
            }

        }
    }
}
    }


    public function addPost()
    {

        $auth = new \Delight\Auth\Auth($this->PDO);
        $this->userstatus = $auth->isBanned();
        if (($this->userstatus)) {
            $auth->logOut();
        } else {
            $cities = $this->database->Select('regions');
            array_splice($cities, 0, 1);
            $categories = $this->database->Select('category');
            $urlref = ($_SERVER ["HTTP_REFERER"]);
            echo $this->views->render('addpost', ['categories' => $categories, 'cities' => $cities, 'url1' => $urlref]);
        }
    }

    public function gotoDelete()
    {
        if(isset($_GET['numbpost'])){
            $numbpost=$_GET['numbpost'];


        $this->status=$this->profileController->getRoleAdmin();
        if($this->status)
        {
           $images=$this->database->SelectOnePostImage('images',$numbpost);

            foreach ($images as $img)
           {
                unlink ( 'images/posts/'.$img['id'].'.jpg');
            }

           $this->database->deleteOnePost($numbpost);
          $this->database->deleteImages($numbpost);
        }
      header("location: /");
        }
    else
        {
            header("location: /");
        }


    }

    public function test()
    {
        echo $this->views->render('test');

    }

    public function getNumber()
    {
        $numb=$this->database->SelectNumberById($_POST['myid']);
        $numb=$numb['0']['telnumber'];
        if($numb==null)
        {
            $numb='Не указан';
        }
        echo $numb;

    }


}