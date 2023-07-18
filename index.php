<?php
include ( 'inc/functions.php' ) ;
include ( 'inc/data.php' ) ;
$cNr = 0;
if( isset( $_GET[ 'cNr' ] ) ) $cNr = $_GET[ 'cNr' ];

$db     =  new SQLite3('../db/hallmonitor.db' );
$today  =  strtotime ( date("Y-m-d" ) );
$screen =  getScreenData( $db );
$html = '';

$html =  getHtmlData( $db, $html );
$html[ 'newsticker'  ] = getNewstickerData( $db, false, $today );
$html[ 'screenslide' ] = getScreenslideData( $html, $screen, $today, $html[ 'screenslide' ] );

if( !$cNr )
{ echo $html[ 'navihead'    ];
  if ( $html[ 'newsticker'  ][ 'payload' ] ) { echo $html[ 'newsticker'  ][ 'html' ]; }
  echo $html[ 'screenslide' ];
  echo $html[ 'menu'        ];
  echo $html[ 'navifoot'    ];
}

else
{ $type = explode('.',  $screen[ $cNr ][ 'content' ] );
  
  $isPDF = false;
  foreach($type as $t)
  {  if(strcmp( substr($t, 0,2) ,'pdf') ) { $isPDF = true;}
  }
  
  if( $isPDF )
  {
    $del = array ('<p>','</p>');  ## HTML TAGS aus string entfernen
    $screen[ $cNr ][ 'content' ] =  str_replace($del, '', $screen[ $cNr ][ 'content' ]);

    $html[ 'content' ] = '<iframe src="backend/files/'.  $screen[ $cNr ][ 'content' ] .'#toolbar=0" width="100%" height="1060px"></iframe>';
  }
  else
  { $html[ 'content' ] = $screen[ $cNr ][ 'content' ];
  }
 
  echo $html[ 'contenthead' ];
  echo $html[ 'content'     ];
  echo $html[ 'contentfoot' ];
}

?>