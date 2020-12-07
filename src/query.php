<?php

namespace Hyper\Database;

use Exception;

class Query
{
    private $text_query;
    private $bind_values;
    private $columns;

    public function __toString()
    {
        return (string)$this->text_query;
    }

    public function select(string $table_name, $collumns = '*'):self
    {
        if(is_array($collumns))
        {
            $collumns = implode(',',$collumns);
        }
        $this->text_query = "SELECT $collumns FROM $table_name";
        return($this);
    }

    public function limit(int $limit):self
    {
        $this->validateValueType($limit);
        $this->text_query .= " LIMIT $limit";
        return($this);
    }

    public function where($condition):self
    {
        $where = array();
        if(is_array($condition))
        {
            foreach($condition as $key => $value)
            {
                $this->validateValueType($value);
                array_push($where,$key .'='.$value);
            }

            $where = ' WHERE ' . implode(' AND ',$where);
        }

        else if(is_string($condition) && $condition != '')
        {
            strpos($condition, 'WHERE') === true ?
            $where = 'WHERE ' . $condition : $where = $condition;
        }

        else
        {
            throw new InvalidArgumentException('Condition is not a string or a array.');
        }

        $this->text_query .= $where;

        return($this);
    }

    public function insert(string $table, array $values, bool $bind_value = false):self
    {
        if($this->isMultipleData($values))
        {
            $values = $this->createInsertQueryMultipleData($values);
            if ($bind_value)
            {
                $values[1] = "";
                $values[1] .= '(' . implode(",", array_keys($values[2][0])) . ')';
                $size = count($values[2]);
                for($i = 1; $i < $size; $i++)
                {
                    $values[1] .= ',(' . implode(",", array_keys($values[2][$i])) . ')';
                }
            }
        }

        else
        {
            $values = $this->createInsertQuerySingleData($values);
            if ($bind_value)
                $values[1] = '(' . implode(",", array_keys($values[2])) . ')';
        }

        $this->text_query = "INSERT INTO $table (" . $values[0] . ") VALUES " . $values[1];
        $this->bind_values=$values[2];
        return($this);
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
            throw new Exception('Use this function afteruse UPDATE function');

        return $this;
    }

    public function delete(string $table):self
    {
        $this->text_query = "DELETE $table";
        return $this;
    }

    public function create():self
    {
        $this->text_query = 'CREATE';
        return $this;
    }

    public function table(string $table):self
    {
        $this->text_query .= " TABLE $table ";
        $this->columns = [];
        return $this;
    }

    public function addColumn(string $title, string $type, string ...$params):self
    {
        $column = "$title $type " . implode(" ", $params);
        array_push($this->columns, $column);

        $pattern  = "/()/i";
        $query = preg_replace($pattern, implode(' ', $this->columns) , $this->text_query);

        $this->text_query = $query;
        return $this;
    }

    public function getValues():array
    {
        return $this->bind_values;
    }

    private function validateValueType(&$value)
    {
        if(is_string($value))
        {
            $function_pattern = "#\(((?:\[^\(\)\]++|(?R))*)\)#";

            preg_match($function_pattern, $value, $match);

            empty($match) ? $value = "'$value'" :  $match;
        }

        return $value;
    }

    private function bindValues(array $data, int $index = 0):array
    {
        foreach($data as $key => $value)
        {
            $key = ":$key" . "$index";
            $bind_values[$key] = $this->validateValueType($value);
        }

        return $bind_values;
    }

    private function queryHas(string $query):bool
    {
        $pattern = "/$query/i";
        return preg_match($pattern, $this->text_query) > 0 ? true : false;
    }

    private function isMultipleData(array $data)
    {
        foreach($data as $value)
        {
            if(is_array($value))
            {
                return true;
            }
        }

        return false;
    }

    private function createInsertQueryMultipleData(array $data):array
    {
        $firstKey = array_key_first($data);
        $columns = array_keys($data[$firstKey]);
        $columns_names = implode(',',$columns);

        $values_text = "";
        $index = 0;
        $size = count($data) - 1;
        $bind_values = array();
        foreach($data as $value)
        {
            $bind_value = $this->bindValues($value,$index);
            if($index == $size)
                $values_text .= "(" . implode(',',$bind_value) . ")";
            else
                $values_text .= "(" . implode(',',$bind_value) . "), ";
            array_push($bind_values,$bind_value);
            $index++;
        }

        return [$columns_names, $values_text,$bind_values];
    }

    private function createInsertQuerySingleData(array $data):array
    {
        $columns = array_keys($data);
        $columns_names = implode(',',$columns);
        $bind_value = $this->bindValues($data);
        $values_text = "(" . implode(',',$bind_value) . ")";

        return [$columns_names, $values_text,$bind_value];
    }
}

?>