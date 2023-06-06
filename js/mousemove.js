var timeout = 100;
var old_x = 0;  var x = 0;
var old_y = 0;  var y = 0;

function setTimeOutTimer()
{

}

function startTimeOutTimer()
{  
  document.addEventListener('DOMContentLoaded', function () 
  {
  document.getElementById('buehne').addEventListener('mousemove', holeKoordinaten);
  
  function holeKoordinaten(e)  
  {
  x = e.clientX;
  y = e.clientY;
  document.getElementById('x').innerHTML = x;
  document.getElementById('y').innerHTML = y;
  }
});	
	
  setInterval(getMousePosi, timeout*1000);
}

function stopTimeOutTimer()
{  
  clearInterval(getMousePosi);
}

function getMousePosi()
{ if( old_x == x )
  {  location.href = 'index.php';
  }
  old_x = x;
}
