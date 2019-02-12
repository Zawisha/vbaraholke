<?php
namespace App\controllers;

use App\components\Database;
use League\Plates\Engine;
use PDO;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
class LoginController
{

    private $views;

    private $database;

    private $PDO;

    public $seltok;

    private $profileController;

    private $status;

    private  $client_id = '*'; // ID приложения

    private  $client_secret = '*'; // Защищённый ключ

    private  $redirect_uri = 'http://vbaraholke.by/authvk'; // Адрес сайта

    private $url= 'http://oauth.vk.com/authorize';
    /**
     * @var RegisterController
     */


    public function __construct(Engine $views, Database $database, PDO $PDO, ProfileController $profileController
)
    {

        $this->views = $views;
        $this->database = $database;
        $this->PDO = $PDO;
        $this->profileController = $profileController;

    }

    public function vklog()
    {
        if (isset($_GET['code'])) {
            $params = array(
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'code' => $_GET['code'],
                'redirect_uri' => $this->redirect_uri
            );
            $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

            if (isset($token['access_token'])) {
                $params = array(
                    'uids' => $token['user_id'],
                    'fields' => 'uid,first_name,photo_big',
                    'version' => '5.85',
                    'access_token' => $token['access_token']
                );
            }
            $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);

            if (isset($userInfo['response'][0]['uid'])) {
                $userInfo = $userInfo['response'][0];
                $result = true;
            }
//проверка на наличие в БД

$id = $this->database->SelectVKid($userInfo['uid']);

if($id)
{
    session_start();
    $_SESSION["auth_logged_in"]=true;
    $_SESSION["auth_user_id"]=$id['0']['id'];
    $role = $this->profileController->getRoleAdmin();
    if ($role) {
        $_SESSION["admin"] = '1';
    } else {
        $_SESSION["admin"] = '0';
    }
    if(( $this->isban($_SESSION['auth_user_id']))==true)
    {
        $this->banlogout();

    }
    else
    {
        echo $this->views->render('account');
    }

}
else
{
    $password = $random = substr(md5(mt_rand()), 5, 7);

     $rand = substr(md5(mt_rand()), 5, 7);

    $email='vb-'.$rand.'@tut.by';
    $this->registerVK($password,$userInfo['first_name'],$email,$userInfo['uid']);
    $id = $this->database->SelectVKid($userInfo['uid']);
    session_start();
    $_SESSION["auth_logged_in"]=true;
    $_SESSION["auth_user_id"]=$id['0']['id'];
    echo $this->views->render('addnumbandemail');
}
        }
        else
            echo $this->views->render('login');
    }


    public function registerVK($password,$username,$email,$vkid)
    {
        $auth = new \Delight\Auth\Auth($this->PDO);

        try {
            $auth->register($email, $password, $username, function ($selector, $token) {
            });

            $this->database->updateVkid($email,$vkid);
            $this->database->updateConfirm($vkid);


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


    public function login()
    {
$params = array(
        'client_id' => $this->client_id,
        'redirect_uri'=>$this->redirect_uri,
        'response_type'=>'code'
);

        echo $this->views->render('login', ['params' => $params, 'url'=>$this->url]);
    }

    public function checkIsBanned()
    {
        $auth = new \Delight\Auth\Auth($this->PDO);
        $this->status=$auth->isBanned();
        if($this->status) {
            echo $this->views->render('login',['error' => 'Аккаунт забанен']);
            $this->auth->logout();
        }
    }

    public function isban($id)
    {
$ban=$this->database->selectIsBanById($id);
if($ban['0']['status']=='6')
{return true;}
else{return false;}
    }



    public function authoriz()
    {
        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri'=>$this->redirect_uri,
            'response_type'=>'code'
        );

        $auth = new \Delight\Auth\Auth($this->PDO);
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            try {

                if ($_POST['remember'] == 1) {
                    // keep logged in for one year
                    $rememberDuration = (int)(60 * 60 * 24 * 365);

                } else {
                    // do not keep logged in after session ends
                    $rememberDuration = null;
                }

                $auth->login($_POST['email'], $_POST['password'], $rememberDuration);

                $role = $this->profileController->getRoleAdmin();
                if ($role) {
                    $_SESSION["admin"] = '1';
                } else {
                    $_SESSION["admin"] = '0';
                }
                if(( $this->isban($_SESSION['auth_user_id']))==true)
                {
                    $this->banlogout();
                }
                else
                {
                    return $this->redirect('/account.php');
                }

                //  echo $this->views->render('home');
            } catch (\Delight\Auth\InvalidEmailException $e) {
                // echo (' wrong email address');
                echo $this->views->render('login', ['error' => 'Не верный логин или пароль','params' => $params,'url'=>$this->url]);

            } catch (\Delight\Auth\InvalidPasswordException $e) {
                // echo (' wrong password');
                echo $this->views->render('login', ['error' => 'Не верный логин или пароль','params' => $params,'url'=>$this->url]);

            } catch (\Delight\Auth\EmailNotVerifiedException $e) {
                // echo (' email not verified');
                echo $this->views->render('login', ['error' => 'Email не верифицирован','params' => $params,'url'=>$this->url]);

            } catch (\Delight\Auth\TooManyRequestsException $e) {
                // echo (' too many requests');
                echo $this->views->render('login', ['error' => 'Слишком много запросов. Попробуйте позже.','params' => $params,'url'=>$this->url]);

            }
        }
        else{
            try {

                if ($_POST['remember'] == 1) {
                    // keep logged in for one year
                    $rememberDuration = (int)(60 * 60 * 24 * 365);

                } else {
                    // do not keep logged in after session ends
                    $rememberDuration = null;
                }
                $auth->loginWithUsername($_POST['email'], $_POST['password'], $rememberDuration);
                $role = $this->profileController->getRoleAdmin();
               // session_start();
                if ($role) {
                    $_SESSION["admin"] = '1';
                } else {
                    $_SESSION["admin"] = '0';
                }

                if(( $this->isban($_SESSION['auth_user_id']))==true)
                {
                    $this->banlogout();
                }
                else
                {
                    return $this->redirect('/account.php');
                }

            } catch
                (\Delight\Auth\UnknownUsernameException $e) {
                echo $this->views->render('login', ['error' => 'Не верный логин или пароль']);

                }
				catch (\Delight\Auth\AmbiguousUsernameException $e) {
                    echo $this->views->render('login', ['error' => 'ambiguous username']);

                }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                echo $this->views->render('login', ['error' => 'Слишком много запросов, попробуйте позже']);
                            }
         }

    }

    public function authorizNewUser($email, $password)
    {
        $auth = new \Delight\Auth\Auth($this->PDO);
        $rememberDuration = (int)(60 * 60 * 24 * 365);

            try {
              $auth->login($email, $password, $rememberDuration);
                $role = $this->profileController->getRoleAdmin();
                if ($role) {
                    $_SESSION["admin"] = '1';
                } else {
                    $_SESSION["admin"] = '0';
                }
                $this->checkIsBanned();
                return $this->redirect('/account.php');
                //  echo $this->views->render('home');
            } catch (\Delight\Auth\InvalidEmailException $e) {
                // echo (' wrong email address');
                echo $this->views->render('login', ['error' => 'Не верный логин или пароль']);

            } catch (\Delight\Auth\InvalidPasswordException $e) {
                // echo (' wrong password');
                echo $this->views->render('login', ['error' => 'Не верный логин или пароль']);

            } catch (\Delight\Auth\EmailNotVerifiedException $e) {
                // echo (' email not verified');
                echo $this->views->render('login', ['error' => 'Email не верифицирован']);

            } catch (\Delight\Auth\TooManyRequestsException $e) {
                // echo (' too many requests');
                echo $this->views->render('login', ['error' => 'Слишком много запросов. Попробуйте позже.']);
            }
    }

    function redirect($path)
    {
        header("Location: $path");
        exit;
    }


    public function isLogin()
    {
        $auth = new \Delight\Auth\Auth($this->PDO);
        if ($auth->isLoggedIn()) {
            echo('user is signed in');
        }
        else {
            echo('not login');
        }
        $id = $auth->getUserId();
        echo $id;
    }


    public function logout()
    {
        $auth = new \Delight\Auth\Auth($this->PDO);

        $auth->destroySession();
        $auth->logOut();
        session_start();
        $_SESSION = array(0);
        session_destroy();
     header("Location: /");
    }

    public function banlogout()
    {
        $auth = new \Delight\Auth\Auth($this->PDO);

        $auth->destroySession();
        $auth->logOut();
        session_start();
        $_SESSION = array(0);
        session_destroy();
        echo $this->views->render('ban');
    }


    public function account()
    {
        echo $this->views->render('account');
    }

    public function resetpass()
    {
        echo $this->views->render('resetpassword');
    }


    public function respassword()
    {
        try {

            $auth = new \Delight\Auth\Auth($this->PDO);
            $auth->forgotPassword($_POST['email'], function ($selector, $token) {
                // send `$selector` and `$token` to the user (e.g. via email)
            $this->seltok = 'selector='.\urlencode($selector).'&token='.\urlencode($token);
//            echo $this->seltok;
                $this->regemail=$_POST['email'];
                echo $this->views->render('thxresetpassword');
            });

        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            echo $this->views->render('resetpassword', ['error' => 'Не верный Email адрес']);
//            echo('invalid email address');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            echo $this->views->render('resetpassword', ['error' => 'Email не верифицирован']);
//            echo(' email not verified');
        }
        catch (\Delight\Auth\ResetDisabledException $e) {
            echo $this->views->render('resetpassword', ['error' => 'Восстановлениеи пароля прервано, попробуйте ещё раз.']);
//            echo(' password reset is disabled');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
//            echo( 'too many requests');
            echo $this->views->render('resetpassword', ['error' => 'Слишком много запросов, попробуйте позже']);

        }
    }


    public function SendMail()
    {
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, "ssl"));
        $transport->setUsername('vbaraholkeby@gmail.com');
        $transport->setPassword('*');
        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message('Wonderful Subject'));
        $message->setFrom(['vbaraholkeby@gmail.com' => 'Vbaraholke.by']);
        $message->setTo($this->regemail);
        $tok = $this->seltok;
        $message->setBody('Для сброса пароля перейдите по ссылке' . 'http://localhost/respas?' . $tok.'.php');
// Send the message
        $mailer->send($message);
    }

//формируем ссылку и отправляем
    public function reslink()
    {
        $this->respassword();
       $this->sendResetEmail();

    }

    public function sendResetEmail()
    {
        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );
        $from = "admin@vbaraholke.by";
        $to = $this->regemail;
        $subject = "Reset mail";
        $tok = $this->seltok;
        $message ='Для сброса пароля перейдите по ссылке' . 'http://localhost/respas?' . $tok.'.php' ;
        $headers = "From:" . $from;
        mail($to,$subject,$message, $headers);
    }


public function respas()
{

    $auth = new \Delight\Auth\Auth($this->PDO);
    try {
        $auth->canResetPasswordOrThrow($_GET['selector'], $_GET['token']);
        echo $this->views->render('auth/reset', ['selector' => $_GET['selector'],'token' => $_GET['token']]);
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        echo $this->views->render('auth/reset',['error' => 'Не верный токен']);
        // invalid token
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
        echo $this->views->render('auth/reset',['error' => 'Токен истёк']);
        // token expired
    }
    catch (\Delight\Auth\ResetDisabledException $e) {
        echo $this->views->render('auth/reset',['error' => 'Восстановление пароля прервано']);
        // password reset is disabled
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        echo $this->views->render('auth/reset',['error' => 'Слишком много запросов']);
        // too many requests
    }
}




public function newpassword()
{
    $auth = new \Delight\Auth\Auth($this->PDO);
    try {
        $auth->resetPassword($_POST['selector'], $_POST['token'], $_POST['password']);

        echo ('password has been reset');
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        echo ('invalid token');
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
        echo ('token expired');
    }
    catch (\Delight\Auth\ResetDisabledException $e) {
        echo ('password reset is disabled');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        echo ('invalid password');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        echo ('too many requests');
    }
}

public function ChangePassword()
{
    echo $this->views->render('newpasswordlog');
}

public function NewPassLog()
{
    $auth = new \Delight\Auth\Auth($this->PDO);
    try {
        $auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);
        echo $this->views->render('finishpass', ['message'=>'пароль поменялся']);
//        echo ('password has been changed');
    }
    catch (\Delight\Auth\NotLoggedInException $e) {
        echo $this->views->render('finishpass', ['message'=>'not logged in']);
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
//        echo ('invalid password(s)');
        echo $this->views->render('finishpass', ['message'=>'не верный пароль']);
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        echo $this->views->render('finishpass', ['message'=>'слишком много запросов']);
//        echo ('too many requests');
    }
}

}



?>