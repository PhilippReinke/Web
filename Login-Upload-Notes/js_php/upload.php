<?php
// Überprüfe, ob der Nutzer angemeldet ist.
session_start();

// Falls nicht, Weiterleitung zur Anmeldeseite.
if (!isset($_SESSION['loggedin'])) 
{
    header('Location: index.html');
    exit;
}

// Datei speichern
$uploaddir = '../files/';

if (isset($_FILES['datei']))
{
     move_uploaded_file($_FILES['datei']['tmp_name'], $uploaddir.basename($_FILES['datei']['name']));
}

?>