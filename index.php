<?php
ini_set('display_errors', 1);
echo "<h1>PDO demo!</h1>";
$username = 'tjn9';
$password = 'britain93';
$hostname = 'sql2.njit.edu';
$dsn = "mysql:host=$hostname;dbname=$username";

echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>Email</th></tr>";

class TableRows extends RecursiveIteratorIterator 
{
    function __construct($it) 
    {
        parent::__construct($it, self::LEAVES_ONLY);
    }

    function current() 
    {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() 
    {
        echo "<tr>";
    }

    function endChildren() 
    {
        echo "</tr>" . "\n";
    }
}

try 
{
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully<br>";
    
    $query = 'SELECT id, email FROM accounts
		WHERE id < :id_Limit';
	$statement = $conn->prepare($query);
	$statement ->bindValue(':id_Limit', 6);
	$statement->execute();
	$accounts = $statement->fetchAll();
	$statement->closeCursor();

	$count = $statement->rowCount();
	echo "Results: " . $count. "<br>";

	$result = $statement->setFetchMode(PDO::FETCH_ASSOC);
	foreach(new TableRows(new RecursiveArrayIterator($statement->fetchAll())) as $k=>$v)
	{
		echo $v;
	}
} 
catch(PDOException $e) 
{
    echo "Connection failed: " . $e->getMessage() ."<br>";
}

$conn = null;
echo "</table>";
?>