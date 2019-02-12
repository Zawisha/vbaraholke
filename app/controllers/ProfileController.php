<?php

namespace App\controllers;


use App\components\Database;
use League\Plates\Engine;
use PDO;
use JasonGrimes\Paginator;

class ProfileController
{

    private $views;

    private $PDO;

    public $islog;

    public $name;

    public $id;

    public $role;

    private $database;

    private $status;

    public function __construct(Engine $views, PDO $PDO, Database $database )
    {
        $this->views = $views;
        $this->PDO = $PDO;
        $this->database = $database;
    }

public function isLoggedIn()
{
    $auth = new \Delight\Auth\Auth($this->PDO);
    if ($auth->isLoggedIn()) {
     return ('user is signed in');
    }
    else {
        return (' user is *not* signed in yet');
    }
}

public function userID()
    {
    $auth = new \Delight\Auth\Auth($this->PDO);
    $id = $auth->getUserId();
    return $id;
    }

    public function emailAdress()
    {
        $auth = new \Delight\Auth\Auth($this->PDO);
        $email = $auth->getEmail();
        return  $email;
    }

public function displayName()
{
    $auth = new \Delight\Auth\Auth($this->PDO);
    $name = $auth->getUsername();
    return $name;
}



public function userInformation()
{
   $this->islog=$this->isLoggedIn();
    $this->name=$this->displayName();
    $this->id=$this->userID();
$this->role=$this->getRoleAdmin();
    $auth = new \Delight\Auth\Auth($this->PDO);
$this->status=$auth->isBanned();
   echo $this->views->render('account',['isloged' =>$this->islog,'name' => $this->name, 'id'=>$this->id, 'role'=>$this->status]);
}

    public function getRoleAdmin()
    {
    $auth = new \Delight\Auth\Auth($this->PDO);
        if ($auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            return true;
        }
        else
        {
            return false;
        }

    }

    public function adminInsert()
    {
        $this->role=$this->getRoleAdmin();
        echo $this->views->render('admin',['role'=>$this->role]);
    }

    public function addAdmin()
    {
        if($this->getRoleAdmin())
        {
            $this->database->addAdmin('qwerty@tut.by');
            echo $this->views->render('addadmin');
        }
        else
        {
            echo $this->views->render('accepterror');
        }
    }

    public function showuserposts()
    {
        $this->id = $this->userID();
        if (isset($_GET['page'])) {
            $currentPage = $_GET['page'];
        } else {
            $currentPage = '1';
        }
        $urlPattern = '/userposts?page=(:num)';
        $itemsPerPage = 8;
        //текущая страница
        $totalItems = $this->database->getCountUserPost($this->id);
        if ($totalItems != '0') {
            $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
            //вытаскиваю все посты
            $posts = $this->database->getAllPostsLikeUser($currentPage, $this->id);
            foreach ($posts as $key => $postimage) {
                $this->arrimage[] = $postimage['idpost'];
            }
            $images = $this->database->SelectImages('images', $this->arrimage);
            echo $this->views->render('userposts', ['posts' => $posts, 'paginator' => $paginator, 'images' => $images]);
        }
        else{
            echo $this->views->render('userposts', ['zero' => '1']);
        }
    }

    public function showNotMineUserPosts()
    {
        $this->id = $_GET['id'];
        if (isset($_GET['page'])) {
            $currentPage = $_GET['page'];
        } else {
            $currentPage = '1';
        }
        $urlPattern = '/notmineuserposts?page=(:num)&id='.$_GET['id'];
        $itemsPerPage = 8;
        //текущая страница
        $totalItems = $this->database->getCountUserPost($this->id);
        if ($totalItems != '0') {
            $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

            //вытаскиваю все посты
            $posts = $this->database->getAllPostsLikeUser($currentPage, $this->id);


            foreach ($posts as $key => $postimage) {
                $this->arrimage[] = $postimage['idpost'];
            }

            $images = $this->database->SelectImages('images', $this->arrimage);

            echo $this->views->render('notmineuserposts', ['posts' => $posts, 'paginator' => $paginator,
                'images' => $images,]);
        }
        else{
            echo $this->views->render('notmineuserposts', ['zero' => '1']);

        }

    }

    public function contacts()
    {
        echo $this->views->render('contacts');

    }

    public function rules()
    {
        echo $this->views->render('rules');

    }

    public function users()
    {
       // var_dump($_GET);
        if(isset($_GET['id'])){
            $user=$_GET['id'];
        }
        else
        {
            $user='1';
        }
            $username=$this->database->SelectUserNameById('users',$user);
if(empty($username))
{
    header("location: /");
}
else
{

$region = $this->database->selectRegion($username['0']['region']);
    $username['0']['region']=$region['0']['region'];
    echo $this->views->render('user', ['username' => $username, 'userid'=>$user]);

}
    }



    public function chat()
    {
        if(isset($_POST['mess']) && $_POST['mess']!="" && $_POST['mess']!=" " && (strlen($_POST['mess']))<500) {
            $date= time();
            $date+=3600;
            $this->database->insertChat($_POST['myid'],$_POST['hisid'],$_POST['mess'],$date);
           $mymes['0']= date('Y-m-d H:i:s',$date);
            $mymes['1']=$_POST['mess'];
            echo json_encode($mymes);
        }
    }

    public function loadHisMesses()
    {
        $messages=$this->database->selectMesses($_POST['hisid'],$_POST['myid']);
        $countarray=count($messages);
        for ($i = 0; $i < $countarray; $i++) {
            $messages[$i]['time']= date('Y-m-d H:i:s',$messages[$i]['time']);
        }

        for ($i = 0; $i < $countarray; $i++) {
if($messages[$i]['unread']=='1') {
    $this->database->updateUnread($messages[$i]['id']);
}
        }
       echo json_encode($messages);
    }

    public function allloadmesses()
    {
        $messages=$this->database->loadSelectMesses($_POST['hisid'],$_POST['myid']);
        $countarray=count($messages);
                if($countarray>200)
        {
            $countarray1=$countarray-200;
            for ($i = 0; $i < $countarray1; $i++) {
                array_splice($messages, 0, 1);
            }
            $countarray=count($messages);
        }
        for ($i = 0; $i < $countarray; $i++) {
            $messages[$i]['time']= date('Y-m-d H:i:s',$messages[$i]['time']);
        }
        //var_dump($messages);
        for ($i = 0; $i < $countarray; $i++) {

            $this->database->updateUnread($messages[$i]['id']);
        }
      echo json_encode($messages);
    }

    public function loadnmess()
    {
        $messages=$this->database->selectUnreadMesses($_POST['myid']);
        $countarray=count($messages);
        echo json_encode($countarray);
    }

    public function showmessages()
    {
        echo $this->views->render('showmessages');

    }
    public function allloadnmess()
    {
        $messages=$this->database->loadUnreadMesses($_POST['myid']);
        $countarray=count($messages);
        $writeid=[];

        for ($i = 0; $i < $countarray; $i++) {
            $writeid[$i]=$messages[$i]['myid'];
        }

       $writeid= array_unique($writeid);
        $countarray=count($writeid);
       $usersarr=[];
        for ($i = 0; $i < $countarray; $i++) {
            $usersarr[$i]['id']=$writeid[$i];
           $nick=$this->database->SelectUserNameById('users',$usersarr[$i]['id']);
            $usersarr[$i]['nick']=$nick['0']['username'];
        }
        echo json_encode($usersarr);
    }


}