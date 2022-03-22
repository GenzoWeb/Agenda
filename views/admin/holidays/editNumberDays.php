<?php
use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$title = "Modifier Nombre de jours";
$daysHolidays = $holidays->getCounter();
$diffDays = $holidays->getNumberDaysSet();
$yearHoliday=[];
$start = $daysHolidays[0]['year'] - 1;
$end = $daysHolidays[0]['year'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $data = $_POST;

   if (isset($data['valid'])) {   
      $holidays->yearCalculate($daysHolidays);

      if (!isset($data['validDay'])){
         header('location: /Agenda/public/gestion-modif-compteur');
         exit();
      }
   }
   
   if (isset($data['validDay'])) {
      $validator = new \App\calendar\EventValidator();
      $errors = $validator->validatesDays($data);

      if (empty($errors)) {
         foreach ($data as $key => $value) {
            if ($key !== "validDay"){
               $name = str_replace("_", " ", $key);
               $holidays->updateDaysTotal($value, $name);
            }
         }
         header('location: /Agenda/public/gestion-modif-compteur');
         exit();
      } 
   }
}
?>

<div class="section_admin_edit_Number_Days">
   <h1>Gestion du compteur de jours</h1>
   <div class="admin_adit_numbers">
      <div class="counter">
         <form action="" method="post">
            <table>
               <caption>
                  Modification des jours ( <?= $start . " / " . $end ?> )
               </caption>
               <?php 
               foreach($daysHolidays as $daysH): ?>
               <tr>
                  <td> <?= $daysH['name'] ?></td>
                  <td>
                     <input type="text" name="<?= $daysH['name'] ?>" id="<?= $daysH['name'] ?>" value="<?= htmlentities($daysH['numbers']); ?>" required>
                  </td>
               </tr>
               <?php endforeach ?> 
            </table>
            <p>
               Mise à jour du nombre des jours : <input type="checkbox" id="validDay" name="validDay">
            </p>
            <p>
               Mise à jour année en cours : <input type="checkbox" id="valid" name="valid">
            </p>
            <?php if (isset($errors)): 
            foreach($errors as $er): ?>
            <p class="alert"><?= $er; ?> </p>
            <?php endforeach; endif; ?>
            <button type="submit" class="btn" onclick="return confirm('Voulez vous vraiment faire la mise à jour ?')">Mise à jour</button>
         </form>
      </div>
      <div>
         <table class="table_rest_days">
            <caption>Nombre de jours restant</caption>
            <?php foreach($diffDays as $key => $value): ?>
               <tr>
                  <td> <?= $key ?></td>
                  <td class="rest_days"> <?= $value ?></td>
               </tr>
            <?php endforeach ?>
         </table>
      </div>
   </div>
</div>