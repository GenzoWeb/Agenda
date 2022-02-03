<div class="section_month">
   <?php 
   $title = "Calendrier mensuel";
   $month = (int)$params['month'];
   $year = (int)$params['year'];
   $calendarsYear = new \Views\DisplayCalendar(1, $month, $year);
   $calendarsYear->displayCalendarHtml();
   ?>
</div>