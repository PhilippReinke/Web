<footer>
	<div class='content-center'>
		<div class='column3'>
			<span style='font-size: 2em; color: white;'>Unternehmen</span>
			<br><br>
			<span style='text-align: left; line-height: 1.4em;'>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut et erat id lacus sodales rutrum. Ut scelerisque libero ut mi molestie consectetur.
			</span>
		</div>
		<div class='column3'>
			<table>
				<tr><td width='30px'>&#x1F4CD;<br><br></td><td style="line-height: 1.4em;">12345 Ort<br>Straße 123</td></tr>
				<tr><td>&#128222;</td><td>12345678</td></tr> <!--&phone;-->
				<tr><td>&#128231;</td><td>your@mail.de</td></tr> <!--&#9993;-->
			</table>
		</div>
		<div class='column3'>
			<?php
			// Öffnungszeiten auslesen
			$array = file('Verwaltung/oeffnungszeiten.txt');

			// Daten extrahieren
			$i = 0; $teile; $strTagAlt;
			foreach ($array as $line) 
			{
				$teile[$i++] = explode('|', $line);
			}					

			// Anmerkungen auslesen
			$f = fopen('Verwaltung/anmerkung.txt', 'r'); $line = fgets($f); fclose($f);
			$anmerkung = explode('|', $line);
			if($anmerkung[0] == 1)
			{
				echo "<font color='#d1350e'>".$anmerkung[1].'</font><br>';
			}

			// Öffnungszeizten ausgeben
			echo "<span style='line-height: 1.4em;'>";
			foreach($teile as $teil)
			{
				$zusEch = ($strTagAlt == '') ? '' : "<br>";
				$zusTag = ($teil[1] != '-') ? ' - '.$teil[1] : '';
				$zusZeit = ($teil[3] != '') ? ' - '.$teil[3] : '';

				if($strTagAlt == ($teil[0].$zusTag))
				{
					echo ' und '.$teil[2].$zusZeit;
				}
				else
				{
					echo $zusEch.$teil[0].$zusTag.'<br>'.$teil[2].$zusZeit;
				}

				//  Daten speichern
				$strTagAlt = $teil[0].$zusTag;
			}
			echo '</span>';
			?>
		</div>
	</div>
	</footer>
	<div id='footer-final'>
		© 2021 Name Unternehmen &nbsp; | &nbsp; <a href='impressum.php'>Impressum / AGB</a> &nbsp; | &nbsp; <a href='datenschutz.php'>Datenschutzerklärung</a>
	</div>