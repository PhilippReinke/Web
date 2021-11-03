<?php
session_start();

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

// Wurden der Benutzername und das Passwort übermittelt?
if ( !isset($_POST['username'], $_POST['password']) ) 
{
	exit('Bitte den Benutzer und das Passwort eingeben.');
}

// Passwort zu Benutzer abfragen und verhindere SQL-Injektion
if ($_POST['username'] == 'user' and $_POST['password'] == '1234') 
{
	// Verifikation erfolgreich. Benutzer ist eingeloggt.
	// Es wird eine Session begonnen. So wissen wir später, dass der Benutzer eingeloggt ist.
	session_regenerate_id();
	$_SESSION['loggedin'] = TRUE;
	$_SESSION['name'] = $_POST['username'];
	$_SESSION['id'] = $id;
	//echo 'Willkommen ' . $_SESSION['name'] . '!';

	// weiterleiten
	redirect('../oeffnungszeiten.php');
} 	
else 
{
	// Benutzername oder Passwort falsch
	redirect('../index.html');
}

?>