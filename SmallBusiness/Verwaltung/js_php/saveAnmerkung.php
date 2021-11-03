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
$fp = fopen('../anmerkung.txt', 'w');
$str = htmlspecialchars($_POST['check'].'|'.$_POST['komm']);
fwrite($fp, $str);
fclose($fp);

// umleiten
redirect('../oeffnungszeiten.php');
?>