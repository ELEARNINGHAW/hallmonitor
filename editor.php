<?php
#print_r($_POST);
?>
<?php

include( 'inc/functions.php' );

$db = new SQLite3('db/hallmonitor.db' );

if ( isset ( $_POST ) ) { actionHandler( $db ); }

#if (isset($_GET['cNr'])) $cNr = $_GET['cNr']; else { $cNr = 0; }

$today      = strtotime( date("Y-m-d" ) );

$htmlData   = getHtmlData( $db );

echo( $htmlData[ 'editorhead' ] );

echo getNewstickerEditor( $db );

echo  getScreenSlideEditor( $db );

?>
