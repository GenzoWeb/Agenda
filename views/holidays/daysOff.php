<?php

use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$title = "Ajoutez jour de congés";
$day = "";
$data = [];

if ($params) {
   $day = $params['year'] . '-' . $params['month'] . '-' . $params['day'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $data = $_POST;
   $errors = [];
   $validator = new \App\calendar\EventValidator();
   $errors = $validator->validatesHoliday($_POST);

   if (empty($errors)) {
      $holiday = new \App\calendar\Holiday();
      $holiday->setName($data['name']);
      $holiday->setStart($data['start']);

      $pdo = Connection::getPDO();
      $holidays = new \App\calendar\Holidays($pdo);
      $holidays->create($holiday);

      if($data['end']){
         $dateStart = new DateTime($data['start']);
         $dateEnd = new DateTime($data['end']);
         $diff = $dateEnd->diff($dateStart)->format("%a");
   
         for ($i = 0; $i < $diff; $i++) {
            $testDay = (clone $dateStart)->modify('+1 day')->format("w");

            if($testDay > 0 && $testDay < 6 ) {
               $holiday->setName($data['name']);
               $holiday->setStart($dateStart->modify('+1 day')->format("Y-m-d"));
               $holidays->create($holiday);
            } else {
              $dateStart->modify('+1 day')->format("Y-m-d");
            }
         }
      }

      header('location: /Agenda/public?valider=congés');
      exit();
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
         <button type="submit" class="btn">Envoyer</button>
      </form>  
   </div>
</div>