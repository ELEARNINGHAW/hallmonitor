<style>
.wrap
{ position: absolute;
  top: 0px;
  right: 2px;
  width: 320px;
  height: 300px;
  padding: 0;
  overflow: hidden;
}
.frame {
    width: 1280px;
    height: 800px;
    border: 0;
    -ms-transform: scale(0.25);
    -moz-transform: scale(0.25);
    -o-transform: scale(0.25);
    -webkit-transform: scale(0.25);
    transform: scale(0.25);
    
    -ms-transform-origin: 0 0;
    -moz-transform-origin: 0 0;
    -o-transform-origin: 0 0;
    -webkit-transform-origin: 0 0;
    transform-origin: 0 0;
}
</style>

<script src="js/tinymce/tinymce.min.js"></script>

<?php

include('inc/functions.php');
include('data.php');
$cNr = 0;
#if (isset($_GET['cNr'])) $cNr = $_GET['cNr'];

$db = new SQLite3('db/hallmonitor.db');

$today = strtotime(date("Y-m-d"));

$screen = getScreen( $db );

#deb($screen,1);
$html = getHtml( $db, $html );

echo($html['navihead']);

echo '
<script>
    $( function() {
        $( "#datepicker" ).datepicker();
    } );
</script>
';

$newsticker  = getNewsticker( $db, true );
$screenslide = getScreenslide( $screen, $today, $html[ 'screenslide' ] );

foreach ( $newsticker as $nt )
{
  $chk = ''; if($nt['active']) {$chk = 'checked'; }
  
  echo '<span> <input type="text"      id="nttext'   .$nt[ 'id' ]. '" value="'.       $nt[ 'text'        ].'" ></span> ';
  echo '<span> <input type="date"      id="ntdate'   .$nt[ 'id' ]. '" value="'.       $nt[ 'best_before' ].'" ></span> ';
  echo '<span> <input type="checkbox"  id="ntactive' .$nt[ 'id' ]. '" value="active'. $nt[ 'id'          ].'" ' .$chk. ' ></span>';
  echo '<span> <input type="button"    id="ntdel'    .$nt[ 'id' ]. '" value="'.       $nt[ 'id'         ].'" ></span> <br>';
}


foreach ( $screen as $ss )
{
  $chk = ''; if($ss['active']) {$chk = 'checked'; }
  
  echo '<span> <input type="text"      id="ssheader'  .$ss[ 'id' ]. '" value="'.       $ss[ 'header'      ].'" ></span> ';
  echo '<span> <textarea               id="sscontent' .$ss[ 'id' ]. '" value="'.       $ss[ 'content'      ].'" >'.       $ss[ 'content'      ].'</textarea></span> ';
  echo '<span> <input type="date"      id="ssdate'    .$ss[ 'id' ]. '" value="'.       $ss[ 'best_before' ].'" ></span> ';
  echo '<span> <input type="checkbox"  id="ssactive'  .$ss[ 'id' ]. '" value="active'. $ss[ 'id'          ].'" ' .$chk. ' ></span>';
  echo '<span> <input type="button"    id="ssdel'     .$ss[ 'id' ]. '" value="'.       $ss[ 'id'          ].'" ></span> <br>';
  
  
  
  echo "<script>
tinymce.init({
  selector: 'textarea#sscontent" .$ss[ 'id' ]. "',
   height: 200,
  menubar: false,
 
  toolbar: 'undo redo | formatselect | ' +
'bold italic backcolor | alignleft aligncenter ' +
'alignright alignjustify | bullist numlist outdent indent | ' +
'removeformat | help' +
'save table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
  plugins: 'table save'
});

</script>";


}


#deb( $newsticker,1 );
#print_r($_GET);
#print_r($_POST);
?>
<div class="wrap">
    <iframe class="frame" src="./index.php"></iframe>
</div>
