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

// Verbindungsinformationen zur Datenbank
include 'database_login.php';

// Funktinoiert die Verbindung zur Datenbank?
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) 
{
	exit('Keine Verbindung zu MySQL: ' . mysqli_connect_error());
}

// Wurden der Benutzername und das Passwort übermittelt?
if ( !isset($_POST['username'], $_POST['password']) ) 
{
	exit('Bitte den Benutzer und das Passwort eingeben.');
}

// Passwort zu Benutzer abfragen und verhindere SQL-Injektion
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) 
{
	// Vermeide SQL-Injektion. Bind parameters (s = string, i = int, b = blob, etc).
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();

	// Abfrage speichern
	$stmt->store_result();

	// Überprüfe Benutzername und Passwort
	if ($stmt->num_rows > 0)
	{
		$stmt->bind_result($id, $password);
		$stmt->fetch();

		// Account exisitiert, überprüfe nun das Passwort.
		// WICHTIG: Benutze password_hash, um das Passwort in der Datenbank zu speichern.
		if (password_verify($_POST['password'], $password))
		{
			// Verifikation erfolgreich. Benutzer ist eingeloggt.
			// Es wird eine Session begonnen. So wissen wir später, dass der Benutzer eingeloggt ist.
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['name'] = $_POST['username'];
			$_SESSION['id'] = $id;
			//echo 'Willkommen ' . $_SESSION['name'] . '!';

			// weiterleiten
			redirect('../upload.php');
		} 	
		else 
		{
			// Passwort falsch
			// echo 'Benutzername oder Passwort falsch!';
			redirect('../index.html');
		}
	} 
	else 
	{
		// Benutzername nicht vorhanden
		// echo 'Benutzername oder Passwort falsch!';
		redirect('../index.html');
	}

	$stmt->close();
}


?>