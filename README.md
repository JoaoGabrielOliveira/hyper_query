# Hyper Query

This project is, basically, a SQL query constructor.

With that, you can use PHP to create SQL query.


## Exemples
A select table query

````php
$query = new Query;
$query->select('tabela');
````

A select table query with a specific fields

````php
$query = new Query;
$query->select('tabela','name, age, another');
````

A select table query, but show just a one result

````php
$query = new Query;
$query->select('tabela')->limit(1);
````