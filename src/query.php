<?php

namespace Hyper\Database;

class Query
{
    private $text_query;
    private $bind_values;

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

    public function insert(string $table, array $values):self
    {
        $columns = array_keys($values);
        $columns_names = implode(',',$columns);

        $values = implode(',',$this->bindValues($values));

        $this->text_query = "INSERT INTO $table ($columns_names) VALUES ($values)";

        return($this);
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

    private function bindValues(array &$data):array
    {
        #Inicial $data = ['nome' => 'Jonathan', 'idade' => 10, "criado_em" => "NOW()"];

        $index = 0;
        foreach($data as $key => $value)
        {
            $key = ":$key" . "$index";
            $bind_values[$key] = $this->validateValueType($value);
        }

        #Esperado = [:nome1 => 'Jonathan', :idade1 => 10]
        return $bind_values;
    }
}

?>