<?php

use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$title = "Modifier congés";
$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$holiday = $holidays->getHolidayById($params['id']);
$errors = [];
$data = [
   'name' => $holiday->getName(),
   'start' => $holiday->getStart()->format('Y-m-d')
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $data = $_POST;
   $validator = new \App\calendar\EventValidator();
   $errors = $validator->validatesHoliday($data);
   if (empty($errors)) {
      $holiday->setName($data['name']);
      $holiday->setStart($data['start']);

      $holidays->getHolidayByDate(new DateTimeImmutable($data['start']));
      $holidays->update($holiday);

      header('location: /Agenda/public?valider=congés');
      exit();
   }
}
?>

<div class="section_create">
   <h1>Modifier congés du <?= (new DateTime($data['start']))->format('d-m-Y') ?></h1>
   <div class="form_create form_holidays">
      <form action="" method="post">
         <select name="name" id="name" placeholder="Nom du congés *" required>
            <option value="">Choisir un type de congés</option>
            <option value="RTT S">RTT S</option>
            <option value="RTT E">RTT E</option>
            <option value="CP">CP</option>
            <option value="CA">CA</option>
            <option value="Enfant malade">Enfant malade</option>
            <option value="Autre">Autre</option>
         </select>
         <?php if (isset($errors['name'])): ?>
            <p><?= $errors['name'] ?> </p>
         <?php endif?>
         <input type="date" name="start" id="start" value="<?= htmlentities((new DateTime($data['start']))->format('Y-m-d')); ?>" required>
         <?php if (isset($errors['start'])): ?>
            <p><?= $errors['start'] ?> </p>
         <?php endif?>
         <button type="submit" class="btn">Modifier</button>
      </form>  
   </div>
   <p class="white"><a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn" id="test">Retour</a></p>
</div>