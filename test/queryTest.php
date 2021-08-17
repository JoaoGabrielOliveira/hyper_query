<?php
use PHPUnit\Framework\TestCase;
use Hyper\Useful\Query;

class QueryTest extends TestCase
{
    public $single_data;
    public $multiple_data;

    protected function setUp():void
    {
        $this->single_data = ['name' => 'Jonathan', 'age' => 10, "created_by" => "NOW()"];

        $this->multiple_data = [
            0 => ['name' => 'Jonathan', 'age' => 10, "created_by" => "NOW()"],
            "Segundo" => ['name' => 'Jong', 'age' => 20, "created_by" => "NOW()"]
        ];
    }
    public function test_should_return_query_text_when_call_the_object_class()
    {
        $query = new Query;
        $this->assertEquals('', $query);

        $query->select("tableName");
        $this->assertEquals('SELECT * FROM tableName', $query);
    }

    public function test_should_return_a_select_query()
    {
        $query = new Query;
        $query->select('tableName','*');

        $this->assertEquals('SELECT * FROM tableName', $query);

        $query = (new Query)->select('tableName','*');
        $this->assertEquals('SELECT * FROM tableName', $query);
    }

    public function test_should_return_a_select_query_with_a_limit():void
    {
        $query = new Query;
        $query->select('tableName','*')->limit(3);

        $this->assertEquals('SELECT * FROM tableName LIMIT 3', $query);

        $query = (new Query)->select('tableName','*')->limit(3);
        $this->assertEquals('SELECT * FROM tableName LIMIT 3', $query);
    }

    public function test_should_return_select_query_with_conditions():void
    {
        $query = new Query;

        $query->select('tableName','*')->where(['id' => 5]);
        $this->assertEquals('SELECT * FROM tableName WHERE id=5', $query);

        $query->select('tableName','*')
            ->where(['id' => [5,6,7]]);
        $this->assertEquals('SELECT * FROM tableName WHERE id IN(5,6,7)', $query);
    }

    public function test_should_return_a_where_select_query_with_limit():void
    {
        $query = new Query;
        $query->select('tableName','*')->where(['id' => 5])->limit(1);
        $this->assertEquals('SELECT * FROM tableName WHERE id=5 LIMIT 1', $query);
    }

    public function test_should_return_a_insert_query_with_single_data():void
    {
        $query = new Query;
        $query->insert('tableName', $this->single_data);
        $this->assertEquals('INSERT INTO tableName (name,age,created_by) VALUES (\'Jonathan\',10,NOW())', $query);
    }

    public function test_should_must_return_a_update_query():void
    {
        $query = new Query;

        $query->update('tableName')
            ->set('name','Jonathan')
            ->set('age',10)
            ->set('updated','NOW()')->where("ID = 1");

        $this->assertEquals('UPDATE tableName SET name=\'Jonathan\', age=10, updated=NOW()', $query);
    }

    public function test_should_return_a_insert_query_with_multiple_data():void
    {
        $query = new Query;
        $query->insert('tableName', $this->multiple_data);
        $this->assertEquals('INSERT INTO tableName (name,age,created_by) VALUES (\'Jonathan\',10,NOW()), (\'Jong\',20,NOW())', $query);
    }

    public function test_should_return_a_insert_query_with_bind_data():void
    {
        $query = new Query;

        $query->insert('tableName', $this->single_data, true);
        $this->assertEquals('INSERT INTO tableName (name,age,created_by) VALUES (:name0,:age0,:created_by0)', $query);

        $query->insert('tableName', $this->multiple_data, true);
        $this->assertEquals('INSERT INTO tableName (name,age,created_by) VALUES (:name0,:age0,:created_by0),(:name1,:age1,:created_by1)', $query);

        $query->insert('tableName', $this->single_data, true);
        $bind_example = [':name0',':age0',':created_by0'];
        foreach($bind_example as $key)
        {
            $this->assertArrayHasKey($key, $query->getValues());
        }
    }

    public function test_should_return_a_delete_query():void
    {
        $query = new Query;

        $query->delete('tableName');
        $this->assertEquals('DELETE tableName', $query);

        $query->delete('tableName')->where(['id' => 1]);
        $this->assertEquals('DELETE tableName WHERE id=1', $query);
    }

    public function test_should_return_a_create_table_query():void
    {
        $query = new Query;

        $query->create()->table("tableName");
        $this->assertEquals('CREATE TABLE tableName', $query);

        $query->create()->table()->
        addColumn('ID', 'INT', 'PRIMARY KEY')->
        addColumn('name', 'VARCHAR(80)')->
        addColumn('created_by', 'DATE');
        $this->assertEquals('CREATE TABLE tableName (ID INT PRIMARY KEY,name VARCHAR(80), created_by DATE)', $query);
    }

    /*public function test_should_return_a_create_table_query():void
    {
        $query = new Query;

        $query->create()->table();
        $this->assertEquals('CREATE TABLE tableName', $query);

        $query->create()->table()->
        addColumn('ID', 'INT', 'PRIMARY KEY')->
        addColumn('name', 'VARCHAR(80)')->
        addColumn('created_by', 'DATE');
        $this->assertEquals('CREATE TABLE tableName (ID INT PRIMARY KEY,name VARCHAR(80), created_by DATE)', $query);
    }*/


    public function test_should_return_a_query_with_offset():void
    {
        $query = new Query;

        $query->select('tableName')->offset(10);
        $this->assertEquals('SELECT * FROM tableName OFFSET 10', $query);
    }

    public function test_should_return_a_query_with_between():void
    {
        $query = new Query;

        $query->select('tableName')->between('age', 0, 10);
        $this->assertEquals('SELECT * FROM tableName WHERE age BETWEEN 0 AND 10', $query);
    }
}
?>