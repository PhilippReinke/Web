<?php
// Überprüfe, ob der Nutzer angemeldet ist.
session_start();

// Falls nicht, Weiterleitung zur Anmeldeseite.
if (!isset($_SESSION['loggedin'])) 
{
    header('Location: index.html');
    exit;
}

// Umleitung
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
        echo '</noscript>'; exit;
    }
}

// Datei löschen
unlink($_POST['path']);

// umleiten
redirect('../upload.php');
?>