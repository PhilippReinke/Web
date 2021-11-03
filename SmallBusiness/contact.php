<!DOCTYPE html>
<html>
<head>
	<title>Titel</title>

	<!-- Metadaten -->
	<meta charset='utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<meta http-equiv='Content-Language' content='de'>
	<meta name='keywords' content='...' />
	<meta name='description' content='...' />
	<meta name='revisit-after' content='14 days'>

	<!-- Favicon -->
	<link rel='apple-touch-icon' sizes='180x180' href='favicon/apple-touch-icon.png'>
	<link rel='icon' type='image/png' sizes='32x32' href='favicon/favicon-32x32.png'>
	<link rel='icon' type='image/png' sizes='16x16' href='favicon/favicon-16x16.png'>
	<link rel='manifest' href='favicon/site.webmanifest'>
	<meta name='msapplication-TileColor' content='#da532c'>
	<meta name='theme-color' content='#ffffff'>
	
	<!-- CSS -->
	<link rel='stylesheet' href='css/style.css'>
	<link rel='stylesheet' href='css/nav.css'>

</head>

<body>
	<!-- Logo und Navbar -->
	<?php include ('nav.php'); ?>

	<!-- Google Maps -->
	<div class='row-white'>
	<div class='content-center'>
		<iframe frameborder='0' style=' border:0; width:100%; height: 25em; ' src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9914406081625!2d2.2922926154413146!3d48.85837360866247!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2964e34e2d%3A0x8ddca9ee380ef7e0!2sEiffelturm!5e0!3m2!1sde!2sde!4v1623058550917!5m2!1sde!2sde' allowfullscreen></iframe>
	</div>
	</div>

	<!-- Adresse und Öffnungszeiten -->
	<div class='row-gray'>
	<div class='content-center'>

		<div class='column2Contact'>
			<h1>Adresse</h1>
			<p>
				Name Unternehmen<br>
				Straße 123<br>
				12345 Ort<br>
				<br>
				<table style='color: #626262; line-height: 1.4em; font-size: 1em;'>
					<tr><td>&#128222;&nbsp;</td><td>12345678</td></tr>
					<tr><td>&#128231;&nbsp;</td><td>your@mail.de</td></tr>
				</table>
			</p>
		</div>

		<div class='column2Contact'>
			<h1>Öffnungszeiten</h1>
			<?php 
				// Öffnungszeiten auslesen
				$array = file('Verwaltung/oeffnungszeiten.txt');

				// Daten extrahieren
				$i = 0; $teile;
				foreach ($array as $line) 
				{
					$teile[$i++] = explode('|', $line);
				}
			?>

			<table class='tabPreise'>
			<tr>
				<th width='30%' bgcolor='#E88578'>Tag</th>
				<th width='70%' bgcolor='#B02116'>Öffnungszeit</th>
			</tr>
			<?php
				// Zeiten eifügen
				foreach($teile as $teil)
				{
					$zusTag = ($teil[1] != '-') ? ' - '.$teil[1] : '';
					$zusZeit = ($teil[3] != '') ? ' - '.$teil[3] : '';
					echo '<tr>';
					echo '	<td>'. $teil[0].$zusTag .'</td>';
					echo '	<td>'. $teil[2].$zusZeit .'</td>';
					echo '</tr>';
				}

				// Anmerkungen auslesen und ggf. anzeigen
				$f = fopen('Verwaltung/anmerkung.txt', 'r'); $line = fgets($f); fclose($f);
				$anmerkung = explode('|', $line);

				if($anmerkung[0] == 1)
				{
					echo '<tr>';
					echo "	<td colspan='2' class='tabInfobox'>". $anmerkung[1] ."</td>";
					echo '</tr>';
				}
			?>	
			</table>
		</div>
	</div>
	</div>

	<!-- Footer -->
	<?php include ('footer.php'); ?>

</body>
</html>