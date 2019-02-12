<?php

namespace App\controllers;



use App\components\Database;
use Aura\SqlQuery\QueryFactory;

use Delight\Auth\Auth;
use League\Plates\Engine;

use PDO;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;


class RegisterController
{


    private $PDO;

    private $queryFactory;

    public $seltok;

    public $regemail;
    /**
     * @var Database
     */
    private $database;
    /**
     * @var LoginController
     */
    private $loginController;

    private  $client_id = '6721477'; // ID приложения

    private  $redirect_uri = 'http://vbaraholke.by/authvk'; // Адрес сайта

    private $url= 'http://oauth.vk.com/authorize';

    public function __construct(Engine $views, PDO $PDO, QueryFactory $queryFactory, Database $database, LoginController $loginController)
    {

        $this->views = $views;
        $this->PDO = $PDO;
        $this->queryFactory = $queryFactory;
        $this->database = $database;
        $this->loginController = $loginController;
    }

    public function showForm()
    {
        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri'=>$this->redirect_uri,
            'response_type'=>'code'
        );

        echo $this->views->render('auth/register', ['params' => $params, 'url'=>$this->url]);
    }

//
    public function register()
    {
        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri'=>$this->redirect_uri,
            'response_type'=>'code'
        );

        $auth = new \Delight\Auth\Auth($this->PDO);

    try {
       $auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
            // send `$selector` and `$token` to the user (e.g. via email)
    $this->seltok = 'selector='.\urlencode($selector).'&token='.\urlencode($token);
    $this->regemail=$_POST['email'];
                });
        echo $this->views->render('thxregistration');


    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      //  echo 'invalid email address';
        echo $this->views->render('auth/register',['error' => 'Не верный Email','params' => $params, 'url'=>$this->url]);

    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      //  echo 'invalid password';
        echo $this->views->render('auth/register',['error' => 'Не верный пароль','params' => $params, 'url'=>$this->url]);


    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      //  echo 'user already exists';
        echo $this->views->render('auth/register',['error' => 'Пользователь уже существует','params' => $params, 'url'=>$this->url]);

    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      //  echo 'too many requests';
        echo $this->views->render('auth/register',['error' => 'Слишком много запросов.Попробуйте позже.','params' => $params, 'url'=>$this->url]);

    }


    }

    public function registerVK($password,$username)
    {
        $auth = new \Delight\Auth\Auth($this->PDO);

        try {
            $auth->register('vk@mail.com', $password, $username, function ($selector, $token) {
            });

        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            //  echo 'invalid email address';
            echo $this->views->render('auth/register',['error' => 'Не верный Email']);

        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            //  echo 'invalid password';
            echo $this->views->render('auth/register',['error' => 'Не верный пароль']);


        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            //  echo 'user already exists';
            echo $this->views->render('auth/register',['error' => 'Пользователь уже существует']);

        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            //  echo 'too many requests';
            echo $this->views->render('auth/register',['error' => 'Слишком много запросов.Попробуйте позже.']);

        }


    }


    public function sendtestmail()
    {
        ini_set( 'display_errors', 1 );

        error_reporting( E_ALL );


    $from = "admin@vbaraholke.by";
    $to = $this->regemail;
    $subject = "Checking PHP mail";
        $tok = $this->seltok;
    $message ='Для подтверждения регистрации перейдите по ссылке http://vbaraholke.by/confirm?' . $tok.'.php' ;
    $headers = "From:" . $from;
    mail($to,$subject,$message, $headers);

    }


    public function SendMail()
    {
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, "ssl"));
         $transport->setUsername('vbaraholkeby@gmail.com');
        $transport->setPassword('*');
        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message('vbaraholke message'));
          $message->setFrom(['vbaraholkeby@gmail.com' => 'Vbaraholke.by']);
         $message->setTo($this->regemail);
         $tok = $this->seltok;
         $message->setBody('http://vbaraholke.by/confirm?' . $tok.'.php');

        $result= $mailer->send($message);
        if (!$mailer->send($message, $failures))
        {
            echo "Failures:";
            print_r($failures);
        }
    }

    public function RegAndSend()
    {
        if( preg_match( '~[A-Z]~', $_POST['phone-number']) ||
            preg_match( '~[a-z]~', $_POST['phone-number'])  ){
            $flagnumb='1';
        } else {
            $flagnumb='0';
        }



        if ((isset($_POST['username']))&&($flagnumb=='0'))
        {
        if ((strlen($_POST['username']) < 20) && (strlen($_POST['username']) > 0)) {
        $this->register();
        $this->sendtestmail();
        $this->database->updateUser('users','timepass',$_POST['password'],'email',$_POST['email'],'333');
            $this->database->updateNumber($_POST['email'], $_POST['phone-number']);
    }
    else
    {
        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri'=>$this->redirect_uri,
            'response_type'=>'code'
        );
        echo $this->views->render('auth/register',['error' => 'Логин не более 20 символов.','params' => $params, 'url'=>$this->url]);
    }
         }
    }

    public function confirm()
    {
        $auth = new \Delight\Auth\Auth($this->PDO);
        try {
            $_GET['token'] = substr($_GET['token'], 0, -4);
            $reg = $auth->confirmEmail($_GET['selector'], $_GET['token']);
    $pass=$this->database->SelectNewTimePass($reg['1']);
            $this->database->updateUser('users','timepass','q','email',$reg['1']);
            $this->loginController->authorizNewUser($reg['1'], $pass['0']['timepass']);
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            echo $_GET['selector'];
            echo $_GET['token'];
            echo ('Не верный токен');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            echo ('Время токена истекло');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            echo ('Такой email уже существует');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            echo ('Слишком много запросов, попробуйте позже');
        }
    }

}