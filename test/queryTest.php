<?php
require_once dirname(__DIR__) . "/src/query.php";
use PHPUnit\Framework\TestCase;
use Hyper\Database\Query;

class QueryTest extends TestCase
{
    public function test_return_query_text_when_call_the_object_class()
    {
        $query = new Query;
        $this->assertEquals('', $query);

        $query->select("tabela");
        $this->assertEquals('SELECT * FROM tabela', $query);
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

    public function test_return_a_where_select_query():void
    {
        $query = new Query;
        $query->select('tabela','*')->where(['id' => 5]);
        $this->assertEquals('SELECT * FROM tabela WHERE id=5', $query);
    }

    public function test_return_a_where_select_query_with_limit():void
    {
        $query = new Query;
        $query->select('tabela','*')->where(['id' => 5])->limit(1);
        $this->assertEquals('SELECT * FROM tabela WHERE id=5 LIMIT 1', $query);
    }
}
?>