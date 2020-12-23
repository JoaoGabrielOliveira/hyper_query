# Where

Here, you can see all forms to create a WHERE condition.

The query class use ```where```  function to create a WHERE condition.
````php
where( array|string $condition);
````
The function as param can be a **array** or a **string**.

When you pass **array**, you need use the **key-value model**, where the **key** is the column name and **value** is... The value.
Example:
````php
where(['id' => 1]);
where(['nome' => 'Antonio']);
````
````php
$id = ['id' => 1]
where($id);
````
The form most secure to use where is passing a array, because have a better form to valid the SQL


When you pass **string**, you need pass a SQL query.

````php
where("ID = 1");
where("name = 'Antonio'");
````

Use the **string** form when you need use a another operator, different of equal.

Example:
````php
where("updated_at >= 15/01/2020 ");
where("age < 18");
````

----------------
### IN
To use a IN condition on WHERE, like this guy above:
````sql
SELECT * FROM tabela WHERE id IN (0,1,2,3,4,5)
````

You can pass a ```array```, like the example above:
````php
$query->select('tabela')->where(['id' => [0,1,2,3,4,5]);
````

---------------

To use AND & OR, to create a multiple conditions

### AND
````php
$query->select('tabela')->where(['id' => range(0,5)])->and(['name' => 'Luciano']);
````
Using this form, the result is that.
````sql
SELECT * FROM tabela WHERE id IN (0,1,2,3,4,5) AND "name"='Luciano'
````

### OR
````php
$query->select('tabela')->where(['data' => ])->and(['name' => 'Luciano']);
````
````sql
SELECT * FROM tabela WHERE id IN (0,1,2,3,4,5) AND name='Luciano'
````