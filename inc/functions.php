<?php

function getNewsticker( $db, $raw, $today = '')
{ $newsticker = null;
  $res = $db->query('SELECT * FROM newsticker');
  while ($row = $res -> fetchArray( ) )
  { $newsticker[ $row[ 'id' ] ] = $row;
  }
  
  if($raw)
  {  return $newsticker;
  }

  else
  {  $html['newsticker'] = <<<EOD
<div class="news red">
<span>News</span>
<ul>
EOD;
  
  foreach ($newsticker as $nt)
  { if($nt['active'] AND $today < strtotime( $nt['best_before'])  )
  {  $html['newsticker'] .= '<li><a href="#">+++ ' . $nt['text'] . ' +++ </a></li>';
  }
  }
  
  $html['newsticker'] .= <<<EOD
</ul>
</div>
EOD;
  
  return $html['newsticker'];
  }
}

function getScreen( $db )
{ $screen = null;
  $res = $db -> query( 'SELECT * FROM slidescreen' );
  while ( $row = $res -> fetchArray() )
  {  $screen[ $row[ 'id' ] ]  = $row;
  }
  return $screen;
}

function getScreenslide( $screen, $today, $screenslide )
{
  $html[ 'screenslide' ] =  $screenslide;
  foreach ($screen as $sc)
  { if ( $sc[ 'active' ] AND $today <= strtotime( $sc['best_before']) )
  { if ( $sc[ 'content' ] ) { $sc[ 'header' ] = '<a href="index.php?cNr=' .$sc['id'] . '">' .$sc['header']. '</a>' ; }
    $html[ 'screenslide' ] .= '<div data-img="i/' . $sc['img'] . '" class="any inverse"><div class="mo">' . $sc['header'] . '</div></div>';
  }
  }
  
  $html['screenslide'] .= '</div>';
  return $html['screenslide'] ;
}

function getHtml($db , $html)
{
  $res = $db -> query('SELECT * FROM html' );
  while ( $row = $res -> fetchArray( ) )
  { $html[ $row[ 'name' ] ] = $row[ 'value' ];
  }
  return $html;
}


function deb($val, $kill= false)
{
  echo "<pre>";
  print_r($val);
  echo "</pre>";
  if ($kill) die();
}


?>