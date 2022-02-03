<div class="section_home">
   <?php
   $calendarsHome = new \Views\DisplayCalendar(2);
   // $calendarsHome = new \Views\DisplayCalendar(2, $_GET['month'] ?? null, $_GET['year'] ?? null);
   $calendarsHome->displayCalendarHtml();
   ?>
</div>