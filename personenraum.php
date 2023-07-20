<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/jquery.dm-uploader.min.css" rel="stylesheet">
    <link href="css/upload-styles.css" rel="stylesheet">
  <script src = "js/jquery-1.10.2.min.js"></script>
  <script src = "js/jquery-ui-1.13.2/jquery-ui.min.js"></script>
  <link  href = "js/jquery-ui-1.13.2/jquery-ui.min.css" rel="stylesheet" >
  <script src="js/mousemove.js"></script>
<link rel="stylesheet"  type="text/css" href="css/circle-menu.min.css">
<style>
 h1, h2, h3, tr {  text-align:center;padding: 0px;  -moz-user-select:none; cursor:pointer; }
 .ui-accordion-header:hover{ color:#000000; background-color: #DDDDDD; }
 .ui-accordion-header:active{ color:#ffffff; background-color: #003399; }

#hover1, #hover2
{
 width:100%;
 height:60px;
 border:1px solid #cc0000;   
 background-color:#ACACAC;
 text-align: center;
 top:-20px;
}


#container
{
  width:100%;
  height:960px;
  overflow-y:auto;   
  border:1px solid #000;
  overflow: hidden;
}

.udnav
{
  width:75px;
  height:60px;
}


</style>

<script>
function toggleKl( kid) 
{ var x = document.getElementById(kid);
  if (x.style.display == "block"){
      x.style.display = "none";
   } else {
      x.style.display = "block";
   }
}

</script>
</head>

<body id="buehne" onload="startTimeOutTimer();" style="padding-bottom:0px; padding-top: 0px;">
<div id="hover1"> <img class="udnav" src="img/up1.png"/> </div>
<div id="container">
<h1 style="background-color: black; color:white; padding: 20px; ">RAUMFINDER LS</h1>
<?php
 $tabelle = 'users';
 $i = 0;
 $db    =  new SQLite3('../db/personenraum.db' );
 $SQL = 'SELECT * FROM "' . $tabelle . '" ';
 
 $ret   = $db -> query( $SQL );

 while ( $row = $ret -> fetchArray( ) )
 { $row[ 'anrede' ] = $row[ 'name' ].', '. $row[ 'vorname' ].' '.$row[ 'titel' ] ;
  
  if($row[ 'vorname' ] != '')
  { $personen[ $row[ 'name' ].$row[ 'vorname' ] ] = $row;
  }
  else
  { $orga[ $row[ 'name' ] ] = $row;
  }
 }

  ksort($personen);
  ksort($orga);
 
  echo '<div id="klaus">'; 
  foreach($personen as $p)
  {
     $i++;
     $id = 'klaus'.$i;
	 echo '<a onclick="toggleKl(\''.$id.'\')"><h1>' .$p['anrede']. ' ['. $i.'] </h1></a>';
	 echo '<div id = "'.$id.'" style="border: solid black 2px; display: none;">';
     if ( $p[ 'raum'    ]  !='' ) { echo "<h2> RAUM    : " . $p[ 'raum'    ] . '</h2>'; }
     if ( $p[ 'aufzug'  ]  !='' ) { echo "<h2> AUFZUG  : " . $p[ 'aufzug'  ] . '</h2>'; }
     if ( $p[ 'telefon' ]  !='' ) { echo "<h2> TELEFON : (040 428 75) " . $p[ 'telefon' ] . '</h2>'; }
     if ( $p[ 'email'   ]  !='' ) { echo "<h2> EMAIL   : " . $p[ 'email'   ] . '</h2>'; }
     if ( $p[ 'bereich' ]  !='' ) { echo "<h2> BEREICH : " . $p[ 'bereich' ] . '</h2>'; }
     if ( $p[ 'einheit' ]  !='' ) { echo "<h2> EINHEIT : " . $p[ 'einheit' ] . '</h2>'; }
     echo "</div>";
  }

foreach($orga as $o)
{
  $i++;
  $id = 'klaus'.$i;
  echo '<a onclick="toggleKl(\''.$id.'\')"><h1>' .$o['anrede']. ' ['. $i.'] </h1></a>';
  echo '<div id = "'.$id.'" style="border: solid black 2px; display: none;">';
  if ( $o[ 'raum'    ]  !='' ) { echo "<h2> RAUM    : " . $o[ 'raum'    ] . '</h2>'; }
  if ( $o[ 'aufzug'  ]  !='' ) { echo "<h2> AUFZUG  : " . $o[ 'aufzug'  ] . '</h2>'; }
  if ( $o[ 'telefon' ]  !='' ) { echo "<h2> TELEFON : (040 428 75) " . $o[ 'telefon' ] . '</h2>'; }
  if ( $o[ 'email'   ]  !='' ) { echo "<h2> EMAIL   : " . $o[ 'email'   ] . '</h2>'; }
  if ( $o[ 'bereich' ]  !='' ) { echo "<h2> BEREICH : " . $o[ 'bereich' ] . '</h2>'; }
  if ( $o[ 'einheit' ]  !='' ) { echo "<h2> EINHEIT : " . $o[ 'einheit' ] . '</h2>'; }
  echo "</div>";
}
 
 echo '</div>'; 
 echo '</div>'; 

?>
 <div id="hover2">  <img class="udnav" src="img/down1.png"/> </div>
<script>

var amount = '';

function scroll() {
	      console.log(amount);
    $('#container').animate({ 
        scrollTop: amount
    }, 100, 'linear',function() {
        if (amount != '') {
            scroll();
        }
    });
}
$('#hover1').hover(function() {
    amount = '+=100';
    scroll();
}, function() {
    amount = '';
});
$('#hover2').hover(function() {
    amount = '-=100';
    scroll();
}, function() {
    amount = '';
});



 	

$( "#klaus" ).accordion({
 heightStyle: "content",
   collapsible: true,
 active: false ,
});
</script>

<nav class="c-circle-menu js-menu">
  <button class="c-circle-menu__toggle is-active js-menu-toggle">
    <a href="index.php"><span>Toggle</span></a>
  </button>
</nav>

  </body>
</html>
