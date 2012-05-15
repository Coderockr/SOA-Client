<?php
namespace test;

abstract class AppTest extends \PHPUnit_Framework_TestCase
{

    protected $em;


    public function __construct() {
        include 'configs/configs.testing.php';
        include './bootstrap.php';
        $this->em = $em;
        parent::__construct();
    }

    public function truncate($table){
        $connection = $this->em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $q = $dbPlatform->getTruncateTableSql($table);
        $connection->executeUpdate($q);
    }
}