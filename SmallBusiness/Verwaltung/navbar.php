<div id='sidenav'>
	<!-- Kreuz zum Einklappen -->
	<a href='javascript:void(0)' style='font-size:2.3em;' id='closebtn' onclick='closeNav()'>&times;</a>

	<!-- abmelden -->
	<a href='js_php/logout.php'>abmelden</a>
	<br><br>

	<!-- restliche Links und Auswahl des aktuellen Links -->
	<?php
		$curentPage = basename($_SERVER['PHP_SELF']);
		$selectedStyle = 'style="color: #f1f1f1"';

		// Öffnungszeiten
		$insert = ($curentPage == 'oeffnungszeiten.php') ? $selectedStyle : '';
		echo '<a '. $insert .' href="oeffnungszeiten.php">Öffnungszeiten</a>';

		// Dateien
		$insert = ($curentPage == 'upload.php') ? $selectedStyle : '';
		echo '<a '. $insert .' href="upload.php">Dateien</a>';
    ?>
</div>

<!-- drei Striche und Menu zum Ausklappen -->
<div id='infobar'>
	<span style='font-size:1.4em; cursor:pointer ' onclick='openNav()'>&#9776; Menu</span>
</div>