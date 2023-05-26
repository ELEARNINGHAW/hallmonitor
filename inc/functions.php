<?php

function getNewstickerData( $db, $raw, $today = '')
{ $newsticker = null;
  $res = $db->query('SELECT * FROM newsticker');
  while ($row = $res -> fetchArray( ) )
  { $newsticker[ $row[ 'id' ] ] = $row;
  }
  
  if($raw)
  {  return $newsticker;
  }
  else
  {  $html['newsticker']['html'] = <<<EOD
<div class="news red">
<span>News</span>
<ul>
EOD;

  $html['newsticker']['payload']= false;
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
  $res = $db -> query( 'SELECT * FROM slidescreen' );
  while ( $row = $res -> fetchArray() )
  {  $screen[ $row[ 'id' ] ]  = $row;
  }
  return $screen;
}


function getScreenslideData( $html, $screen, $today, $screenslide )
{
  $html[ 'screenslide' ] =  $screenslide;
  foreach ($screen as $sc)
  {
    #deb($sc[ 'active' ]);
    #deb( $today <=  strtotime( $sc['best_before']) );
    if ( $sc[ 'active' ] AND $today <= strtotime( $sc['best_before']) )
  { if ( $sc[ 'content' ] ) { $sc[ 'header' ] = '<a href="index.php?cNr=' .$sc['id'] . '">' .$sc['header']. '</a>' ; }
    $html[ 'screenslide' ] .= '<div data-img="i/' . $sc['img'] . '-lo.jpg" class="any inverse"><div class="mo">' . $sc['header'] . '</div></div>'."\n";
  }
  }
  $html['screenslide'] .= '</div>';
  #deb( $html['screenslide'],1);
  return $html['screenslide'] ;
}


function getHtmlData( $db )
{ $html = getHTML2();
  $res = $db -> query('SELECT * FROM html' );
  while ( $row = $res -> fetchArray( ) )
  { $html[ $row[ 'name' ] ] = $row[ 'value' ];
  }
  return $html;
}


function actionHandler( $db )
{ $post = $_POST; #deb($_POST);
  if( isset( $post['ssid'] ) )
  { #if ( ltrim( rtrim( $post[ 'action' ] ) ) == 'DEL' ) { echo "DEL"; }
    #if ( ltrim( rtrim( $post[ 'action' ] ) ) == 'SAV' )
    {
      $ss   = $_POST;
      $ssid = $ss['ssid'];
      $SQL  = 'UPDATE slidescreen SET ';
      
      if ( isset ( $ss[ 'ssheader__' .$ssid ] ) ) { $SQL1 = $SQL . ' header       = \''. $db -> escapeString( $ss[ 'ssheader__' .$ssid ] ) . '\' WHERE id = \''.$ssid.'\' '; $db -> query( $SQL1 );  }
      if ( isset ( $ss[ 'sscontent_' .$ssid ] ) ) { $SQL2 = $SQL . ' content      = \''. $db -> escapeString( $ss[ 'sscontent_' .$ssid ] ) . '\' WHERE id = \''.$ssid.'\' '; $db -> query( $SQL2 );  }
      if ( isset ( $ss[ 'ssdate'            ] ) ) { $SQL3 = $SQL . ' best_before  = \''.                      $ss[ 'ssdate'            ]   . '\' WHERE id = \''.$ssid.'\' '; $db -> query( $SQL3 );  }
      if ( isset ( $ss[ 'ssspinner'         ] ) ) { $SQL4 = $SQL . ' img          = \''.                      $ss[ 'ssspinner'         ]   . '\' WHERE id = \''.$ssid.'\' '; $db -> query( $SQL4 );  }
      if ( isset ( $ss[ 'ssactive'          ] ) ) { $SQL5 = $SQL . ' active       = \''. 1                                                 . '\' WHERE id = \''.$ssid.'\' '; $db -> query( $SQL5 );  }
      else                                        { $SQL5 = $SQL . ' active       = \''. 0                                                 . '\' WHERE id = \''.$ssid.'\' '; $db -> query( $SQL5 );  }
    }
  }
  
  if( isset( $_POST['ntdel']) )
  { $id   =  $_POST['ntid'];
    $SQL  = 'DELETE FROM newsticker WHERE id = '.$id ;
    $db -> query( $SQL );
  }
  
  if( isset($_POST['nttext0'] ) AND  trim( $_POST['nttext0']) != '' )
  { $nttext = $_POST['nttext0'];
    $ntdate = $_POST['ntdate0'];
    $SQL    = 'INSERT INTO newsticker (text, best_before, active ) VALUES ("'.$nttext.'","'.$ntdate.'","1" )';
    $db -> query( $SQL );
  }
  
  if( isset( $_POST['ntid']) )
  {
    $nt   = $_POST;
    $ntNr = $nt['ntid'];
    $SQL  = 'UPDATE newsticker SET ';
    
    if ( isset ( $nt[ 'nttext'   .$ntNr ] ) ) { $SQL1 = $SQL . ' text         = "'. $nt[ 'nttext'.$ntNr    ]  .'" WHERE id = "'.$ntNr.'" '; $db -> query( $SQL1 );  }
    if ( isset ( $nt[ 'ntdate'   .$ntNr ] ) ) { $SQL2 = $SQL . ' best_before  = "'. $nt[ 'ntdate'.$ntNr    ]  .'" WHERE id = "'.$ntNr.'" '; $db -> query( $SQL2 );  }
    if ( isset ( $nt[ 'ntactive' .$ntNr ] ) ) { $SQL3 = $SQL . ' active       = "'. 1  .'" WHERE id = "'.$ntNr.'" '; $db -> query( $SQL3 );  }
    else                                      { $SQL3 = $SQL . ' active       = "'. 0  .'" WHERE id = "'.$ntNr.'" '; $db -> query( $SQL3 );  }
  
    if ( isset ( $nt[ 'ntdel'  ] ) ) { $SQL1 = $SQL . 'DELETE FROM newsticker WHERE id = "'.$ntNr.'" '; $db -> query( $SQL1 );  }
  }
}

function getNewstickerEditor($db)
{ $html = '';
  $newsticker  = getNewstickerData( $db, true );
  foreach ( $newsticker as $nt )
  {
    $chk = ''; if($nt['active']) {$chk = 'checked'; }
    $html .= '<div class="ntline" id="netline'   .$nt[ 'id' ]. '" >'."\n";
    $html .=  '<form action="editor.php" method="post" id = "ntform' .$nt[ 'id' ]. '">';
    $html .=  '<span> <input type="text"      class="nttext"   name="nttext'   .$nt[ 'id' ]. '" id="nttext'   .$nt[ 'id' ]. '" value="'.       $nt[ 'text'        ].'" ></span>'."\n";
    $html .=  '<span> <input type="date"      class="ntdate"   name="ntdate'   .$nt[ 'id' ]. '" id="ntdate'   .$nt[ 'id' ]. '" value="'.       $nt[ 'best_before' ].'" ></span> '."\n";
    $html .=  '<span> <input type="checkbox"  class="ntactive" name="ntactive' .$nt[ 'id' ]. '" id="ntactive' .$nt[ 'id' ]. '" value="active'. $nt[ 'id'          ].'" ' .$chk. ' ></span>'."\n";
    $html .=  '<span> <input type="hidden"    class="ntid"     name="ntid"                      id="ntid'     .$nt[ 'id' ]. '" value="'.       $nt[ 'id'          ].'" ></span>'."\n";
    $html .=  '<span> <input type="submit"    class="ntdel"    name="ntdel"                     id="ntdel'    .$nt[ 'id' ]. '" value=" DEL " ></span>'."\n";
    $html .=  '</form>'."\n";
    $html .=  '</div>'."\n";
  
    $html .=  '<script>';
    $html .=  "$( '#ntactive" .$nt[ 'id' ]. "' ).change( function()  { if( this.checked )  {  $('#netline" .$nt[ 'id' ]. "').css('background-color', 'red');  }  else{ $('#netline".$nt[ 'id' ]. "').css('background-color', 'blue'); }  $('#ntform" .$nt[ 'id' ]. "').submit(); });";
    $html .=  "$( '#nttext"   .$nt[ 'id' ]. "' ).change( function()  {  $('#ntform" .$nt[ 'id' ]. "').submit(); } );";
    $html .=  "$( '#ntdate"   .$nt[ 'id' ]. "' ).change( function()  {  $('#ntform" .$nt[ 'id' ]. "').submit(); } );";
  
    $html .=  '</script>';
  }
  $nt[ 'id' ] = 0;
  $datepreset =  date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+7, date("Y")));
  
  $html .= '<div class="ntline" id="netline'   .$nt[ 'id' ]. '" >'."\n";
  $html .=  '<form action="editor.php" method="post">';
  $html .=  '<span> <input type="text"      class="nttext"   name="nttext'   .$nt[ 'id' ]. '" id="nttext'   .$nt[ 'id' ]. '" value=" " ></span>'."\n";
  $html .=  '<span> <input type="date"      class="ntdate"   name="ntdate'   .$nt[ 'id' ]. '" id="ntdate'   .$nt[ 'id' ]. '" value="'. $datepreset.'" ></span> '."\n";
  $html .=  '<span> <input type="hidden"    class="ntupd"    name="ntupd'                . '" id="ntupd'    .$nt[ 'id' ]. '" value=" " ></span>'."\n";
  $html .=  '<span> <input type="submit"    class="ntsub"    name="ntsub'    .$nt[ 'id' ]. '" id="ntsub'    .$nt[ 'id' ]. '" value=" NEU " ></span>'."\n";
  $html .=  '</form> <br>'."\n";
  $html .=  '</div>'."\n";
  
  return $html;
}


function deb($val, $kill= false)
{
  echo "<pre>";
  print_r($val);
  echo "</pre>";
  if ($kill) die();
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
      <a href="Flyer_Studierendentagung_2023.html" class="c-circle-menu__link">
        <img src="img/house.svg" alt="">
      </a>
    </li>
    <li class="c-circle-menu__item" id="myButton2" >
      <a href="raum2.html" class="c-circle-menu__link">
        <img src="img/photo.svg" alt="">
      </a>
    </li>
    <li class="c-circle-menu__item" id="myButton3" >
      <a href="exkursion.html" class="c-circle-menu__link">
        <img src="img/pin.svg" alt="">
      </a>
    </li>
    <li class="c-circle-menu__item" id="myButton4" >
      <a href="klausurennoten.html" class="c-circle-menu__link">
        <img src="img/search.svg" alt="">
      </a>
    </li>
    <li class="c-circle-menu__item" id="myButton5" >
      <a href="raumfinder.html" class="c-circle-menu__link">
        <img src="img/tools.svg" alt="">
      </a>
    </li>
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
content: '<div class="tooltip">18. Hamburger Studierendentagung</div>',
  });
 tippy('#myButton2', { allowHTML: true,   maxWidth: 'none',   placement: 'left', offset: [0, 0],
	content: '<div  class="tooltip">Raumänderungen im Mai</div>',
  })
  tippy('#myButton3', { allowHTML: true,  maxWidth: 'none',    placement: 'left', offset: [0, 0],
	content: '<div  class="tooltip">Exkursion für BT Studierende</div>',
  });

  tippy('#myButton4', { allowHTML: true,  maxWidth: 'none',    placement: 'left', offset: [0, 0],
	content: '<div  class="tooltip">Klausurennoten</div>',
  });

  tippy('#myButton5', { allowHTML: true,  maxWidth: 'none',    placement: 'left', offset: [0, 0],
	content: '<div  class="tooltip">Raumfinder</div>',
  });
</script>
EOD;
  
  
  $html['editorhead'] = <<<EOD
<script src = "js/tinymce/tinymce.min.js"></script>
<script src = "js/jquery-1.10.2.min.js"></script>
<script src = "js/jquery-ui-1.13.2/jquery-ui.min.js"></script>
<link  href = "js/jquery-ui-1.13.2/jquery-ui.min.css" rel="stylesheet" >

<style>
  .ssline{  display: flex;   }
  .ssspinner { width:40px }
  .ssheader_, .sscontent {float:left;width:600px; border: solid 2px gray;}
  .ssspinner, .ssheader_, .sscontent {  height:400px;   margin: 10px;  background-size: cover; }
  .sscontent { overflow: auto;}
  .ssdate { font-size: 18px; }
  .ssdel  {  padding: 11px; }
  .ssactive  {height: 40px; position: relative; top: 13px ;width: 40px;}
</style>


<style>
  .wrap  { position: absolute;   top: 0px;  right: 2px;  width: 320px;  height: 300px;  padding: 0;  overflow: hidden; }
  .frame { width: 1280px; height: 800px; border: 0; -ms-transform: scale( 0.25 ); -moz-transform: scale( 0.25 ); -o-transform: scale( 0.25 );  -webkit-transform: scale(0.25);   transform: scale(0.25);-ms-transform-origin: 0 0;   -moz-transform-origin: 0 0;   -o-transform-origin: 0 0;   -webkit-transform-origin: 0 0;   transform-origin: 0 0;}
</style>

<style>
.ntline { background: #699B67; width: 1000px; padding: 5px; margin: 2px; }
.nttext, .ntdate, .ntactive  { height: 40px; width: 40px; margin: 5px; }
.ntactive  { position: relative; top: 13px }
.nttext  { width: 600px; }
.ntdate  { width: 150px; font-size: 18px; }
.ntdel, .ntsub, .sssav  {  padding: 11px; }
</style>

<script src="js/tinymce/tinymce.min.js"></script>
EOD;
 return $html;
}

#deb($screenData);
function getScreenSlideEditor($db)
{
  $screenData = getScreenData( $db );
  $html = '';
  foreach ( $screenData as $ss )
  {
    if( $ss[ 'active'] ) { $chk = 'checked'; } else { $chk = ''; }

    $html .= "\n" . '<form action="editor.php" method ="POST" id = "ssform'  .$ss[ 'id' ]. '"  >';
    $html .=  "\n" . '<div                           class = "ssline"> ';
    $html .=  "\n" . '<div                           class = "ssline2" > <div   name ="ssheader_"  id = "ssheader__'  .$ss[ 'id' ]. '" class = "ssheader_" >'        .$ss[ 'header'  ]. '</div></div> ';
    $html .=  "\n" . '<div                           class = "ssline2" > <input name ="ssspinner"  id = "ssspinner_'  .$ss[ 'id' ]. '" class = "ssspinner" value="'  .$ss[ 'img'     ]. ' ></div>          ';
    $html .=  "\n" . '<div                           class = "ssline2" > <div   name ="sscontent"  id = "sscontent_'  .$ss[ 'id' ]. '" class = "sscontent" >'        .$ss[ 'content' ]. '</div></div> ';
  
    $html .=  "\n" . '      <input type = "date"     class = "ssdate"    name ="ssdate"   id = "ssdate'    .$ss[ 'id' ]. '" value="'        .$ss[ 'best_before' ]. '" >';
    $html .=  "\n" . '<div> <input type = "checkbox" class = "ssactive"  name ="ssactive" id = "ssactive'  .$ss[ 'id' ]. '" value="active" ' .$chk. ' ></div>';
    $html .=  "\n" . '      <input type = "hidden"   class = "sshidden"  name ="ssid"       id = "ssid_"                      value="'       .$ss[ 'id'          ]. '" > ';
    $html .=  "\n" . '</form>';
  
    $html .=  "\n" . '<form action="editor.php" method ="POST">';
    $html .=  "\n" . '<div> <input type = "submit"   class = "ssdel"    name ="action"   id = "ssdel'     .$ss[ 'id' ]. '" value=" DEL " ></div></div>';
    $html .=  "\n" . '      <input type = "hidden"   class = "sshidden" name ="ssid"     id = "ssid_"                      value="'       .$ss[ 'id'          ]. '" > ';
    $html .=  "\n" . '</div></form>';
  
   
    
    $html .=  "\n".'<script>';
    $html .=  "\n".'$( "#ssspinner_'  .$ss[ 'id' ]. '" ).spinner({  spin: function( event, ui ){ $("#ssform' .$ss[ 'id' ]. '").submit(); },  spin: function( event, ui ) '."\n".' {if (  ui.value <= 0 ) {  $( this ).spinner( "value", 1 );return false; };'."\n".' myurl = \'url( "i/\'+ui.value+\'.jpg" )\'; '."\n".' $( "#ssheader__'  .$ss[ 'id' ]. '" ).css( "background-image" , myurl );} }); ';
    $html .=  "\n".'const hcss'  .$ss[ 'id' ]. ' = {selector: \'#ssheader__'  .$ss[ 'id' ]. '\', menubar: false, inline: true, toolbar: \'undo redo | bold italic underline\',  content_css: \'css/minicontent.css\',     setup: (editor) => { editor.on("focusOut", () => {  $("#ssform' .$ss[ 'id' ]. '").submit(); });} };';
    $html .=  "\n".'const ccss'  .$ss[ 'id' ]. ' = {selector: \'#sscontent_'  .$ss[ 'id' ]. '\', menubar: false, inline: true, toolbar: \'undo redo | bold italic underline\',                                            setup: (editor) => { editor.on("focusOut", () => {  $("#ssform' .$ss[ 'id' ]. '").submit(); });} };';
    $html .=  "\n".'tinymce.init( hcss' .$ss[ 'id' ]. ' );  ';
    $html .=  "\n".'tinymce.init( ccss' .$ss[ 'id' ]. ' );  ';
    $html .=  "\n".'$( "#ssheader__' .$ss[ 'id' ]. '" ).css( "background-image" , "url( \'i/'  .$ss[ 'img' ]. '.jpg\' )" );';
    $html .=  "\n".'$( "#ssspinner_' .$ss[ 'id' ]. '" ).spinner( "value", '.$ss[ 'img' ].' );';
    
   # $html .=  "\n".'</script>';
  
    $html .=  "$( '#ssactive" .$ss[ 'id' ]. "' ).change( function()
    { if( this.checked ) { $('#netline" .$ss[ 'id' ]. "').css('background-color', 'red'); }
    else{ $('#netline".$ss[ 'id' ]. "').css('background-color', 'blue'); }
    $('#ssform" .$ss[ 'id' ]. "').submit(); });";
  #  $html .=  "$( '#SSHEADER__"  .$ss[ 'id' ]. "' ).change( function()  {  $('#ssform" .$ss[ 'id' ]. "').submit(); } );";
    $html .=  "$( '#ssspinner_"  .$ss[ 'id' ]. "' ).change( function()  {  $('#ssform" .$ss[ 'id' ]. "').submit(); } );";
  #  $html .=  "$( '#sscontent_"  .$ss[ 'id' ]. "' ).change( function()  {  $('#ssform" .$ss[ 'id' ]. "').submit(); } );";
    $html .=  "$( '#ssdate"      .$ss[ 'id' ]. "' ).change( function()  {  $('#ssform" .$ss[ 'id' ]. "').submit(); } );";
    
    $html .=  '</script>';
  }

    $html .=  '<script>';
    $html .=  "function dateHandler(e){alert('1');}";
    $html .=  '</script>';
  
  return $html;
}

function getPreviewScreen()
{
  $html  =  "\n" . ' <div class="wrap">';
  $html .=  "\n" . '  <iframe class="frame" src="./index.php"></iframe>';
  $html .=  "\n" . '</div>';
  
  return $html;
  
}

?>