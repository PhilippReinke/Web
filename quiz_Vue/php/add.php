<?php
// Verbindungsinformationen zur Datenbank
include 'database_login.php';

/*
Link zum Einfügen:
https://philippreinke.de/quiz/php/add.php?name=Hallo&points=12&max_points=30&date=2020
*/

// neue Daten zu highscore hinzufügen
header('Content-Type: application/json');
$pdo = new PDO('mysql:host='.$DATABASE_HOST.';dbname='.$DATABASE_NAME, $DATABASE_USER, $DATABASE_PASS);
$name = trim($_GET['name'] ?? '');
$points = trim($_GET['points'] ?? '');
$max_points = trim($_GET['max_points'] ?? '');
$date = trim($_GET['date'] ?? '');
 
if(strlen($name) == 0 && strlen($points) == 0 && strlen($max_points) == 0 && strlen($date) == 0) 
{
    echo json_encode(["ok" => false, "erstesIf" => true, "name"=> $name]);
}
else
{
    $statement = $pdo->prepare("INSERT INTO `highscore` (`id`, `name`, `points`, `max_points`, `date`) VALUES (NULL, '".$name."', '".$points."', '".$max_points."', '".$date."');");
    $ok = $statement->execute();
    echo json_encode(["ok" => $ok]);
}
?>