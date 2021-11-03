<?php
// Überprüfe, ob der Nutzer angemeldet ist.
session_start();

// Falls nicht, Weiterleitung zur Anmeldeseite.
if (!isset($_SESSION['loggedin'])) 
{
    header('Location: index.html');
    exit;
}

// Umleitungsfunktion
function redirect($url)
{
    if (!headers_sent())
    {    
        header('Location: '.$url);
        exit;
    }
    else
    {  
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
        exit;
    }
}

// Verbindungsinformationen zur Datenbank
include 'database_login.php';

// Welcher Button wurde gedrückt?
if (isset($_POST['btnDelete'])) 
{
    // verbinden
    $conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    // Check connection
    if ( mysqli_connect_errno() ) 
    {
        die('Keine Verbindung zu MySQL: ' . $conn->connect_error);
    }

    // Eintrag löschen
    $sql = 'DELETE FROM notes WHERE id = '. $_POST['id'];
    $conn->query($sql);

    // Verbindung schließen
    $conn->close();

    // keine Notiz laden
    unset($_SESSION['currentNoteID']);

    // umleiten
    redirect('../notes.php');
} 
else
{
    // Datei wird zur Bearbeitung geladen
    $_SESSION['currentNoteID'] = $_POST['id'];

    // umleiten
    redirect('../notes.php');
}

?>