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
<h1 style="background-color: black; color:white; padding: 20px; ">Bewertungen SS 2023 </h1>
<?php
 $semester= '23S';
 $i = 0;
 $db    =  new SQLite3('../db/klausurnotenS.db' );
 $SQL = 'SELECT * FROM "' . $semester . '" ';	
 
 $ret   = $db -> query( $SQL );

 while ( $row = $ret -> fetchArray( ) )
 { $klausuren[ $row[ 'Prüfende(r)' ] ][  $row[ 'Prüfung' ]  ][  $row[ 'Matnr3' ]  ] = $row;
 }

 foreach ($klausuren as $kk => $kv)
  { $lecturer = explode('.',$kk  );
	
	if (isset( $lecturer[ 1 ] ) )         { $sname = trim( $lecturer[ 1 ] ). ' '. trim( $lecturer[ 0 ] ).'.' ; }
    else if( trim($lecturer[ 0 ]) != '' ) { $sname = trim( $lecturer[ 0 ] ) ;}
  
    $lec[ $sname ][ 'sname' ] = $sname;
    $lec[ $sname ][ 'oname' ] = $kk;
  }
  ksort($lec);
  echo '<div id="klaus">'; 
  foreach($lec as $l)
  { #print_r($klausuren); die();
     $i++;
     $id = 'klaus'.$i;
	 echo '<a onclick="toggleKl(\''.$id.'\')"><h1>' .$l['sname']. ' ['. $i.'] </h1></a>'; 
	 echo '<div id = "'.$id.'" style="border: solid black 2px; display: none;">';
    # echo '<h1>' .$l['sname']. ' ['. $i.'] </h1>'; 
	# echo '<div id = "'.$id.'" style="border: solid 4px blue;">';
		
	
	 ksort ($klausuren[ $l['oname']]);
	 
	 foreach ($klausuren[ $l['oname']] as $pk => $prüfung)
	 { ksort($prüfung);
	 
	   echo "<a><h2>".$pk.'</h2></a>'; 
		  
       $kl = "<table>";
	   foreach ($prüfung as  $p ) { $kl .= '<tr><td style="width:48%">&nbsp;</td><td>'.$p['Matnr3'].'</td><td style="width:50px">&nbsp;</td><td>'.$p['Bewertung1Dez'].'</td><td style="width:48%">&nbsp;</td></tr> ';  }
       $kl .=  "</table>";	
	   echo $kl;
	 }

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
