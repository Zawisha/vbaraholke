<?php

namespace App\controllers;

use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException ;
use App\components\Database;
use League\Plates\Engine;
class ValidationController
{
    private $database;
    /**
     * @var Engine
     */
    private $views;

    public function __construct(Database $database, Engine $views)
{
    $this->database = $database;
    $this->views = $views;
}

    //проверка на длину строки
    public function length($header,$a,$z)
    {
        $validator = Validator::length($a, $z);
        $validator ->validate($header);
        try {
            $validator->assert($header);
        }
        catch (NestedValidationException $ex)
        {
            return $messages = $ex->getFullMessage();
        }
    }

    //проверка на число
    public function numeric($numb)
    {
        //здесь может быть ошибка
        $validator = Validator::numeric();
        $validator ->validate($numb);
        try {
            $validator->assert($numb);
        }
        catch (NestedValidationException $ex)
        {
            return $messages = $ex->getFullMessage();
        }
    }

    //проверка на совпадение категорий, региона
    public function between($numb,$a,$z)
    {
        $validator = Validator::between($a, $z);
        $validator ->validate($numb);

        try {
            $validator->assert($numb);
        }
        catch (NestedValidationException $ex)
        {
            return $messages = $ex->getFullMessage();
        }
    }
    //очистка данных от html и php тегов
    public function diehtml($value)
    {
        //убирает html теги
        $value = strip_tags($value);
        //преобразует спец. символы в html сущности
        $value = htmlspecialchars($value);
        //удалим пробелы из начала и конца строки
        $value = trim($value);
        return $value;
    }

    public function validateEmailandNumber()
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
            if( preg_match( '~[A-Z]~', $_POST['phone-number']) ||
                preg_match( '~[a-z]~', $_POST['phone-number'])  ){
            $flag='1';
            }
            else
            {
                $flag='0';
            }
            if($flag=='0')
            {
            $this->database->updateNumber($_SESSION["auth_email"],$_POST['phone-number']);
      }

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->database->updateEmail($_SESSION["auth_email"],$_POST['email']);
       }
        $_SESSION["auth_email"]=$_POST['email'];
        header("Location: /account.php");

    }

    public function showValidateEmailandNumber()
        {
            echo $this->views->render('addnumbandemail');
        }



}