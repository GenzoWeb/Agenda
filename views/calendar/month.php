<?php 
$title = "Calendrier mensuel";
$month = (int)$params['month'];
$year = (int)$params['year'];
$calendarsMonth = new \Views\DisplayCalendar(1, $month, $year);
?>
<div class="section_month">
   <?php $calendarsMonth->displayCalendarHtml(); ?>
</div>