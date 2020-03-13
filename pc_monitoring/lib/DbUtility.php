<?php
namespace Utility;

use PDO;
use PhpOffice\PhpSpreadsheet\Chart\Exception;
require_once '../.env.php';

class DbUtility
{
    public $dsn;
    public $opt;
    public $pdo;

    public function __construct()
    {
        //$this->dsn = 'mysql:host=127.0.0.1;dbname=nr';
        $this->dsn = DSNSTRING;
        $this->opt = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8"
        );
        try {
            $this->pdo = new PDO($this->dsn, 'nr_user', 'QazWsx12', $this->opt);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
