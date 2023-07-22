<?php

function getNewstickerData( $db, $raw, $today = '')
{ $newsticker = null;
  
  $stmt = $db->prepare( 'SELECT * FROM newsticker' );
  $res = $stmt->execute();

  while ( $row = $res -> fetchArray( ) )
  { $newsticker[ $row[ 'id' ] ] = $row;
  }
  
  if( $raw )
  {  return $newsticker;
  }
  else
  {  $html[ 'newsticker' ][ 'html' ] = <<<EOD
<div class="news red"><span>News</span><ul>
EOD;

  $html[ 'newsticker' ][ 'payload' ]= false;
  if(isset($newsticker))
  foreach ($newsticker as $nt)
  { if($nt['active'] AND $today < strtotime( $nt['best_before'])  )
  {  $html['newsticker']['html'] .= '<li><a href="#">+++ ' . $nt['text'] . ' +++ </a></li>';
     $html['newsticker']['payload']= true;
  }
  }
  $html['newsticker']['html'] .= <<<EOD
</ul>
</div>
EOD;

  return $html['newsticker'];
  }
}

function getScreenData( $db )
{ $screen = null;
  $stmt = $db->prepare( 'SELECT * FROM slidescreen' );
  $res = $stmt->execute();
  
  while ( $row = $res -> fetchArray() )
  {  $screen[ $row[ 'id' ] ]  = $row;
  }

  return $screen;
}

function getHtmlData( $db )
{ $html = getHTML2();
  $stmt = $db->prepare( 'SELECT * FROM html' );
  $res = $stmt->execute();

  while ( $row = $res -> fetchArray( ) )
  { $html[ $row[ 'name' ] ] = $row[ 'value' ];
  }

  return $html;
}


function actionHandler( $db )
{ $post = $_POST;
  if( isset( $post['action'] ) )
  { if ( $post['action'] == ' NEW ')
    $ss ['best_before'] =  $datepreset =  date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d") + 7 , date("Y")));
    $ss ['img'] = rand(1, 30);
    
    {
      $stmt = $db->prepare( 'INSERT INTO slidescreen (header, content, best_before, img , active )  VALUES ("" , "" , ? ,  ? , "true" )' );
      $stmt->bindValue( 1 , $ss ['best_before'] , SQLITE3_TEXT );
      $stmt->bindValue( 2 , $ss ['img']         , SQLITE3_TEXT );
      $res = $stmt->execute();
    }
  }
  
  if( isset( $_POST['ntdel']) )
  { $id   =  $_POST['ntid'];
    $stmt = $db->prepare( 'DELETE FROM newsticker WHERE id = ?' );
    $stmt->bindValue( 1 , $id , SQLITE3_INTEGER );
    $res = $stmt->execute();
  }
  
  if( isset($_POST['nttext0'] ) AND  trim( $_POST['nttext0']) != '' )
  { $nttext = $_POST['nttext0'];
    $ntdate = $_POST['ntdate0'];
  
    $stmt = $db->prepare( 'INSERT INTO newsticker (text, best_before, active ) VALUES ( ? , ? , "1" )' );
    $stmt->bindValue( 1 , $nttext , SQLITE3_TEXT );
    $stmt->bindValue( 2 , $ntdate , SQLITE3_TEXT );
    $res = $stmt->execute();
   
    $SQL    = 'INSERT INTO newsticker (text, best_before, active ) VALUES ("'.$nttext.'","'.$ntdate.'","1" )';
    $db -> query( $SQL );
  }
  
  if( isset( $_POST['ntid']) )
  { $nt   = $_POST;
    $ntNr = $nt['ntid'];
   # $SQL  = 'UPDATE newsticker SET ';
    
    if ( isset ( $nt[ 'nttext'   .$ntNr ] ) )
    {
      $stmt = $db->prepare( 'UPDATE newsticker SET text = ? WHERE id = ?' );
      $stmt->bindValue( 1 , $nt[ 'nttext'.$ntNr  ] , SQLITE3_TEXT );
      $stmt->bindValue( 2 , $ntNr                  , SQLITE3_TEXT );
      $res = $stmt->execute();
    }
    if ( isset ( $nt[ 'ntdate'   .$ntNr ] ) )
    { $stmt = $db->prepare( 'UPDATE newsticker SET best_before = ? WHERE id = ?' );
      $stmt->bindValue( 1 , $nt[ 'ntdate'.$ntNr  ] , SQLITE3_TEXT );
      $stmt->bindValue( 2 , $ntNr                  , SQLITE3_TEXT );
      $res = $stmt->execute();
    }
    if ( isset ( $nt[ 'ntactive' .$ntNr ] ) )
    { $stmt = $db->prepare( 'UPDATE newsticker SET active = 1 WHERE id = ?' );
      $stmt->bindValue( 1 , $ntNr                  , SQLITE3_TEXT );
      $res = $stmt->execute();
    }
    else
    { $stmt = $db->prepare( 'UPDATE newsticker SET active = 0 WHERE id = ?' );
      $stmt->bindValue( 1 , $ntNr                  , SQLITE3_TEXT );
      $res = $stmt->execute();
    }
  
    if ( isset ( $nt[ 'ntdel'  ] ) )
    { $stmt = $db->prepare( 'DELETE FROM newsticker WHERE id = ?' );
      $stmt->bindValue( 1 , $ntNr                  , SQLITE3_TEXT );
      $res = $stmt->execute();
    }
  }
}

function getNewstickerEditor($db)
{ $html = '<div class="ntline">';
  $newsticker  = getNewstickerData( $db, true );
  if(isset ($newsticker))
  foreach ( $newsticker as $nt )
  { if( $nt[ 'active' ]  == 1 ) { $chk = 'checked'; $bgc = '#FFFFFF'; } else { $chk = ''; $bgc = '#BEBEBE'; }
    
    
    $chk = ''; if($nt['active']) {$chk = 'checked'; }
    $html .= '<div class="ntline" id="netline'   .$nt[ 'id' ]. '" style="background-color: '.$bgc.';"  >'."\n";
    $html .=  '<form action="editor.php" method="post" id = "ntform' .$nt[ 'id' ]. '">';
    $html .=  '<span> <input type="text"      class="nttext"   name="nttext'   .$nt[ 'id' ]. '" id="nttext'   .$nt[ 'id' ]. '" value="'.       $nt[ 'text'        ].'" ></span>'."\n";
    $html .=  '<span> <input type="date"      class="ntdate"   name="ntdate'   .$nt[ 'id' ]. '" id="ntdate'   .$nt[ 'id' ]. '" value="'.       $nt[ 'best_before' ].'" ></span> '."\n";
    $html .=  '<span> <input type="checkbox"  class="ntactive" name="ntactive' .$nt[ 'id' ]. '" id="ntactive' .$nt[ 'id' ]. '" value="active'. $nt[ 'id'          ].'" ' .$chk. ' ></span>'."\n";
    $html .=  '<span> <input type="hidden"    class="ntid"     name="ntid"                      id="ntid'     .$nt[ 'id' ]. '" value="'.       $nt[ 'id'          ].'" ></span>'."\n";
    $html .=  '<span> <input type="submit"    class="ntdel"    name="ntdel"                     id="ntdel'    .$nt[ 'id' ]. '" value=" DEL " ></span>'."\n";
    $html .=  '</form>'."\n";
    $html .=  '</div>'."\n";
  
    $html .=  '<script>';
    $html .=  "\n"."$( '#ntactive" .$nt[ 'id' ]. "' ).change( function()  { if( this.checked )  {  $('#netline" .$nt[ 'id' ]. "').css('background-color', 'red');  }  else{ $('#netline".$nt[ 'id' ]. "').css('background-color', 'blue'); }  $('#ntform" .$nt[ 'id' ]. "').submit(); });";
    $html .=  "\n"."$( '#nttext"   .$nt[ 'id' ]. "' ).change( function()  {  $('#ntform" .$nt[ 'id' ]. "').submit(); } );";
    $html .=  "\n"."$( '#ntdate"   .$nt[ 'id' ]. "' ).change( function()  {  $('#ntform" .$nt[ 'id' ]. "').submit(); } );";
  
    $html .=  "\n"."var TodayDate = new Date();";
    $html .=  "\n".'var date = new Date( $("#ntdate' .$nt[ 'id' ]. '" ).val() ); ';
    $html .=  "\n".' if (date >= TodayDate) { bgc = "#FFFFFF";  } else { bgc = "#FF0000";  };';
    $html .=  "\n".' $( "#ntdate' .$nt[ 'id' ]. '").css("background-color", bgc ) ;'  ;
  
    $html .=  '</script>';
  }
  $nt[ 'id' ] = 0;

  $datepreset =  date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d") + 7 , date("Y")));
  
  $html .=  '<div class="ntline" id="netline'   .$nt[ 'id' ]. '" >'."\n";
  $html .=  '<form action="editor.php" method="post">';
  $html .=  '<span> <input type="text"      class="nttext"   name="nttext'   .$nt[ 'id' ]. '" id="nttext'   .$nt[ 'id' ]. '" value=" " ></span>'."\n";
  $html .=  '<span> <input type="date"      class="ntdate"   name="ntdate'   .$nt[ 'id' ]. '" id="ntdate'   .$nt[ 'id' ]. '" value="'. $datepreset.'" ></span> '."\n";
  $html .=  '<span> <input type="hidden"    class="ntupd"    name="ntupd'                . '" id="ntupd'    .$nt[ 'id' ]. '" value=" " ></span>'."\n";
  $html .=  '<span> <input type="submit"    class="ntsub"    name="ntsub'    .$nt[ 'id' ]. '" id="ntsub'    .$nt[ 'id' ]. '" value=" NEU " ></span>'."\n";
  $html .=  '</form>'."\n";
  $html .=  '</div>'."\n";
  $html .=  '</div>'."\n";
  
  return $html;
}

function deb($val, $kill= false)
{ echo "<pre>";
  print_r($val);
  echo "</pre>";
  if ($kill) die();
}

function getScreenSlideEditor($db)
{
  $screenData = getScreenData( $db );
  $html = '<div id="result"></div>';
  if (isset($screenData))
  foreach ( $screenData as $ss )
  { $html.= getScreenSlideRow($ss);
  }
  
  $html .=  "\n" . '<form action="editor.php" method="POST"> ';
  $html .=  "\n" . ' <div style ="float: right; margin-right: 50px;"> ';
  $html .= '<input type="submit" class="ssnew" name="action" id="ssnew" value=" NEW ">';
  $html .=  '</div>';
  
  $html .=  "\n" . ' <div style ="float: left; margin-right: 50px;"> ';
  $html .=  '<button class="ssnew" ><a href="login/logout.php">Logout</a></button>';
  $html .=  '<button class="ssnew" ><a href="upload-xls-1.php">CSV: W22 Upload</a></button>';
  $html .=  '<button class="ssnew" ><a href="upload-xls-2.php">CSV: S23 Upload</a></button>';
  $html .=  '<button class="ssnew" ><a href="upload-xls-0.php">CSV: PERSON-RAUM</a></button>';
  $html .=  '</div>';
  $html .=  '</form>';

  $html .=  '<script>';
  $html .=  "function dateHandler(e){ ;}";
  $html .=  '</script>';
  
  return $html;
}


function getScreenslideData( $html, $screen, $today, $screenslide )
{ $html[ 'screenslide' ] =  $screenslide;
  if (isset($html[ 'screenslide' ]))
    foreach ($screen as $sc)
    { if ( $sc[ 'active' ] == 'true' AND $today <= strtotime( $sc['best_before']) )
      { if ( $sc[ 'content' ] AND $sc[ 'content' ] != '<p><br data-mce-bogus="1"></p>'
        AND $sc[ 'content' ] != '<p><br></p>' )
      { $sc[ 'header' ] = '<a href="index.php?cNr=' .$sc['id'] . '">' .$sc['header']. '<p  style="position:absolute;top: 900px; width:100%; text-align:center;  font-size: xx-large ;">[weitere Infos >>]</p></a>' ;
      }
        $html[ 'screenslide' ] .= '<div data-img="i/jpg/lo/' . $sc['img'] . '-lo.jpg" class="any inverse"><div class="mobg"><div class="mo">' . $sc['header'] . '</div></div></div>'."\n";
      }
    }
  $html['screenslide'] .= '</div>';
  return $html['screenslide'] ;
}




function getScreenSlideRow($ss)
{
# deb($ss);
#$ss ['id']
#$ss['header']
#$ss['content']
#$ss ['best_before']
#$ss ['img']
#$ss['active']
if( $ss[ 'active'] == "true" ) { $chk = 'checked'; $bgc = '#FFFFFF'; } else { $chk = ''; $bgc = '#BEBEBE'; }
 
$html  =  "\n" . '<div                           class = "ssline" id="sslineX' .$ss[ 'id' ]. '" style="background-color:' .$bgc. '"  > ';
$html .=  "\n" . '<div                           class = "ssline2" > <div   name ="ssheader_'  .$ss[ 'id' ]. '"  id = "ssheader__'  .$ss[ 'id' ]. '" class = "ssheader_" >'        .$ss[ 'header'  ]. '</div></div> ';
$html .=  "\n" . '<div                           class = "ssline2" > <input name ="ssspinner'  .$ss[ 'id' ]. '"  id = "ssspinner_'  .$ss[ 'id' ]. '" class = "ssspinner" value="'  .$ss[ 'img'     ]. '"></div>          ';
$html .=  "\n" . '<div                           class = "ssline2" > <div   name ="sscontent'  .$ss[ 'id' ]. '"  id = "sscontent_'  .$ss[ 'id' ]. '" class = "sscontent" >'        .$ss[ 'content' ]. '</div></div><div> ';

$html .=  "\n" . '<div                      class = "ssline2"   id = "ssblockC'    .$ss[ 'id' ]. '">';
$html .=  "\n" . ' <input type = "date"     class = "ssdate"    name ="ssdate"   id = "ssdate'    .$ss[ 'id' ]. '" value="'         .$ss[ 'best_before' ]. '" ><br>';
$html .=  "\n" . ' <input type = "checkbox" class = "ssactive"  name ="ssactive" id = "ssactive'  .$ss[ 'id' ]. '" value="active" ' .$chk. ' ><br>';

$html .=  "\n" . '<div id="drag-and-drop-zone' .$ss[ 'id' ]. '" class="dm-uploader">';
$html .=  "\n" . '<h3 class="mb-5 mt-5 text-muted">PDF</h3> ';
$html .=  "\n" . '<div class="btn btn-primary btn-block mb-5"> ';
$html .=  "\n" . '<span>open</span> ';
$html .=  "\n" . '<input type="file"  title="Click to add Files" /> ';
$html .=  "\n" . '<input type="hidden" id="x38" name="ssid" value="'.$ss[ 'id' ].'" /> ';
$html .=  "\n" . '</div></div> ';
$html .=  "\n" . ' ';

$html .=  "\n" . '<input type = "button"    class = "ssdel"     name ="action"   id = "ssdel'     .$ss[ 'id' ]. '" value=" DEL " >';
$html .=  "\n" . '</div>';
$html .=  "\n" . '</div>';
$html .=  "\n" . '</div>';

$html .=  "\n".'<script>';
$html .=  "\n".' $( "#ssspinner_'  .$ss[ 'id' ]. '" ).spinner(';
$html .=  "\n".' { ';
$html .=  "\n".' min:0, ';
$html .=  "\n".' max:30, ';
$html .=  "\n".' icons: { down: "custom-down-icon", up: "custom-up-icon" }, ';
$html .=  "\n".' change: function( event, uic' .$ss[ 'id' ]. ' )';
$html .=  "\n".' {  $( "#result" ).load( "inc/ajax.php", { "update[]": ["SSPICH", ' .$ss[ 'id' ]. ',  $("#ssspinner_' .$ss[ 'id' ]. '" ).spinner( "value" ) ] } );   } ';
$html .=  "\n".', spin: function( event, uis' .$ss[ 'id' ]. ' ) ';
$html .=  "\n".' {  myurl = \'url( "i/jpg/lo/\'+uis' .$ss[ 'id' ]. '.value+\'-lo.jpg" )\'; '."\n".' $( "#ssheader__'  .$ss[ 'id' ]. '" ).css( "background-image" , myurl );} ';
$html .=  "\n".' }); ';
$html .=  "\n".' $( "#ssspinner_' .$ss[ 'id' ]. '" ).spinner( "value", '.$ss[ 'img' ].' );';

$html .=  "\n".'tinymce.init(';
$html .=  "\n".'{selector: "#ssheader__'  .$ss[ 'id' ]. '",';
$html .=  "\n".'menubar: false,';
$html .=  "\n".'inline: true,';
$html .=  "\n".' paste_as_text: true,';
$html .=  "\n"." plugins: [";
$html .=  "\n"."'advlist autolink lists link image charmap print preview anchor',";
$html .=  "\n"." 'searchreplace visualblocks code fullscreen',";
$html .=  "\n"." 'insertdatetime media table paste wordcount'";
$html .=  "\n"."],";
$html .=  "\n".'toolbar: "undo redo | bold italic underline",';
$html .=  "\n".'content_css: "css/minicontent.css", ';
$html .=  "\n".'setup: (editor) => ';
$html .=  "\n".'{ editor.on("focusOut", () => ';
$html .=  "\n".'{ console.log(  $("#ssheader__' .$ss[ 'id' ]. '" ).html()  );  ';
$html .=  "\n".' $( "#result" ).load( "inc/ajax.php", { "update[]": ["SSHEAD", ' .$ss[ 'id' ]. ',  $("#ssheader__' .$ss[ 'id' ]. '" ).html()] } );';
$html .=  "\n".'';
$html .=  "\n".' });';
$html .=  '}';
$html .=  '}); ';


$html .=  "\n".' tinymce.init({';
$html .=  "\n".' selector: "#sscontent_'  .$ss[ 'id' ]. '",';
$html .=  "\n".' menubar: false,';
$html .=  "\n".' inline: true,';
$html .=  "\n".' paste_as_text: true,';
$html .=  "\n".' toolbar: "undo redo | bold italic underline | code",';
$html .=  "\n".' content_css: "css/minicontent.css", ';

$html .=  "\n"." plugins: [";
$html .=  "\n"."'advlist autolink lists link image charmap print preview anchor',";
$html .=  "\n"." 'searchreplace visualblocks code fullscreen',";
$html .=  "\n"." 'insertdatetime media table paste wordcount'";
$html .=  "\n"."],";
$html .=  "\n"."toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat code ',";
#  $html .=  "\n"."  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'";
$html .=  "\n"."";
$html .=  "\n"."";
$html .=  "\n"."";

$html .=  "\n".' setup: (editor) => ';
$html .=  "\n".' { editor.on("focusOut", () => ';
$html .=  "\n".' { console.log(  $("#sscontent_' .$ss[ 'id' ]. '" ).html()  );  ';
$html .=  "\n".' $( "#result" ).load( "inc/ajax.php", { "update[]": ["SSCONT", ' .$ss[ 'id' ]. ',  $("#sscontent_' .$ss[ 'id' ]. '" ).html()] } );';
$html .=  "\n".' });}}); ';
$html .=  "\n".'';
$html .=  '';
$html .=  '';
$html .=  ' function success(){ ;} ';
$html .=  '';
$html .=  '';

$html .=  "\n".'$( "#ssheader__' .$ss[ 'id' ]. '" ).css( "background-image" , "url( \'i/jpg/lo/'  .$ss[ 'img' ]. '-lo.jpg\' )" );';

$html .=  "$( '#ssactive" .$ss[ 'id' ]. "' ).change( function()";
$html .=  '{';
$html .=  "\n".' $( "#result" ).load( "inc/ajax.php", { "update[]": ["SSACTIV", ' .$ss[ 'id' ]. ',  $("#ssactive' .$ss[ 'id' ]. '" ).prop("checked") ] });';
$html .=  "\n".' if ($("#ssactive' .$ss[ 'id' ]. '" ).is(":checked")) { bgc = "#FFFFFF";  } else { bgc = "#BEBEBE";  };'  ;
$html .=  "\n".' $( "#sslineX' .$ss[ 'id' ]. '").css("background-color", bgc ) ;'  ;
$html .=  '});';

$html .=  "\n"."$( '#ssdate"      .$ss[ 'id' ]. "' ).change( function() ";
$html .=  "\n".'{ $( "#result" ).load( "inc/ajax.php", { "update[]": ["SSDATE", ' .$ss[ 'id' ]. ',  $("#ssdate' .$ss[ 'id' ]. '" ).val()] })}';
$html .=  ");";

$html .=  "\n"."var TodayDate = new Date();";
$html .=  "\n".'var date = new Date( $("#ssdate' .$ss[ 'id' ]. '" ).val() ); ';
$html .=  "\n".' if (date >= TodayDate) { bgc = "#FFFFFF";  } else { bgc = "#FF0000";  };';
$html .=  "\n".' $( "#ssdate' .$ss[ 'id' ]. '").css("background-color", bgc ) ;'  ;

$html .=  "$( '#ssdel"      .$ss[ 'id' ]. "' ).click( function() ";
$html .=  "\n".'{ $( "#result" ).load( "inc/ajax.php", { "delete[]": ["SSDELE", ' .$ss[ 'id' ]. ',  $("#ssdel' .$ss[ 'id' ]. '" ).val()] }) ; $("#sslineX' .$ss[ 'id' ]. '" ).remove()   }';
$html .=  ");";

$html .=  "\n"."$('#drag-and-drop-zone" .$ss[ 'id' ]. "').dmUploader( ";
$html .=  "\n"." {
 url: 'backend/upload.php?ssid=" .$ss[ 'id' ]. "',
 extFilter: [ 'jpg', 'pdf', 'png', 'gif' ],
 maxFileSize: 3000000,
 onDragEnter: function(){ this.addClass('active');    },
 onDragLeave: function(){ this.removeClass('active'); },
 onInit:      function(){ ui_add_log('Penguin initialized :)', 'info');
  },
 onComplete : function(){
  },
 onNewFile:   function(id, file){ ui_add_log('New file added #' + id);
    ui_multi_add_file(id, file);
  },
  onBeforeUpload: function(id)
 { ui_add_log('Starting the upload of #' + id);
   ui_multi_update_file_status(id, 'uploading', 'Uploading...');
   ui_multi_update_file_progress(id, 0, '', true);
 },
  onUploadCanceled: function(id)
 {  ui_multi_update_file_progress(id, 0, 'warning', false);
 },
  onUploadProgress: function(id, percent){ ui_multi_update_file_progress(id, percent); },
  onUploadSuccess: function(id, data)
  { ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
    ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
    ui_multi_update_file_status(id, 'success', 'Upload Complete');
    ui_multi_update_file_progress(id, 100, 'success', false);
    $( '#sscontent_" .$ss[ 'id' ]. "' ).load( 'inc/ajax.php', { 'load[]': ['SSCONT', " .$ss[ 'id' ]. " ] })

    
  },
  onUploadError: function(id, xhr, status, message)
  { ui_multi_update_file_status(id, 'danger', message);
    ui_multi_update_file_progress(id, 0, 'danger', false);
  },
  onFallbackMode: function(){ ui_add_log('Plugin cant be used here, running Fallback callback', 'danger'); },
  onFileSizeError: function(file){ /* ui_add_log('File '' + file.name + '' cannot be added: size excess limit', 'danger');*/ }
}
";
$html .=  "\n".");";
$html .=  '</script>';
return $html;
}

function getPreviewScreen()
{ $html  =  "\n" . ' <div class="wrap">';
  $html .=  "\n" . '  <iframe class="frame" src="./index.php"></iframe>';
  $html .=  "\n" . '</div>';
  return $html;
}

function getHTML2()
{
  $html['screenslide'] = <<<EOD
<!-- Fotorama -->
<div class="fotorama"
  data-width="100%"
  data-max-width="100%"
  data-loop="true"
  data-transition="crossfade"
  data-nav="false"
  data-shuffle="true"
  data-autoplay="10000"
  data-arrows="true"
  data-click="true"
  data-swipe="false"
>
EOD;
  $html['menu'] = <<<EOD
<nav class="c-circle-menu js-menu">
  <button class="c-circle-menu__toggle js-menu-toggle" onclick="startTimeOutTimer();">
    <span>Toggle</span>
  </button>
  <ul class="c-circle-menu__items">
    <li class="c-circle-menu__item" id="myButton1" >
      <div onclick="window.location='klausshowW22.php'" class="c-circle-menu__link">
        <img src="img/search.svg" alt="">
      </div>
    </li>

  <li class="c-circle-menu__item" id="myButton2" >
          <div onclick="window.location='klausshowS23.php'" class="c-circle-menu__link">
        <img src="img/search.svg" alt="">
      </a>
    </li>
    
    <li class="c-circle-menu__item" id="myButton3" >
      <div onclick="window.location='personenraum.php'" class="c-circle-menu__link">
        <img src="img/search.svg" alt="">
      </div>
    </li>

      <li class="c-circle-menu__item" id="myButton4" >
     <div onclick="window.location='pong.html'" class="c-circle-menu__link">
        <img src="img/pong.svg" alt="">
      </a>
    </li>
 <!--
    <li class="c-circle-menu__item" id="myButton5" >
      <a href="raumfinder.html" class="c-circle-menu__link">
        <img src="img/tools.svg" alt="">
      </a>
    </li>
    -->

  </ul>
  <div class="c-circle-menu__mask js-menu-mask"></div>
</nav>

<script src="js/dist/circleMenu.min.js"></script>
<script>
  var el = '.js-menu';
  var myMenu = cssCircleMenu(el);
</script>

<script>
tippy('#myButton1', { allowHTML: true,   maxWidth: 'none',   placement: 'left',  offset: [0, 0],
content: '<div class="tooltip">Klausurnoten WS22</div>',
  });
 tippy('#myButton2', { allowHTML: true,   maxWidth: 'none',   placement: 'left', offset: [0, 0],
	content: '<div  class="tooltip">Klausurnoten SS23</div>',
  })
  tippy('#myButton3', { allowHTML: true,  maxWidth: 'none',    placement: 'left', offset: [0, 0],
	content: '<div  class="tooltip">Raumfinder</div>',
  });

  tippy('#myButton4', { allowHTML: true,  maxWidth: 'none',    placement: 'left', offset: [0, 0],
	content: '<div  class="tooltip">PONG!</div>',
  });

  tippy('#myButton5', { allowHTML: true,  maxWidth: 'none',    placement: 'left', offset: [0, 0],
	content: '<div  class="tooltip">Raumfinder</div>',
  });
</script>
EOD;
  
$html['editorhead'] = <<<EOD
<!DOCTYPE html>
<html>
<head>
<title>hallmonitor</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<script src="js/bootstrap.min.js"></script>

<script src = "js/tinymce/tinymce.min.js"></script>
<script src = "js/jquery-1.10.2.min.js"></script>
<script src = "js/jquery-ui-1.13.2/jquery-ui.min.js"></script>

<script src="js/dist/jquery.dm-uploader.min.js"></script>
<script src="js/upload-ui.js"></script>
<script src="js/upload-config.js"></script>


<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/jquery.dm-uploader.min.css" rel="stylesheet">
<link href="css/upload-styles.css" rel="stylesheet">
<link href = "js/jquery-ui-1.13.2/jquery-ui.min.css" rel="stylesheet" >

<style>
  .ssline, .ntline{  border: solid 1px black; margin: 2px; padding: 5px; }
  .ssline {  display: flex;  }
  .ssline2 {  border: solid 2px black; margin: 10px; padding: 10px; }
  .ssheader_, .sscontent {float:left;width:600px; border: solid 2px gray;}
  .ssspinner, .ssheader_, .sscontent {  height:400px;   background-size: cover; margin-right:  0px; margin-left: 10px; }
  .ssspinner { width:40px; margin-right: 0px;}
  .sscontent { overflow: auto;}
  .ssdate { font-size: 18px; height: 50px; }
  .ssdel  {  padding: 15px; margin:10px; margin-left:0px;  position: relative;  }
  .ssnew  {  padding: 15px; margin:10px; }
  .ssactive  {height: 40px; position: relative; margin:10px  ; margin-left:0px;width: 40px;}
</style>


<style>
  .wrap  { position: absolute;   top: 0px;  right: 2px;  width: 320px;  height: 300px;  padding: 0;  overflow: hidden; }
  .frame { width: 1280px; height: 800px; border: 0; -ms-transform: scale( 0.25 ); -moz-transform: scale( 0.25 ); -o-transform: scale( 0.25 );  -webkit-transform: scale(0.25);   transform: scale(0.25);-ms-transform-origin: 0 0;   -moz-transform-origin: 0 0;   -o-transform-origin: 0 0;   -webkit-transform-origin: 0 0;   transform-origin: 0 0;}
</style>

<style>

.nttext, .ntdate, .ntactive  { height: 40px; width: 40px; margin: 5px; }
.ntactive  { position: relative; top: 13px }
.nttext  { width: 600px; }
.ntdate  { width: 180px; font-size: 18px; }
.ntdel, .ntsub, .sssav  { padding: 11px; }
</style>

<script src="js/tinymce/tinymce.min.js"></script>
</head>
<body style="padding-top: 0px;">

EOD;
  return $html;
}

?>