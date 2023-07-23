<?php
header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control: post-check=0, pre-check=0", false);
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
{
  $type =  $screen[ $cNr ][ 'content' ];
  $isPDF = false;
  $isIMG = false;
  $format = '';
  
  $del = array ('<p>','</p>');  ## HTML TAGS aus string entfernen
  $screen[ $cNr ][ 'content' ] =  str_replace($del, '', $screen[ $cNr ][ 'content' ]);

  $ss = strtolower( substr(  $screen[ $cNr ][ 'content' ], -3 ) );
  
  if( $ss == 'pdf' )  { $format = 'pdf'; }
  if( $ss  =='jpg' )  { $format = 'img'; }
  if( $ss  =='png' )  { $format = 'img'; }
  
  if( $format == 'pdf' )
  { $html[ 'content' ] = '<iframe src="backend/files/'.  $screen[ $cNr ][ 'content' ] .'#toolbar=0" width="100%" height="1060px"></iframe>';
  }
  else if( $format == 'img' )
  { $html[ 'content' ] = '<img src="backend/files/'.  $screen[ $cNr ][ 'content' ] .'" width="100%" height="1080px"></img>';
  }
  else
  { $html[ 'content' ] = $screen[ $cNr ][ 'content' ];
  }
 
  echo $html[ 'contenthead' ];
  echo $html[ 'content'     ];
  echo $html[ 'contentfoot' ];
}

?>