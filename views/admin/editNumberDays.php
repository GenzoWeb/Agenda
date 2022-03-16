<?php
use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$title = "Modifier Nombre de jours";
$daysHolidays = $holidays->getCounter();
$yearHoliday=[];

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $data = $_POST;
   // $validator = new \App\calendar\EventValidator();
   // $errors = $validator->validates($data);

   if (empty($errors)) {
      if (isset($data['valid'])) {         
         foreach($daysHolidays as $d) {
            if ($d['name'] === "CP" || $d['name'] === "Enfant malade") {
               $yearHoliday[$d['name']] = $d['year'];
            }
         }

         $holidayYear = date($yearHoliday['CP']);
         $holidayYearChildren = date($yearHoliday['Enfant malade']);
         $date = new DateTimeImmutable(date('d-m-Y'));
         $year = $date->format('Y');
         $dateChange = new DateTimeImmutable(date('01-06-' . $holidayYear));
         $dateChangeCurrent = new DateTimeImmutable(date('01-06-' . $year));

         if ($date >= $dateChange && $date >= $dateChangeCurrent) {
            $year = $date->modify("+1year")->format('Y');
            foreach ($daysHolidays as $total){
               if ($total['name'] !== "Enfant malade") {
                  $holidays->updateYears($year, $total['name']);
               }
            }
         } else {
            if ($holidayYear !== $year && $date < $dateChangeCurrent) {
               foreach ($daysHolidays as $total){
                  if ($total['name'] !== "Enfant malade") {
                     $holidays->updateYears($date->format('Y'), $total['name']);
                  }
               }
            }
         }
         
         if ($holidayYearChildren !== $year) {
            $holidays->updateYears($date->format('Y'), "Enfant malade");
         }
      }
   
      if (isset($data['validDay'])) {
         foreach ($data as $key => $value) {
            if ($key !== "validDay"){
               $name= str_replace("_", " ", $key);
               $name= str_replace("_", " ", $key);

               $holidays->updateDaysTotal($value, $name);
            }
         }
      }
   }

   header('location: /Agenda/public/gestion-modif-compteur');
   exit();
}
?>

<div class="section_admin_edit_Number_Days">
   <h1>PAGE edit number days</h1>   
   <form action="" method="post">
      <table> 
         <?php 
         foreach($daysHolidays as $total): ?>
         <tr>
            <td> <?= $total['name'] ?></td>
            <td>
               <input type="text" name="<?= $total['name'] ?>" id="<?= $total['name'] ?>" value="<?= htmlentities($total['numbers']); ?>" required>
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
      <button type="submit" class="btn" onclick="return confirm('Voulez vous vraiment faire la mise à jour ?')">Mise à jour</button>
   </form>
</div>