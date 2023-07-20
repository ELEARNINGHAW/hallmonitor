<?php
session_start();
if( $_SESSION[ 'user_email' ] == "" )
{ header( 'location:login/index.php' ); }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/jquery.dm-uploader.min.css" rel="stylesheet">
    <link href="css/upload-styles.css" rel="stylesheet">
     <link href="css/style.css" rel="stylesheet">
  </head>

  <body>
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/dist/jquery.dm-uploader.min.js"></script>
    <script src="js/upload-ui.js"></script>
    <script src="js/upload-xls-config.js"></script>
<?php

#try { if ( !isset($_FILES['file']['error']) ||   is_array($_FILES['file']['error']) )
#	{ throw new RuntimeException('Invalid parameters.');    }
/*
    switch ( $_FILES[ 'file' ][ 'error' ] ) 
	{   case UPLOAD_ERR_OK:             break;
        case UPLOAD_ERR_NO_FILE:        throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:     
       		case UPLOAD_ERR_FORM_SIZE:  throw new RuntimeException('Exceeded filesize limit.');
            default:                    throw new RuntimeException('Unknown errors.');
    }
 */

if (isset($_FILES[ 'file' ]))
{
  
  $file[ 'name' ] =  $_FILES[ 'file' ][ 'name' ];
  $file[ 'dir'  ] = 'backend/files/';
  $file[ 'path' ] = $file[ 'dir'  ].$file[ 'name' ]  ;

  if (!is_writable( $file[ 'dir' ] ) ){ die( 'Keine Schreibrechte im Verzeichnis ' . $file[ 'dir'  ]); }

  if ( !move_uploaded_file( $_FILES['file']['tmp_name'], $file[ 'path' ] ))
  { throw new RuntimeException('Failed to move uploaded file.');
  }
    // All good, send the response
    echo json_encode([
        'status' => 'ok',
        'path'   => $file[ 'path' ]
    ]);
 
   require('spreadsheet-reader/php-excel-reader/excel_reader2.php');
   require('spreadsheet-reader/SpreadsheetReader.php');

 $i = 0;
 $headlineNr = 3;   # In welcher XLS Zeile sind die Bezeichner der Spalten
 $table = 'users'; # Semestername = Tabellenname
 
 $heads[ 0 ][ 'dbName'  ] = "name"     ;
 $heads[ 1 ][ 'dbName'  ] = "vorname"  ;
 $heads[ 2 ][ 'dbName'  ] = "titel"    ;
 $heads[ 3 ][ 'dbName'  ] = "telefon"  ;
 $heads[ 4 ][ 'dbName'  ] = "email"    ;
 $heads[ 5 ][ 'dbName'  ] = "raum"     ;
 $heads[ 6 ][ 'dbName'  ] = "aufzug"   ;
 $heads[ 7 ][ 'dbName'  ] = "bereich"  ;
 $heads[ 8 ][ 'dbName'  ] = "einheit"  ;
 
 $db    =  new SQLite3('../db/personenraum.db' );
 
 if (isset($_GET['File']))
 {  $file[ 'path' ] = $_GET['File'];
 }

 try
 { $SQL = 'DELETE FROM "' . $table.'"';
   $ret   = $db -> query( $SQL );
   $Reader = new SpreadsheetReader($file[ 'path' ]);
   $order  = array("\r\n", "\n", "\r");
   foreach ($Reader as $Row)
   {
     $tmp[ 'name'    ] =  str_replace( $order, '', trim( $Row[ 0 ] ) ) ;
     $tmp[ 'vorname' ] =  str_replace( $order, '', trim( $Row[ 1 ] ) ) ;
     $tmp[ 'titel'   ] =  str_replace( $order, '', trim( $Row[ 2 ] ) ) ;
     $tmp[ 'telefon' ] =  str_replace( $order, '', trim( $Row[ 3 ] ) ) ;
     $tmp[ 'email'   ] =  str_replace( $order, '', trim( $Row[ 4 ] ) ) ;
     $tmp[ 'raum'    ] =  str_replace( $order, '', trim( $Row[ 5 ] ) ) ;
     $tmp[ 'aufzug'  ] =  str_replace( $order, '', trim( $Row[ 6 ] ) ) ;
     $tmp[ 'bereich' ] =  str_replace( $order, '', trim( $Row[ 7 ] ) ) ;
     $tmp[ 'einheit' ] =  str_replace( $order, '', trim( $Row[ 8 ] ) ) ;
     
     if ( $i++ == $headlineNr )
	 {
	 }	
     elseif ($i > $headlineNr  AND $tmp[ 'name' ] != '' AND  $tmp[ 'raum' ]  != '')
	 {
       $varia = $value = '';
       foreach( $tmp as $rk => $rv )
       {
         $varia .= trim( $rk ).',';
	     $value .= '"'.trim( $rv ).'",';
	   }
      
       $varia .= rtrim( $varia, "," );
       $value .= rtrim( $value, "," );
       
       $SQL = 'INSERT INTO "' . $table . '" ( ' .$varia. ' ) VALUES( '. $value. ' )';
	    echo "\n".$SQL;
       $ret   = $db -> query( $SQL );
       
     }
  } unlink ($file[ 'path' ]);
   }
  catch (Exception $E)
  { echo $E -> getMessage();
  }
}

else
{
?>
 <div id="drag-and-drop-zone-0" class="dm-uploader p-5">
   <h3 class="mb-5 mt-5 text-muted">EXCEL: PERSONEN-RAUM</h3>
   <div class="btn btn-primary btn-block mb-5">
     <span>open</span>
     <input type="file"  title='Click to add Files' />
     
   </div>
 </div> 
<?php	
}
?>
 
<div id="output" style ="width:100%; heigth:50px; background-color:#CCCCCC;" ></div>
<div  style ="float: right; margin-right: 50px;" ><button class="ssnew" ><a href="login/logout.php">Logout</a></button></div>
<div  style ="float: right; margin-right: 50px;" ><button class="ssnew" ><a href="editor.php">EDITOR</a></button></div>

</body>
</html>
