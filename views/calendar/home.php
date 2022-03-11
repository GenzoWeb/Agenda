<div class="section_home">
   <?php
   if (isset($_GET['valider'])): ?>
   <div class="success">
      <p>Votre <?= $_GET['valider'] ?> a bien été enregistré.</p>
   </div>
   <?php endif;

   if (isset($_GET['supprimer'])): ?>
   <div class="success">
      <p>Votre <?= $_GET['supprimer'] ?> a bien été supprimé.</p>
   </div>
   <?php endif; ?>
   <div class="section_home_content">
      <?php
      $calendarsHome = new \Views\DisplayCalendar(2);
      $calendarsHome->displayCalendarHtml();
      ?>
   </div>
</div>

