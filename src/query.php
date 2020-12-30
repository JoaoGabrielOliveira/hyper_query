<?php

namespace Hyper\Useful;

use Exception;

class Query
{
    private $text_query;
    private array $values = [];
    private $columns;

    use Query\Helpers;

    public function __toString()
    {
        return (string)$this->text_query;
    }

    public function select(string $table_name, $columns = '*'):self
    {
        if(is_array($columns))
        {
            $columns = implode(',',$columns);
        }
        $this->text_query = "SELECT $columns FROM $table_name";
        return($this);
    }

    public function insert(string $table, array $columns = []):self
    {
        $columns = empty($columns) ? '' : '(' . implode(',',$columns) . ')';
        $this->text_query = "INSERT INTO $table $columns";
        return $this;
    }

    public function addValue(...$values)
    {
        $this->findOrAddToQuery('VALUES');
        array_push($this->values, $values);

        for($i = 0; $i < count($this->values); $i++)
        {
            $this->validateValueType($data);
            echo $data;
        }

        $query_values = [];
        foreach($this->values as $value)
        {
            $query_values[] = '('. implode(',',$value) .')';
        }

        $this->text_query .= implode(',',$query_values);
    }

    public function update(string $table):self
    {
        $this->text_query = "UPDATE $table";
        return $this;
    }

    public function set(string $field,$value):self
    {

        if($this->queryHas('UPDATE') > 0)
        {
            $this->validateValueType($value);
            $this->queryHas('SET') ?
                $this->text_query .= ",$field=$value" :
                $this->text_query .= " SET $field=$value";
        }
        else
            throw new Exception('Use this function after use UPDATE function');

        return $this;
    }

    public function delete(string $table):self
    {
        $this->text_query = "DELETE $table";
        return $this;
    }

    public function create():self
    {
        $this->text_query = 'CREATE ';
        return $this;
    }

    public function drop():self
    {
        $this->text_query = 'DROP ';
        return $this;
    }

    public function table(string $table):self
    {
        $this->text_query .= "TABLE $table";
        $this->columns = [];
        return $this;
    }

    public function addColumn(string $title, string $type, string ...$params):self
    {
        $column = "$title $type " . implode(" ", $params);
        array_push($this->columns, $column);

        $columns = implode(',', $this->columns);
        
        $pattern  = "/(CREATE)\s(TABLE)\s(\w*)/";
        preg_match($pattern,$this->text_query,$match);

        $query = $match[0];

        $this->text_query = "$query ($columns)";
        return $this;
    }

    public function where($condition):self
    {
        $this->findOrAddToQuery('WHERE');
        if (!$this->queryHas("SELECT"))
            throw new Exception('Para realizar o WHERE, é necessario que tenha um SELECT');

        if(is_array($condition))
            $this->conditionWithArray($condition);

        else if(is_string($condition))
            $this->conditionWithString($condition);

        return $this;
    }

    public function and($condition = null):self
    {
        $this->text_query .= ' AND ';
        if(!is_null($condition)) $this->where($condition);
        return $this;
    }

    public function or($condition = null):self
    {
        $this->text_query .= ' OR ';
        if(!is_null($condition)) $this->where($condition);
        return $this;
    }

    public function between($column,$min,$max):self
    {
        $this->findOrAddToQuery('WHERE');
        $this->validateValueType($min);
        $this->validateValueType($max);
        $this->text_query .= " $column BETWEEN $min AND $max";
        return $this;
    }

    public function limit(int $limit):self
    {
        $this->validateValueType($limit);
        $this->text_query .= " LIMIT $limit";
        return $this;
    }

    public function offset(int $offset):self
    {
        $this->validateValueType($offset);
        $this->text_query .= " OFFSET $offset";
        return $this;
    }
}

?>