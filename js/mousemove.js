var timeout = 120;
var old_x = 0;
var old_y = 0;
var currentMousePos = { x: -1, y: -1 };

function setTimeOutTimer()
{

}

function startTimeOutTimer()
{ jQuery(function($)
  {  $(document).mousemove(function(event)
     { currentMousePos.x = event.pageX;
       currentMousePos.y = event.pageY;
     });

      console.log(old_x);
      console.log(currentMousePos.x);
      setInterval(getMousePosi, timeout*500);
  });
}

function getMousePosi()
{ console.log(old_x);
  console.log(currentMousePos.x);
  if( old_x == currentMousePos.x )
  { location.href = 'index.php';
  }
  old_x = currentMousePos.x;
}
