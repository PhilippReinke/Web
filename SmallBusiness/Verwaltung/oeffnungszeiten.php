<?php
// Überprüfe, ob der Nutzer angemeldet ist.
session_start();

// Falls nicht, Weiterleitung zur Anmeldeseite.
if (!isset($_SESSION['loggedin'])) 
{
	header('Location: index.html');
	exit;
}
?>


<!DOCTYPE html>
<html>
	<head>
		<title>Öffnungszeiten</title>

		<!-- Metadaten --->
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<meta http-equiv='Content-Language' content='de'>
		
		<!-- Skripte -->
		<link rel='stylesheet' href='css/style.css'>
		<link rel='stylesheet' href='css/navbar.css'>
		<script src='js_php/script.js'></script>

		<style>
			table, input, textarea { width: 100%; }
			select, input, textarea
			{
				box-sizing: border-box;
				font-size: 1em;
				font-family: -apple-system, BlinkMacSystemFont, 'segoe ui', roboto, oxygen, ubuntu, cantarell, 'fira sans', 'droid sans', 'helvetica neue', Arial, sans-serif;
				resize: none;
			}

			@media screen and (max-width: 750px)
			{
				select, input, textarea { font-size: 1.11em; }
			}
		</style>
	</head>

	<body>
		
		<!-- Navigationsleiste -->
		<?php include 'navbar.php'; ?>

		<!-- -->
		<div class='box'>
			<div class='boxTitel'>Zeiten ändern</div>
			<div class='boxInhalt'>
				<?php
					// alte Öffnungszeiten auslesen
					$array = file('oeffnungszeiten.txt');

					// Daten extrahieren
					$i = 0; $teile;
					foreach ($array as $line) 
					{
						$teile[$i++] = explode('|', $line);
					}
				?>
				<form method='post' action='js_php/saveOeffnungszeiten.php'>
					<?php

					// Array für die Wochentage
					$arrayTage = array('-', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag','Samstag','Sonntag');

					for ($i = 0; $i <= 5; $i++) 
					{ 
					?>					
					<table>
						<tr>
							<td colspan='2'><b>Eintrag <?php echo $i+1; ?></b></td>
							<td></td>
						</tr>
							<tr>
							<td>Zeitraum</td>
							<td>
								<select name="beg<?php echo $i; ?>" >
							<?php 
								for($k=0; $k<=7; $k++)
								{
								$zusatz = (($teile[$i][0] == $arrayTage[$k]) ? "selected='selected'" : "");
								echo "<option value='".$arrayTage[$k]."' ".$zusatz.">".$arrayTage[$k]."</option>";
								} 
							?>
							</select>
							<select name="end<?php echo $i; ?>" > 
							<?php 
								for($k=0; $k<=7; $k++)
								{
								$zusatz = (($teile[$i][1] == $arrayTage[$k]) ? "selected='selected'" : "");
								echo "<option value='".$arrayTage[$k]."' ".$zusatz.">".$arrayTage[$k]."</option>";
								} 
							?>
							</select>
							</td>
						</tr>
						<tr>
							<td>1. Uhrzeit</td>
							<td><input type='text' name="uhr1_<?php echo $i; ?>" value="<?php echo $teile[$i][2]; ?>"></td>
						</tr>
						<tr>
							<td>2. Uhrzeit</td>
							<td><input type='text' name="uhr2_<?php echo $i; ?>" value="<?php echo $teile[$i][3]; ?>"></td>
						</tr>
					</table>
					<?php } ?>
					<br>
					<div id='butRight'>
						<button class='butSubmit' type='submit'>Speichern</button>
					</div>
				</form>
			</div>
		</div>

		<!-- -->
		<div class='box'>
			<div class='boxTitel'>Anmerkung</div>
			<div class='boxInhalt'>
				<?php 
					// alte Anmerkungen auslesen
					$f = fopen('anmerkung.txt', 'r'); $line = fgets($f); fclose($f);
					$anmerkung = explode('|', $line);
				?>
				<form action='js_php/saveAnmerkung.php' method='POST'>
					<table>
						<tr>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>verändert</td>
							<td><select name='check'>
								<?php if($anmerkung[0] == 0){ ?>
									<option value='0'>nein</option><option value='1'>ja</option></select>
								<?php }
								else{?>
									<option value='1'>ja</option><option value='0'>nein</option></select>
								<?php }?>
							</td>
						</tr>
						<tr>
							<td>Kommentar</td>
							<td><textarea rows='4' name='komm'><?php echo $anmerkung[1];?></textarea></td>
						</tr>
					</table>
					<br>
					<div id='butRight'>
						<button class='butSubmit' type='submit'>Speichern</button>
					</div>
				</form>
			</div>
		</div>

		<!-- -->
		<div class='box'>
			<div class='boxTitel'>Vorschau</div>
			<div class='boxInhalt'>
				<table>
					<?php
						foreach($teile as $teil)
						{
							$zusTag = ($teil[1] != "-") ? " - ".$teil[1] : "";
							$zusZeit = ($teil[3] != "") ? " - ".$teil[3] : "";
							echo "<tr>
							<td>".$teil[0].$zusTag."</td>
							<td>".$teil[2].$zusZeit."</td>
							</tr>";
						}
						if($anmerkung[0] == 1)
						{
							echo "<tr>
							<td colspan='2'><br />".$anmerkung[1]."</td>
							</tr>";
						}
					?>
				</table>
			</div>
		</div>

	</body>
</html>