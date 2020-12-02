<?php

namespace Hyper\Database;

class Query
{
    private $text_query;

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

    private function validateValueType(&$value)
    {
        if(is_string($value))
        {
            $value = "'$value'";
        }

        return $value;
    }
}

?>