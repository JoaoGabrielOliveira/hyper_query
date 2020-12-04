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

    public function test_return_a_insert_query_with_single_data():void
    {
        $query = new Query;
        $single_data=['nome' => 'Jonathan', 'idade' => 10, "criado_em" => "NOW()"];
        $query->insert('tabela', $single_data);
        $this->assertEquals('INSERT INTO tabela (nome,idade,criado_em) VALUES (\'Jonathan\',10,NOW())', $query);
    }

    public function test_return_a_insert_query_with_multiple_data():void
    {
        $query = new Query;
        $multiple_data=[
            0 => ['nome' => 'Jonathan', 'idade' => 10, "criado_em" => "NOW()"],
            "Segundo" => ['nome' => 'Jong', 'idade' => 20, "criado_em" => "NOW()"]
        ];
        $query->insert('tabela', $multiple_data);
        $this->assertEquals('INSERT INTO tabela (nome,idade,criado_em) VALUES (\'Jonathan\',10,NOW()), (\'Jong\',20,NOW())', $query);
    }
}
?>