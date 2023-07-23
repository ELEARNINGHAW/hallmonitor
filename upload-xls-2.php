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

$headlineNr = 4;   # In welcher XLS Zeile sind die Bezeichner der Spalten
$semester = '23S'; # Semestername = Tabellenname
$heads[ 0 ][ 'csvName' ] = "Prüfender(in)"  ; #
$heads[ 1 ][ 'csvName' ] = "Prüfung"	     ; #
$heads[ 2 ][ 'csvName' ] = "Prüfungsnr."	 ; #
$heads[ 3 ][ 'csvName' ] = "matrikelnr"	 ; #
$heads[ 4 ][ 'csvName' ] = "bewertung"      ; #
$heads[ 0 ][ 'dbName'  ] = "Prüfende(r)"    ;
$heads[ 1 ][ 'dbName'  ] = "Prüfung"        ;
$heads[ 2 ][ 'dbName'  ] = "Prüfungsnr"     ;
$heads[ 3 ][ 'dbName'  ] = "Matnr3"         ;
$heads[ 4 ][ 'dbName'  ] = "Bewertung1Dez"  ;
$heads[ 0 ][ 'csvrow'     ] =  13    ;
$heads[ 1 ][ 'csvrow'     ] =  14     ;
$heads[ 2 ][ 'csvrow'     ] =  16    ;
$heads[ 3 ][ 'csvrow'     ] =  0      ;
$heads[ 4 ][ 'csvrow'     ] =  1  ;


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
 
 $i = 0;

 $db    =  new SQLite3('../db/klausurnotenS.db' );
 
 if (isset($_GET['File']))
 {  $file[ 'path' ] = $_GET['File'];
 }

 try
 { $SQL = 'DELETE FROM "' . $semester.'"';
   $ret   = $db -> query( $SQL );
   
   $order  = array("\r\n", "\n", "\r");
   if (($handle = fopen($file[ 'path' ], "r")) !== FALSE)
   { while (($Reader = fgetcsv($handle, 1000, ";")) !== FALSE)
     $R[] = $Reader;
     fclose($handle);
   }
  
   foreach ($R as $Row)
   {
       if ( $headlineNr < $i++
       AND trim($Row[ $heads[ 0 ][ 'csvrow' ] ] ) != ''
       AND trim($Row[ $heads[ 1 ][ 'csvrow' ] ] ) != ''
       AND trim($Row[ $heads[ 2 ][ 'csvrow' ] ] ) != ''
       AND trim($Row[ $heads[ 3 ][ 'csvrow' ] ] ) != ''
       AND trim($Row[ $heads[ 4 ][ 'csvrow' ] ] ) != '' )
	  { $varia = $value = '';
       foreach( $heads as $hk => $hv )
	   {
      
         $var0 =  str_replace( $order, '', trim( $hv[ 'dbName' ]  ) ) ;
         #$var0 = $hv[ 'dbName' ]          ;
         $val0 = $Row[ $hv[ 'csvrow' ] ]  ;
         
		 if     (  $hv[ 'csvrow' ]  == 0 )
 		 { $val = substr($val0 , -3) ; }
 
         else if (  $hv[ 'csvrow' ]  == 1 )
	     { if (ctype_digit(trim($val0)))  { $val0= $val0.'_'; $val = $val0[0].'.'.$val0[1]; }
           else                           { $val = $val0; }
		 }
   
		else                             { $val = $val0; }
        
        $varia .= '"'. $var0 .'",';
	    $value .= '"'. $val .'",';
        
        }
   
		$varia = rtrim($varia, "," );
		$value = rtrim($value, "," );
		
		$SQL = 'INSERT INTO "' . $semester . '" ( ' .$varia. ' ) VALUES( '. $value. ' )';
	 
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
  echo '<div style="padding: 20px; margin:10px; color:white; float: left; width: 180px; border: solid 2px #666666;">';
  echo 'Spaltennamen <br>' ;
  echo '[A] '.$heads[ 3 ][ 'csvName'  ] . " <br>" ;
  echo '[B] '.$heads[ 4 ][ 'csvName'  ] . " <br>" ;
  echo '[N] '.$heads[ 0 ][ 'csvName'  ] . " <br>" ;
  echo '[O] '.$heads[ 1 ][ 'csvName'  ] . " <br>" ;
  echo '[Q] '.$heads[ 2 ][ 'csvName'  ] . " <br>" ;
  echo 'Seperator = Semikolon'         . " <br>" ;
  echo '</div>';
  
?>
 <div id="drag-and-drop-zone-2" class="dm-uploader p-5">
   <h3 class="mb-5 mt-5 text-muted">NOTEN.CSV S23</h3>
    <h3 id='log' class="mb-5 mt-5 text-muted"></h3>
   <div class="btn btn-primary btn-block mb-5">
     <span>open</span>
     <input type="file"  title='Click to add Files' />
   </div>  <div style="padding: 20px; margin:10px; color:white;   border: solid 2px #666666;">
           Datensatz wird aktezpiert: Wenn Daten in ALLEN Spalten [A][B][N][O][Q] vorhanden sind.
      
   </div>
 </div> 
<?php	
}
?>
 
<div id="output" style ="width:100%; heigth:50px; background-color:#CCCCCC;" >123</div>


<div  style ="float: right; margin-right: 50px;" ><button class="ssnew" ><a href="login/logout.php">Logout</a></button></div>
<div  style ="float: right; margin-right: 50px;" ><button class="ssnew" ><a href="editor.php">EDITOR</a></button></div>

  </body>
</html>
