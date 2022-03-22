<?php 
$calendarsHome = new \Views\DisplayCalendar(2); 
// $schoolHolidays = new \App\API\SchoolHolidays();

if (isset($_SESSION['delete'])) {
   $session = $_SESSION['delete'];
}

if (isset($_SESSION['valid'])) {
   $session = $_SESSION['valid'];
}
?>

<div class="section_home">
   <?php if (isset($session)) : ?>
   <div class="success">
      <p><?= $session ?></p>
   </div>
   <?php endif; ?>

   <div class="section_home_content">
      <?php 
      $calendarsHome->displayCalendarHtml();
      ?>

   </div>
</div>