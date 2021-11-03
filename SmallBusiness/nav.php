<div>
	<div class='content-center'>
		<a href='index.php'><img src='images/logo.jpg' id='img_logo' alt=''></a>
	</div>
	</div>

	<nav>
	<div class='content-center'>
		<label for='drop' onclick='' class='toggle'>Menu<span style='float: right; font-size: 1em; '>&#x2630;</span></label>
		<input type='checkbox' id='drop' />
		<ul class='menu'>

			<?php
				$curentPage = basename($_SERVER['PHP_SELF']);
				$selectedStyle = 'style="background: #b02116"';
				$selectedStyleDrop = 'style="background: #555"';

				// Startseite
				$insert = ($curentPage == 'index.php') ? $selectedStyle : '';
				echo "<li><a ". $insert ." href='index.php'>Start</a></li>";

				// Gallerie
				echo "<li>";
				echo "	<!-- Gallerie mit Dropdown -->";
				$galerieArray = array('galerie_atelier.php', 'galerie_hochzeit.php');
				$insert = (in_array($curentPage, $galerieArray)) ? $selectedStyle : '';
				echo "	<label ". $insert ." for='drop-1' onclick='' class='toggle'>Gallerie +</label>";
				echo "	<span ". $insert ." class='expandableEntry'>Gallerie +</span>";
				echo "	<!-- <a href=''>Gallerie</a> -->";
				echo "	<input type='checkbox' id='drop-1'/>";
				echo "	<ul>";
							$insert = ($curentPage == 'galerie_atelier.php') ? $selectedStyleDrop : '';
				echo "		<li><a ". $insert ." href='galerie_atelier.php'>Atelierfotos</a></li>";
							$insert = ($curentPage == 'galerie_hochzeit.php') ? $selectedStyleDrop : '';
				echo "		<li><a ". $insert ." href='#'>Hochzeitsfotos</a></li>";
				echo "	</ul> ";
				echo "</li>";

				// Angebot
				echo "<li>";
				echo "	<!-- Angebot mit Dropdown -->";
				$galerieArray = array('info_atel.php', 'info_baby.php', 'info_bewe.php', 'info_busi.php', 'info_serv.php', 'info_gestaltungsservice.php', 'info_grabbilder.php', 'info_hoch.php', 'info_lade.php', 'info_pass.php', 'info_proBildbearbeitung.php', 'info_rest.php');
				$insert = (in_array($curentPage, $galerieArray)) ? $selectedStyle : '';
				echo "	<label ". $insert ." for='drop-2' onclick='' class='toggle'>Angebot +</label>";
				echo "	<span ". $insert ." class='expandableEntry'>Angebot +</span>";
				echo "	<input type='checkbox' id='drop-2'/>";
				echo "	<ul>";
							$insert = ($curentPage == 'info_atel.php') ? $selectedStyleDrop : '';
				echo "		<li><a ". $insert ." href='info_atel.php'>Atelier</a></li>";
							$insert = ($curentPage == 'info_baby.php') ? $selectedStyleDrop : '';
				echo "		<li><a ". $insert ." href='#'>Babybauch und Erotik</a></li>";
							$insert = ($curentPage == 'info_bewe.php') ? $selectedStyleDrop : '';
				echo "		<li><a ". $insert ." href='#'>Bewerbung</a></li>";
							$insert = ($curentPage == 'info_busi.php') ? $selectedStyleDrop : '';
				echo "		<li><a ". $insert ." href='#'>Businessfotos</a></li>";
							$insert = ($curentPage == 'info_serv.php') ? $selectedStyleDrop : '';
				echo "		<li><a ". $insert ." href='info_serv.php'>Fotoservice / -bücher</a></li>";
				echo "	</ul>";
				echo "</li>";

				// Kontakt
				$insert = ($curentPage == 'contact.php') ? $selectedStyle : '';
				echo "<li><a ". $insert ." href='contact.php'>Kontakt</a></li>";

				// FAQ
				$insert = ($curentPage == 'faq.php') ? $selectedStyle : '';
				echo "<li><a ". $insert ." href='faq.php'>FAQ</a></li>";
			?>
		</ul>
	</div>
	</nav>