<?php 
$title = "Calendriers annuels";
$year = (int)$params['year'];
$calendarsYear = new \Views\DisplayCalendar(12, 1, $year);
?>

<div class="section_year">
   <?php $calendarsYear->displayCalendarHtml(); ?>
</div>