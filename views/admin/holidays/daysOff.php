<?php

use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$title = "Ajouter jour de congés";
$day = "";

if ($params) {
   $day = $params['year'] . '-' . $params['month'] . '-' . $params['day'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $data = $_POST;
   $errors = [];
   $validator = new \App\calendar\EventValidator();
   $testDate = $validator->validatesHoliday($data);

   if (isset($testDate['valid'])) {
      $pdo = Connection::getPDO();
      $holiday = new \App\calendar\Holiday();
      $holidays = new \App\calendar\Holidays($pdo);

      if ($data['end']){
         foreach($testDate['valid'] as $dayValid) {
            $holiday->setName($data['name']);
            $holiday->setStart((new dateTime($dayValid))->format("Y-m-d"));
            if (isset($data['semi'])) {
               $holiday->setSemi(0.5);
            } else {
               $holiday->setSemi("1");
            }
            $holidays->create($holiday);
         }
      } else {
         $holiday->setName($data['name']);
         $holiday->setStart($data['start']);
         if(isset($data['semi'])) {
            $holiday->setSemi("0.5");
         } else {
            $holiday->setSemi("1");
         }
         $holidays->create($holiday);
      }

      $_SESSION['valid'] = "Votre repos a bien été enregistré.";
      header('location: /Agenda/public');
      exit();
   } else {
      $errors = $testDate;
   }
}
?>

<div class="section_create">
   <h1>Ajouter congés</h1>
   <div class="form_create form_holidays">
      <form action="" method="post">
         <select name="name" id="name" placeholder="Nom du congés *" required>
            <option value="">Choisir un type de congés</option>
            <option value="RTT S">RTT S</option>
            <option value="RTT E">RTT E</option>
            <option value="CP">CP</option>
            <option value="CA">CA</option>
            <option value="Enfant malade">Enfant malade</option>
         </select>
         <input type="date" name="start" id="start" value="<?= $day ?>" required>
         <input type="date" name="end" id="end">
         <?php if (isset($errors['name'])): ?>
            <p><?= $errors['name'] ?> </p>
         <?php endif?>
         <?php if (isset($errors['start'])): ?>
            <p><?= $errors['start'] ?> </p>
         <?php endif?>
         <?php 
         if (isset($errors['end'])) :
            if (is_countable($errors['end'])) {
               foreach($errors['end'] as $errors) : ?>
               <p><?= $errors ?> </p>
               <?php endforeach; 
            } else { ?>
            <p><?= $errors['end'] ?> </p>
         <?php } endif; ?>
         <p>
           Demi-journée : <input type="checkbox" id="semi" name="semi">
         </p>
         <button type="submit" class="btn">Envoyer</button>
      </form>  
   </div>
</div>