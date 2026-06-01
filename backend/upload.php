<?php
header('Content-type:application/json;charset=utf-8');

try { if ( !isset($_FILES['file']['error']) ||   is_array($_FILES['file']['error']) )
	{ throw new RuntimeException('Invalid parameters.');
  }

  switch ( $_FILES[ 'file' ][ 'error' ] )
	{   case UPLOAD_ERR_OK:             break;
      case UPLOAD_ERR_NO_FILE:        throw new RuntimeException('No file sent.');
      case UPLOAD_ERR_INI_SIZE:
   		case UPLOAD_ERR_FORM_SIZE:  throw new RuntimeException('Exceeded filesize limit.');
      default:                    throw new RuntimeException('Unknown errors.');
  }
 
	$post = $_POST;
	$get =  $_GET;
    
	$ssid  = $get['ssid'];
 
  $file[ 'name' ] = uniqid().'_'.$_FILES[ 'file' ][ 'name' ];
  $file[ 'nameHTML' ] = pathinfo( $file[ 'name' ] , PATHINFO_FILENAME).".html";

  $htmlFile = '<html><head><body><style>html, body{ height: 100%; margin: 0; } body { display: flex;align-items: center; justify-content: center;}img { max-width: 100%; max-height: 100%; object-fit: contain; }</style><img src="'.$file[ 'name' ].'" alt=""></body></head></html></html>';

  $file[ 'path' ] = 'files/'.$file[ 'name' ]  ;

#  deb($file[ '$htmlFile ' ]);
 # deb($file[ 'path' ],1);

  file_put_contents( 'files/'.$file[ 'nameHTML' ] , $htmlFile);

  $db    =  new SQLite3('../../db/hallmonitor.db' );

  $SQL   = 'UPDATE slidescreen SET  content = \''. $file[ 'name' ] . '\' WHERE id = \'' .$ssid. '\'';
  $ret   = $db -> query( $SQL );

  if ( !move_uploaded_file( $_FILES['file']['tmp_name'], $file[ 'path' ] ))
	{ throw new RuntimeException('Failed to move uploaded file.');
  }

  // All good, send the response
  echo json_encode([
      'status' => 'ok',
      'path'   => $file[ 'path' ]
  ]);
}
 
catch (RuntimeException $e) {
	// Something went wrong, send the err message as JSON
	http_response_code(400);

	echo json_encode([
		'status' => 'error',
		'message' => $e->getMessage()
	]);
}
 