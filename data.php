<?php

$html['screenslide'] = <<<EOD
<!-- Fotorama -->
<div class="fotorama"
  data-width="100%"
  data-max-width="100%"
  data-loop="true"
  data-transition="crossfade"
  data-nav="false"
  data-shuffle="true"
  data-autoplay="5000"
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

?>