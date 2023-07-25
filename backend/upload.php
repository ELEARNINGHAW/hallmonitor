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
  $file[ 'path' ] = 'files/'.$file[ 'name' ]  ;
 
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
 