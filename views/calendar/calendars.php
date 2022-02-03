<div class="section_year">
   <?php 
   $title = "Calendriers annuels";
   $year = (int)$params['year'];
   $calendarsYear = new \Views\DisplayCalendar(12, 1, $year);
   $calendarsYear->displayCalendarHtml();
   ?>
</div>