<?php
// Überprüfe, ob der Nutzer angemeldet ist.
session_start();

// Falls nicht, Weiterleitung zur Anmeldeseite.
if (!isset($_SESSION['loggedin'])) 
{
	header('Location: index.html');
	exit;
}

// Verbindungsinformationen zur Datenbank
include 'js_php/database_login.php';

// Funktioniert die Verbindung zur Datenbank?
$db_link = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) 
{
	exit('Keine Verbindung zu MySQL: ' . mysqli_connect_error());
}

?>


<!DOCTYPE html>
<html>
	<head>
		<title>Notizen</title>

		<!-- Metadaten --->
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<meta http-equiv='Content-Language' content='de'>
		
		<!-- Skripte -->
		<link rel='stylesheet' href='css/style.css'>
		<link rel='stylesheet' href='css/navbar.css'>
		<script src='js_php/script.js'></script> 
	</head>

	<body>
		
		<!-- Navigationsleiste -->
		<?php include 'navbar.php'; ?>

		<!-- Notiz laden und löschen -->
        <div class='box'>
            <div class='boxTitel'>Notiz laden oder löschen</div>
            <div class='boxInhalt'>
            	<?php
				// Titel der vorhandenen Notizen abfragen
				$sql = 'SELECT id, title FROM notes';
				$db_erg = mysqli_query( $db_link, $sql );

				// Abfrage erfolgreich?
				if ( ! $db_erg )
				{
				  die('Ungültige Abfrage: ' . mysqli_error());
				}

				// Titel ausgeben
				echo '<form action="/js_php/DeleteOrLoadNote.php" method="POST">';
				echo '<select id="selectNote" name="id" size="5">';
				while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC))
				{
					// Titel der Notiz
                    echo '<option value="'.$zeile['id'].'">'. $zeile['title'] . '</option>';
				}
				echo '</select>';

				// Button zum Löschen und Laden einer Notiz
                echo '<div id="butRight">';
                echo '<input class="butSubmit" type="submit" name="btnLoad" value="Laden">
                ';
                echo '<input class="butSubmit" type="submit" name="btnDelete" value="Löschen">';
                echo '</div>';
                echo '</form>';

				// Speicher freigeben (nicht notwendig, aber sinnvoll)
				mysqli_free_result( $db_erg );
				?>
            </div>
        </div>

        <!-- neue Notiz anlegen -->
        <div class='box'>
            <div class='boxTitel'>neue Notiz anlegen</div>
            <div class='boxInhalt'>
            	<form action='/js_php/newNote.php' method='POST'>
	            	<textarea id='notesTitle' name='noteTitle'></textarea>
	                <div id='butRight'><input class='butSubmit' type='submit' value='anlegen'></div>
	            </form>
            </div>
        </div>

        <!-- Bearbeitung und speichern -->
        <div class='boxBig'>
            <div class='boxTitel'>Notizen</div>
            <div class='boxInhalt'>
            	<?php
				// lade die Notiz zur ausgewählten id
            	if(!isset($_SESSION['currentNoteID']))
            	{
            		$title = '';
            		$noteText = '';

            		// Bedienelemente werde deaktiviert
            		$disabled = 'disabled';
            	}
            	else
            	{
            		$sql = 'SELECT title, content FROM notes WHERE id=' . $_SESSION['currentNoteID'];
					$db_erg = mysqli_query( $db_link, $sql );

					$array = mysqli_fetch_array( $db_erg, MYSQLI_ASSOC);

					$title = $array['title'];
					$noteText = $array['content'];

					// Bedienelemente werde nicht deaktiviert
					$disabled = '';
            	}            	

				// Verbindung zur Datenbank schließen (nicht notwendig, aber sinnvoll)
				$db_link->close();

            	?>
	            <form action='/js_php/saveNote.php' method='POST'>
	            	<textarea id='notesTitle' name='noteTitle' <?php echo $disabled; ?>><?php echo $title; ?></textarea>
	                <textarea id='notes' name='noteText' <?php echo $disabled; ?> ><?php echo $noteText; ?></textarea>
	                <input type='hidden' name='idLoad' value=<?php echo $_SESSION['currentNoteID']; ?> />
	                <div id='butRight'>
	                	<input class='butSubmit' type='submit' value='speichern' <?php echo $disabled; ?>>
	                </div>
	            </form>
            </div>
        </div>

	</body>
</html>