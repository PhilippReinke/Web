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
		<title>Dateiverwaltung</title>

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

		<!-- Box mit gespeicherte Dateien -->
		<?php
		// Dateinamen in einem Array speichern
		$aDateien = array();	
		if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].'/files/'.$deleteLine[1]))
		{
		   	while (false !== ($file = readdir($handle)))
		    {
                $ausnahmen = array('dateien.txt','.','..');
		                            
		        if (!in_array($file, $ausnahmen))
		        {
		           	$aDateien[] = $file;
		        }
            }		
		    closedir($handle);
        }
		?>
        <div class='box'>
            <div class='boxTitel'>Dateien</div>
            <div class='boxInhalt'>
            <table>
                <?php
                if(sizeof($aDateien) == 0)
                {
                    echo '<tr>';
                    echo '<td>-</td>';
                    echo '<td></td>';
                    echo '</tr>';
                }
                foreach($aDateien as $teil)
                {
                    echo "<tr>";
                    echo "<form action='/js_php/deleteFile.php' method='POST'>";
                    echo "<td>";
                    echo "<input class='butSubmit' type='submit' value='Löschen'/>";
                    echo "<input type='hidden' name='path' value='../files/".$teil."'/>";
                    echo "</td>";
                    echo "<td>";
                    echo "<a href='../files/".$teil."' target='_blank'>".$teil."</a>";
                    echo "</td>";
                    echo "</form>";
                    echo "</tr>";
                }
                ?>			
            </table> 
            </div>
        </div>

        <!-- Box für das Hochladen von Dateien -->
        <div class='box'>
        	<div class='boxTitel'>Upload</div>
            <div class='boxInhalt'>
            <form enctype='multipart/form-data' action='' method='POST'>
                <input name='file' type='file' id='fileA' onchange='fileChange();'/><br />
                <progress style='visibility:hidden' id='progress' style='margin-top:10px'></progress>
                <span style='display:none' id='prozent'></span>
                <div id='butRight'>
                	<input class='butSubmit' name='abort' value='Abbrechen' type='button' onclick='uploadAbort();'/>
                    <input class='butSubmit' name='upload' value='Hochladen' type='button' onclick='uploadFile();'/>                    
                </div>
        	</form> 
            </div>
        </div> 

	</body>
</html>