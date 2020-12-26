<?php
namespace Hyper\Useful\Query;
trait Helpers
{
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

    private function findOrAddToQuery(string $query)
    {
        if(!$this->queryHas($query))
            $this->text_query .= " $query ";
        return null;
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

    public function getValues():array
    {
        return $this->bind_values;
    }

    private function conditionWithString(string $condition):void
    {
        $this->text_query .= $condition;
    }

    private function conditionWithArray(array $condition)
    {
        $condition_keys = array_keys($condition);

        if (count($condition_keys) > 1)
            echo "WARNING | AVISO: Para cada parâmetro, uma condição apenas";

        $column = $condition_keys[0];
        $values = $condition[$column];

        if(is_array($values))
        {
            $params = array();
            foreach($values as $value)
            {
                $this->validateValueType($value);
                array_push($params,$value);
            }

            $values = implode(',',$params);
            $query = "$column IN($values)";
        }

        else
        {
            $this->validateValueType($values);
            $query = "$column=$values";
        }

        $this->text_query .= $query;
    }
}
?>