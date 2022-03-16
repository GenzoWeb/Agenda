<?php
use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$title = "Supprimez jours";

$date = new DateTimeImmutable(date('d-m-Y'));
$year = $date->format('Y');
$dateChangeCurrent = new DateTimeImmutable(date('01-06-' . $year));

if ($date < $dateChangeCurrent) {
   $start = new DateTimeImmutable($year - 1 ."-06-01");
   $end = new DateTimeImmutable($year . "-05-31");
} else {
   $start = new DateTimeImmutable($year . "-06-01");
   $end = new DateTimeImmutable($year + 1 . "-05-31");  
}

$holidaysOfYear = $holidays->getHolidays($start, $end);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $data = $_POST;
   foreach($data['delete'] as $id){
      $holidays->delete($id);
      header('location: /Agenda/public/gestion-suppr');
   }
}
?>

<div class="section_admin_delete">
   <h1>Supprimez des jours</h1>
   <form action="" method="post">
      <table>
         <tr>
            <td>Nom</td>
            <td>Date</td>
            <td>Suppr</td>
         </tr>
         <?php 
         foreach ($holidaysOfYear as $holiday): ?>
            <tr>
               <td><?= $holiday['name']; ?></td>
               <td><?= (new dateTime($holiday['start']))->format('d-m-Y'); ?></td>
               <td>
                  <input type="checkbox" id="id" name="delete[]" value="<?= $holiday['id'] ?>">
               </td>
            </tr>
         <?php endforeach ?>
      </table>
      <button type="submit" class="btn" onclick="return confirm('Voulez vous vraiment supprimer ?')">Supprimer</button>
   </form>

</div>