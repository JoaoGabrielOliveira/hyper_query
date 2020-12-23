# Hyper Query

This project is, basically, a SQL query constructor.
With that, you can use PHP to create SQL query with a mor practical 


## Exemples of SELECT
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
[_Click here to see more functions to use on select_](docs/select.md)

A select table query, with a where condition.

````php
$query = new Query;
$query->select('tabela')->where(['id' => 1]);
$query->select('tabela')->where(['nome' => 'Antonio']);
````

[_Click here to see more about **Where conditions**_](docs/where.md)

----------------------

## Exemples of INSERT
A insert values query

````php
$query = new Query;
$query->insert('tabela', ['id','name','age', 'created_at']);
````

````php
$query = new Query;
$query->insert('tabela', ['id','name','age', 'created_at'])
->addValue(1,'Charles',18, 'NOW()')
->addValue(1,'Kevin', 10, time());
````
[_Click here to see more about **insert method**_](docs/insert.md)

----------------------

## Exemples of DELETE

A delete query

````php
$query = new Query;
$query->delete('tabela');
````

````php
$query = new Query;
$query->delete('tabela')->where(['id' => 10]);
````

----------------------

## Exemples of UPDATE

A update query

````php
$query = new Query;
$query->update('tabela')->set('name','Gabriel');

$query->update('tabela')
    ->set('id',1)
    ->set('name','Gabriel')->where(['id' => 1]);
````

