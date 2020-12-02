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
        $this->text_query .= " LIMIT $limit";
        return($this);
    }
}

?>