<?php

$db = new SQLite3('../db/hallmonitor.db' );
$post = $_POST;

if( isset( $post['delete'] ) )
{ if( ( $post['delete'][0] == 'SSDELE' ) )  ## Slideshow BG PIC Header
{ $ssid  = $post[ 'delete' ][ 1 ];
  $sstxt = $post[ 'delete' ][ 2 ];
  $SQL0  = 'DELETE FROM slidescreen WHERE id = '.$ssid ;
  $db -> query( $SQL0 );
}
}

if( isset( $post['update'] ) )
{ $SQL  = 'UPDATE slidescreen SET ';
  if( ( $post['update'][0] == 'SSPICH' ) )  ## Slideshow BG PIC Header
  { $ssid  = $post[ 'update' ][ 1 ];
    $imgNr = $post[ 'update' ][ 2 ];
    $SQL1 = $SQL . ' img          = \''.   $imgNr   . '\' WHERE id = \''.$ssid.'\' '; $ret = $db -> query( $SQL1 );
  }
  
  if( ( $post['update'][0] == 'SSCONT' ) )  ## Slideshow Content
  { $ssid  = $post[ 'update' ][ 1 ];
    $imgNr = $post[ 'update' ][ 2 ];
    $SQL2 = $SQL . ' content        = \''.   $imgNr   . '\' WHERE id = \''.$ssid.'\' '; $ret = $db -> query( $SQL2 );
  }
  
  if( ( $post['update'][0] == 'SSHEAD' ) )  ## Slideshow HEADER
  { $ssid  = $post[ 'update' ][ 1 ];
    $imgNr = $post[ 'update' ][ 2 ];
    $SQL3 = $SQL . ' header          = \''.   $imgNr   . '\' WHERE id = \''.$ssid.'\' '; $ret = $db -> query( $SQL3 );
  }
  
  if( ( $post['update'][0] == 'SSACTIV' ) )  ## Slideshow BG PIC Header
  { $ssid  = $post[ 'update' ][ 1 ];
    $imgNr = $post[ 'update' ][ 2 ];
    $SQL4 = $SQL . ' active          = \''.   $imgNr   . '\' WHERE id = \''.$ssid.'\' '; $ret = $db -> query( $SQL4 );
  }
  
  if( ( $post['update'][0] == 'SSDATE' ) )  ## Slideshow BG PIC Header
  { $ssid  = $post[ 'update' ][ 1 ];
    $imgNr = $post[ 'update' ][ 2 ];
    $SQL5 = $SQL . ' best_before     = \''.   $imgNr   . '\' WHERE id = \''.$ssid.'\' '; $ret = $db -> query( $SQL5 );
  }

}


?>