<?php
use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$title = "Supprimer jours";
$date = new DateTimeImmutable(date('d-m-Y'));
$year = $date->format('Y');
$dateChangeCurrent = new DateTimeImmutable(date('01-06-' . $year));
$errors = [];

if ($date < $dateChangeCurrent) {
   $start = new DateTimeImmutable($year - 1 ."-06-01");
   $end = new DateTimeImmutable($year . "-05-31");
} else {
   $start = new DateTimeImmutable($year . "-06-01");
   $end = new DateTimeImmutable($year + 1 . "-05-31");  
}

$holidaysOfYear = $holidays->getHolidays($start, $end);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $data = $_POST;
   if ($data) {
      foreach($data['delete'] as $id){
         $holidays->delete($id);
         header('location: /Agenda/public/gestion-suppr');
      }
   }
}
?>

<div class="section_admin_delete">
   <?php if($holidaysOfYear) : ?>
   <h1>Supprimer des jours</h1>
   <form action="" method="post">
      <table class="table_list_delete">
         <thead>
            <tr>
               <th>Nom</th>
               <th>Date</th>
               <th>Suppr</th>
            </tr>
         </thead>
         <tbody>
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
         </tbody>
      </table>
      <button type="submit" class="btn" onclick="return confirm('Voulez vous vraiment supprimer ?')">Supprimer</button>
   </form>
   <?php else :?>
   <p>Aucun jour n'a été posé.</p>
   <?php endif; ?>
</div>

