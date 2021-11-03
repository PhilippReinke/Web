<?php
// Umleitung
function redirect($url)
{
    if (!headers_sent())
    {    
        header('Location: '. $url);
        exit;
    }
    else
    {  
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}

// Daten in einer Datei speichern
$fp = fopen('../oeffnungszeiten.txt', 'w');
for ($i = 0; $i <= 5; $i++) 
{
	// String zusammenfügen
	if(htmlspecialchars($_POST['beg'.$i]) != '-')
	{
		$str = htmlspecialchars($_POST['beg'.$i]).'|';
	}
	else
	{
		continue;
	}
	
	$str .= htmlspecialchars($_POST['end'.$i]).'|'.htmlspecialchars($_POST['uhr1_'.$i]);
	
	if(htmlspecialchars($_POST['uhr2_'.$i]) != '')
	{
		$str .= '|'.htmlspecialchars($_POST['uhr2_'.$i]);
	}
	
	// String in der Datei speichern	 
	$zusatz = ($i != 0) ? "\n" : '';
	fwrite($fp, $zusatz.$str);	
}

fclose($fp);

// umleiten
redirect('../oeffnungszeiten.php');
?>