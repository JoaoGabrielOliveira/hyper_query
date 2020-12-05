<?php
require_once dirname(__DIR__) . "/src/query.php";
use PHPUnit\Framework\TestCase;
use Hyper\Database\Query;

class QueryTest extends TestCase
{
    public $single_data;
    public $multiple_data;

    protected function setUp():void
    {
        $this->single_data = ['nome' => 'Jonathan', 'idade' => 10, "criado_em" => "NOW()"];

        $this->multiple_data = [
            0 => ['nome' => 'Jonathan', 'idade' => 10, "criado_em" => "NOW()"],
            "Segundo" => ['nome' => 'Jong', 'idade' => 20, "criado_em" => "NOW()"]
        ];
    }
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
        $query->insert('tabela', $this->single_data);
        $this->assertEquals('INSERT INTO tabela (nome,idade,criado_em) VALUES (\'Jonathan\',10,NOW())', $query);
    }

    public function test_return_a_insert_query_with_multiple_data():void
    {
        $query = new Query;
        $query->insert('tabela', $this->multiple_data);
        $this->assertEquals('INSERT INTO tabela (nome,idade,criado_em) VALUES (\'Jonathan\',10,NOW()), (\'Jong\',20,NOW())', $query);
    }

    public function test_return_a_insert_query_with_bind_data():void
    {
        $query = new Query;

        $query->insert('tabela', $this->single_data, true);
        $this->assertEquals('INSERT INTO tabela (nome,idade,criado_em) VALUES (:nome0,:idade0,:criado_em0)', $query);

        $query->insert('tabela', $this->multiple_data, true);
        $this->assertEquals('INSERT INTO tabela (nome,idade,criado_em) VALUES (:nome0,:idade0,:criado_em0),(:nome1,:idade1,:criado_em1)', $query);

        $query->insert('tabela', $this->single_data, true);
        $bind_example = [':nome0',':idade0',':criado_em0'];
        foreach($bind_example as $key)
        {
            $this->assertArrayHasKey($key, $query->getValues());
        }
    }

    public function test_return_a_delete_query():void
    {
        $query = new Query;

        $query->delete('tabela');
        $this->assertEquals('DELETE tabela', $query);

        $query->delete('tabela')->where(['id' => 1]);
        $this->assertEquals('DELETE tabela WHERE id=1', $query);
    }
}
?>