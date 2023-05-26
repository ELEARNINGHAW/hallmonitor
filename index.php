<?php
include ( 'inc/functions.php' ) ;
include ( 'data.php' ) ;
$cNr = 0;
if( isset( $_GET[ 'cNr' ] ) ) $cNr = $_GET[ 'cNr' ];

$db     =  new SQLite3('db/hallmonitor.db' );
$today  =  strtotime ( date("Y-m-d" ) );
$screen =  getScreen( $db );

$html =  getHtml( $db, $html );
$html[ 'newsticker'  ] = getNewsticker( $db, false, $today );
$html[ 'screenslide' ] = getScreenslide( $screen, $today, $html[ 'screenslide' ] );

if( !$cNr )
{ echo $html[ 'navihead'    ];
  if ( $html[ 'newsticker'  ][ 'payload' ] ) { echo $html[ 'newsticker'  ][ 'html' ]; }
  echo $html[ 'screenslide' ];
  echo $html[ 'menu'        ];
  echo $html[ 'navifoot'    ];
}

else
{ $type = explode('.',  $screen[ $cNr ][ 'content' ] );
  if( isset( $type[ 1 ] ) AND  strcmp ($type[ 1 ], 'pdf' ) == 0 )
  { $html[ 'content' ] = '<iframe src="'.  $screen[ $cNr ][ 'content' ] .'#toolbar=0" width="100%" height="800px"></iframe>';
  }
  else
  { $html[ 'content' ] = $screen[ $cNr ][ 'content' ];
  }
 
  echo $html[ 'contenthead' ];
  echo $html[ 'content'     ];
  echo $html[ 'contentfoot' ];
}

?>