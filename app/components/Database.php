<?php

namespace App\components;

use Aura\SqlQuery\QueryFactory;
use PDO;

class Database
{


    private $PDO;

    private $queryFactory;

    public $sectionprepare;

    public $regionprepare;

    public function __construct(PDO $PDO, QueryFactory $queryFactory)
    {
        $this->PDO = $PDO;
        $this->queryFactory = $queryFactory;

    }


    public function Select($table)
    {
        $select =  $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table);
        // установим кодировку
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    //главная функция поиска
    public function SelectAll($table, $numoffsets,$region,$section,$sortby)
    {
     $noffset = ($numoffsets - 1)*8;

     //выборка всех при загрузке главной страницы


            if($section==''||$section==0)
            {
             $this->sectionprepare=17;
            }
            else
            {
             $this->sectionprepare=0;
            }

        if($region==''||$region==0)
        {
            $this->regionprepare=7;
        }
        else
        {
            $this->regionprepare=0;
        }

        if ($sortby==0)
        {
            $sortby='price ';
        }
        else
        {$sortby='timeadd desc';}



            $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->limit(8)
            ->where('section = :section')
            ->orWhere('section < :s0')
            ->where('accept = :accept')
            ->having('region = :region')
            ->orHaving('region < :r0')
            ->orderBy(array($sortby))
            ->bindValue('s0',$this->sectionprepare)
            ->bindValue('section', $section)
            ->bindValue('r0',$this->regionprepare)
            ->bindValue('region',$region)
            ->bindValue('accept','1')
//                ->bindValue('sort','price')
            ->offset($noffset);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);



    }

    public function getCount($table,$region,$section)
    {

        if($section==''||$section==0)
        {
            $this->sectionprepare=17;
        }
        else
        {
            $this->sectionprepare=0;
        }

        if($region==''||$region==0)
        {
            $this->regionprepare=7;
        }
        else
        {
            $this->regionprepare=0;
        }


        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where('section = :section')
            ->orWhere('section < :s0')
            ->where('accept = :accept')
            ->having('region = :region')
            ->orHaving('region < :r0')
            ->bindValue('s0',$this->sectionprepare)
            ->bindValue('section', $section)
            ->bindValue('r0',$this->regionprepare)
            ->bindValue('region',$region)
            ->bindValue('accept','1');
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return count($sth->fetchAll(PDO::FETCH_ASSOC));

    }

    public function SelectImages($table, $id)
    {

        $arr = $id;
        $in  = str_repeat('?,', count($arr) - 1) . '?';
        $sql="SELECT * FROM ($table) WHERE postid IN ($in)";
        $stm = $this->PDO->prepare($sql);
        $stm->execute($arr);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function SelectPost($table,$id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where('idpost = :id')
            ->bindValue('id', $id);

        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);

    }

    public function SelectOnePostImage($table,$id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['id'])
            ->from($table)
            ->where('postid = :id')
            ->bindValue('id', $id);

        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function SelectNewTimePass($email)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['timepass'])
            ->from('users')
            ->where('email = :id')
            ->bindValue('id', $email);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function SelectVKid($vkid)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['id'])
            ->from('users')
            ->where('vkid = :vkid')
            ->bindValue('vkid', $vkid);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function CategoriiTovarov($number){
        $select = $this->queryFactory->newSelect();
        $select->cols(['categorytov'])
            ->from('category')
            ->where('number = :number')
            ->bindValue('number', $number);

        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);

    }

    public function SelectUserNameById($table,$id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['username','telnumber','region','city'])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function SelectNumberById($id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['telnumber'])
            ->from('users')
            ->where('id = :id')
            ->bindValue('id', $id);

        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function SelectRegion($number)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['region'])
            ->from('regions')
            ->where('number = :number')
            ->bindValue('number', $number);

        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }


    public function SelectLast()
    {
        $stmt = $this->PDO->prepare("SELECT idpost FROM posts ORDER BY idpost DESC LIMIT 1");
        $stmt->execute(array());
       return $name = $stmt->fetchColumn();
    }

    public function SelectLastImage()
    {
        $stmt = $this->PDO->prepare("SELECT id FROM images ORDER BY id DESC LIMIT 1");
        $stmt->execute(array());
        return $name = $stmt->fetchColumn();
    }


    public function insertPost($postid)
    {
        $insert = $this->queryFactory->newInsert();

        $insert
            ->into('images')                   // INTO this table
            ->cols([                        // bind values as "(col) VALUES (:col)"
                'postid'
                   ])
            ->bindValue('postid', $postid);

        $sth = $this->PDO->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());

    }

    public function updateConfirm($id)
    {

        $update = $this->queryFactory->newUpdate();
        $update
            ->table('users')
            ->cols(array(
                'verified',
            ))
            ->where('vkid = :vkid')
            ->bindValues([                  // bind these values
                'verified' => '1',
                'vkid' => $id,
            ]);

        $sth = $this->PDO->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }


    public function updateVkid($email, $vkid)
    {

        $update = $this->queryFactory->newUpdate();
        $update
            ->table('users')
            ->cols(array(
                'vkid',
            ))
            ->where('email = :email')
            ->bindValues([                  // bind these values
                'vkid' => $vkid,
                'email' => $email,
            ]);

        $sth = $this->PDO->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function insertAllPostParams($header,$description,$price,$region,$city,$category,$condition,$id,$time)
    {
        $insert = $this->queryFactory->newInsert();
        $insert
            ->into('posts')                   // INTO this table
            ->cols([                        // bind values as "(col) VALUES (:col)"
                'header',
                'description',
                'price',
                'region',
                'city',
                'section',
                'new',
                'userid',
                'timeadd'

            ])


            ->bindValues
            ([
            'header'=>$header,
            'description'=>$description,
            'price'=>$price,
            'region'=>$region,
            'city'=>$city,
            'section'=>$category,
            'new'=>$condition,
            'userid'=>$id,
            'timeadd'=>$time

        ]);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());
    }



    public function deleteOnePost($idpost)
    {
        $delete = $this->queryFactory->newDelete();

        $delete
            ->from('posts')
            ->where('idpost = :idpost')
            ->bindValue('idpost', $idpost);

        $sth = $this->PDO->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());
    }

    public function deleteImages($idpost)
    {
        $delete = $this->queryFactory->newDelete();

        $delete
            ->from('images')
            ->where('postid = :idpost')
            ->bindValue('idpost', $idpost);

        $sth = $this->PDO->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());
    }

    public function addAdmin($email)
    {

        $update = $this->queryFactory->newUpdate();
        $update
            ->table('users')
            ->cols(array(
                'roles_mask',
            ))
            ->where('email = :email')
            ->bindValues([                  // bind these values
                'roles_mask' => '1',
                'email' => $email,
            ]);

        $sth = $this->PDO->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function getCountUsers()
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from('users');
            $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return count($sth->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getAllUsers($numoffsets)
    {
            $noffset = ($numoffsets - 1)*8;
            //выборка всех при загрузке главной страницы
            $select = $this->queryFactory->newSelect();
            $select->cols(['*'])
                ->from('users')
                ->limit(8)
                ->offset($noffset);
            $this->PDO->exec("SET NAMES utf8");
            $sth = $this->PDO->prepare($select->getStatement());
            $sth->execute($select->getBindValues());
            return $sth->fetchAll(PDO::FETCH_ASSOC);

    }

    //table - таблица где апдейтим
    //col - какую колонку апдейтим
    //value - начение которое необходимо установить
    //wherecol - в какую колонку искать where
    //wherecolvalue - какое значение в колонку поиска

    public function updateUser($table, $col, $value,$wherecol, $wherecolvalue, $colnumber)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols(array(
                $col,
                $colnumber,
            ))
            ->where("$wherecol = :str")
            ->bindValues([                  // bind these values
                $col => $value,
            'str'  => $wherecolvalue,
            ]);

        $sth = $this->PDO->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function adminupdateUser($table, $col, $value,$wherecol, $wherecolvalue)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols(array(
                $col,
            ))
            ->where("$wherecol = :str")
            ->bindValues([                  // bind these values
                $col => $value,
                'str'  => $wherecolvalue,
            ]);

        $sth = $this->PDO->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }


    public function updateNumber($email, $telnumber)
    {

        $update = $this->queryFactory->newUpdate();
        $update
            ->table('users')
            ->cols(array(
                'telnumber',
            ))
            ->where('email = :email')
            ->bindValues([                  // bind these values
                'telnumber' => $telnumber,
                'email' => $email,
            ]);

        $sth = $this->PDO->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function updateEmail($oldemail, $newemail)
    {

        $update = $this->queryFactory->newUpdate();
        $update
            ->table('users')
            ->cols(array(
                'email',
            ))
            ->where('email = :oldemail')
            ->bindValues([                  // bind these values
                'email' => $newemail,
                'oldemail' => $oldemail,
            ]);

        $sth = $this->PDO->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function getCountNotAvPost()
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from('posts')
            ->where('accept = :accept')
            ->bindValue('accept','0');
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return count($sth->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getAllPostsLikeAdmin($numoffsets)
    {

        $noffset = ($numoffsets - 1)*8;

        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from('posts')
            ->limit(8)

            ->where('accept = :accept')
            ->bindValue('accept','0')
            ->offset($noffset);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);

    }


    public function updatePost($id)
    {

        $update = $this->queryFactory->newUpdate();
        $update
            ->table('posts')
            ->cols(array(
                'accept',
            ))
            ->where('idpost = :idpost')
            ->bindValues([                  // bind these values
                'accept' => '1',
                'idpost' => $id,
            ]);

        $sth = $this->PDO->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }


    // ПОЛУЧИТЬ КОЛИЧЕСТВО ПОСТОВ ЮЗЕРА
    public function getCountUserPost($iduser)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from('posts')
            ->where('userid = :userid')
            ->bindValue('userid',$iduser);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return count($sth->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getAllPostsLikeUser($numoffsets, $userid)
    {

        $noffset = ($numoffsets - 1)*8;

        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from('posts')
            ->limit(8)

            ->where('userid = :userid')
            ->bindValue('userid',$userid)
            ->offset($noffset);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);

    }

    public function insertChat($myid, $hisid, $message, $date)
    {
        $insert = $this->queryFactory->newInsert();

        $insert
            ->into('messages')                   // INTO this table
            ->cols([                        // bind values as "(col) VALUES (:col)"
                'myid',
                'hisid',
                'message',
                'time',
                'unread'
            ])
            ->bindValues([                  // bind these values
                'myid' => $myid,
                'hisid' => $hisid,
                'message' => $message,
                'time' => $date,
                'unread'=>'1'
            ]);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($insert->getStatement());

// bind the values and execute
        $sth->execute($insert->getBindValues());
    }

    public function selectMesses($hisid,$myid)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['id','time','message','unread'])
            ->from('messages')
            ->where('myid = :hisid')
            ->where('hisid = :myid')
            ->bindValue('hisid', $hisid)
            ->bindValue('myid', $myid);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function loadSelectMesses($hisid,$myid)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['id','myid','time','message'])
            ->from('messages')
            ->where('myid = :myid')
            ->where('hisid = :hisid')
            ->orWhere('hisid = :myid')
            ->where('myid = :hisid')
            ->bindValue('hisid', $hisid)
            ->bindValue('myid', $myid);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectUsernamePass($id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['username','password'])
            ->from('users')
            ->where('id = :myid')
            ->bindValue('myid', $id);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectIsBanById($id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['status'])
            ->from('users')
            ->where('id = :id')
            ->bindValue('id', $id);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUnread($id)
    {

        $update = $this->queryFactory->newUpdate();
        $update
            ->table('messages')
            ->cols(array(
                'unread',
            ))
            ->where('id = :id')
            ->bindValues([                  // bind these values
                'unread' => '0',
                'id' => $id,
            ]);

        $sth = $this->PDO->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function updateNewPass($email)
    {

        $update = $this->queryFactory->newUpdate();
        $update
            ->table('users')
            ->cols(array(
                'timepass',
            ))
            ->where('email = :email')
            ->bindValues([                  // bind these values
                'timepass' => 'q',
                'email' => $email,
            ]);

        $sth = $this->PDO->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function selectUnreadMesses($myid)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['unread'])
            ->from('messages')
            ->where('hisid = :myid')
            ->where('unread = :unread')
        ->bindValues([
        'myid' => $myid,
        'unread' => '1',
    ]);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function loadUnreadMesses($myid)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['myid'])
            ->from('messages')
            ->where('hisid = :myid')
            ->where('unread = :unread')
            ->bindValues([
                'myid' => $myid,
                'unread' => '1',
            ]);
        $this->PDO->exec("SET NAMES utf8");
        $sth = $this->PDO->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

}




