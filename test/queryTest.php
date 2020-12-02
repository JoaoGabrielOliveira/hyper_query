<?php
require_once dirname(__DIR__) . "/src/query.php";
use PHPUnit\Framework\TestCase;
use Hyper\Database\Query;

class QueryTest extends TestCase
{
    public function test_must_return_a_string()
    {
        $query = new Query;
        $this->assertEquals('', $query);
    }

    public function test_return_a_select_query()
    {
        $query = new Query;
        $query->select('tabela','*');

        $this->assertEquals('SELECT * FROM tabela', $query);

        $query = (new Query)->select('tabela','*');
        $this->assertEquals('SELECT * FROM tabela', $query);
    }

    public function test_return_a_select_query_with_a_limit():void
    {
        $query = new Query;
        $query->select('tabela','*')->limit(3);

        $this->assertEquals('SELECT * FROM tabela LIMIT 3', $query);

        $query = (new Query)->select('tabela','*')->limit(3);
        $this->assertEquals('SELECT * FROM tabela LIMIT 3', $query);
    }
}
?>