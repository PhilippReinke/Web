<?php
// Verbindungsinformationen zur Datenbank
include 'database_login.php';

// alles aus highscore auslesen
header('Content-Type: application/json');
$pdo = new PDO('mysql:host='.$DATABASE_HOST.';dbname='.$DATABASE_NAME, $DATABASE_USER, $DATABASE_PASS);
$statement = $pdo->prepare("SELECT * FROM highscore ORDER BY points DESC");
$statement->execute();
$data = $statement->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);
?>