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

// Ist die ID bekannt?
if( !isset($_POST['idLoad']) )
{
    // nichts kann geändert werden
    redirect('../notes.php');
}

// Falls kein Titel, immer unbenannt einsetzen.
if( !isset($_POST['noteTitle']) or $_POST['noteTitle'] == '' )
{
	$_POST['noteTitle'] = 'unbenannt';
}

// Verbindungsinformationen zur Datenbank
include 'database_login.php';

// verbinden
$conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

// Check connection
if ( mysqli_connect_errno() ) 
{
	die('Keine Verbindung zu MySQL: ' . $conn->connect_error);
}

// Eintrag aktualisieren
$sql = 'UPDATE notes SET content="'. $_POST['noteText'] .'", title="'. $_POST['noteTitle'] .'" WHERE id = '. $_POST['idLoad'];
$conn->query($sql);

// Verbindung schließen
$conn->close();

// umleiten
redirect('../notes.php');

?>