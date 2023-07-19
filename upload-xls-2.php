<?php
session_start();
if( $_SESSION[ 'user_email' ] == "" )
{ header( 'location:login/index.php' );
}
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
  $file[ 'path' ] = [ 'dir'  ].$file[ 'name' ]  ;

  if (!is_writable($file[ 'dir' ])){ die( 'Keine Schreibrechte im Verzeichnis ' .$file[ 'dir'  ]); }

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
 $headlineNr = 4;   # In welcher XLS Zeile sind die Bezeichner der Spalten
 $semester = '23S'; # Semestername = Tabellenname
 
 $heads[ 0 ][ 'xslName' ] = "Prüfender(in)"  ;
 $heads[ 1 ][ 'xslName' ] = "Prüfung"	     ;
 $heads[ 2 ][ 'xslName' ] = "Prüfungsnr."	 ;
 $heads[ 3 ][ 'xslName' ] = "matrikelnr"	 ;
 $heads[ 4 ][ 'xslName' ] = "bewertung"      ;
 $heads[ 0 ][ 'dbName'  ] = "Prüfende(r)"    ;
 $heads[ 1 ][ 'dbName'  ] = "Prüfung"        ;
 $heads[ 2 ][ 'dbName'  ] = "Prüfungsnr"     ;
 $heads[ 3 ][ 'dbName'  ] = "Matnr3"         ;
 $heads[ 4 ][ 'dbName'  ] = "Bewertung1Dez"  ;

 $db    =  new SQLite3('../db/klausurnotenS.db' );
 
 if (isset($_GET['File']))
 {  $file[ 'path' ] = $_GET['File'];
 }

 try
 { $SQL = 'DELETE FROM "' . $semester.'"'; 
   $ret   = $db -> query( $SQL );
 
   $Reader = new SpreadsheetReader($file[ 'path' ]);
   foreach ($Reader as $Row)
   { ++$i; 
     if ( $i == $headlineNr )
	 { foreach( $Row as $rk=> $rv )
	   { foreach( $heads as $hk => $hv )
		 {  if ( $hv['xslName'] == $rv )  { $heads[$hk]['colNr'] = $rk; }
		 }
	   }
	 }	
     elseif ($i > $headlineNr)
	 { $varia = $value = '';
	   foreach( $heads as $hk => $hv ) 
	   { $val0 = $Row[ $hv['colNr'] ];
		 if      ( $heads[  $hk ][ 'xslName' ] == 'matrikelnr' ) 
 		 { $val = substr($val0 , -3) ; }
 
         else if ( $heads[  $hk ][ 'xslName' ] == 'bewertung' )   
	    { if (ctype_digit(trim($val0)))  { $val0= $val0.'_'; $val = $val0[0].'.'.$val0[1]; }
       	  else                           { $val = $val0; } 
		}
		else                             { $val = $val0; }
        
        $varia .= '"'. $hv['dbName'] .'",';
	    $value .= '"'. $val .'",';
	   }
   
		$varia = rtrim($varia, "," );
		$value = rtrim($value, "," );
		
		$SQL = 'INSERT INTO "' . $semester . '" ( ' .$varia. ' ) VALUES( '. $value. ' )';	
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
 <div id="drag-and-drop-zone-2" class="dm-uploader p-5">
   <h3 class="mb-5 mt-5 text-muted">EXCEL S23</h3>
   <div class="btn btn-primary btn-block mb-5">
     <span>open</span>
     <input type="file"  title='Click to add Files' />
     
   </div>
 </div> 
<?php	
}
?>
 
<div id="output" style ="width:100%; heigth:50px; background-color:#CCCCCC;" >123</div>


<div  style ="float: right; margin-right: 50px;" ><button class="ssnew" ><a href="login/logout.php">Logout</a></button></div>
  

  </body>
</html>
