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

A select table query, with a where condition.

````php
$query = new Query;
$query->select('tabela')->where(['id' => 1]);
````

````php
$query->select('tabela')->where(['nome' => 'Antonio']);
````

````php
$query->select('tabela')->where(['id' => range(0,5)]);
````
````sql
SELECT * FROM tabela WHERE id IN (0,1,2,3,4,5)
````

````php
$query->select('tabela')->where(['id' => range(0,5)])->and(['name' => 'Luciano']);
````
````sql
SELECT * FROM tabela WHERE id IN (0,1,2,3,4,5) AND "name"='Luciano'
````

````php
$query->select('tabela')->where(['data' => ])->and(['name' => 'Luciano']);
````
````sql
SELECT * FROM tabela WHERE id IN (0,1,2,3,4,5) AND name='Luciano'
````