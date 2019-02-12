<?php
namespace App\controllers;

use Aura\SqlQuery\QueryFactory;
use PDO;

class FindController
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

    public function deleteOnePost()
    {
        $delete = $this->queryFactory->newDelete();

        $delete
            ->from('posts')
            ->where('idpost = :idpost')
            ->bindValue('idpost', $_POST['z']);

        $sth = $this->PDO->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());
    }

}

