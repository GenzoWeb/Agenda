<?php

use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$title = "Ajoutez rendez-vous";
$day = "";
$data = [];

if ($params) {
   $day = $params['year'] . '-' . $params['month'] . '-' . $params['day'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $data = $_POST;
   $errors = [];
   $validator = new \App\calendar\EventValidator();
   $errors = $validator->validates($_POST);

   if (empty($errors)) {
      $event = new \App\calendar\Event();
      $event->setName($data['name']);
      $event->setDescription($data['description']);
      $event->setStart(DateTime::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['start'])->format('Y-m-d H:i:s'));

      $pdo = Connection::getPDO();
      $events = new \App\calendar\Events($pdo);
      $events->create($event);

      header('location: /Agenda/public?valider=rendez-vous');
      exit();
   }
}
?>

<div class="section_create">
   <h1>Ajouter rendez-vous</h1>
   <div class="form_create">
      <form action="" method="post">
         <input type="text" name="name" id="name" placeholder="Nom du rendez vous *" value="<?= isset($data['name']) ? htmlentities($data['name']) : ''; ?>" required>
         <?php if (isset($errors['name'])): ?>
            <p><?= $errors['name'] ?> </p>
         <?php endif?>
         <input type="date" name="date" id="date" value="<?= isset($data['date']) ? htmlentities($data['date']) : $day; ?>" required>
         <?php if (isset($errors['date'])): ?>
            <p><?= $errors['date'] ?> </p>
         <?php endif?>
         <input type="time" name="start" id="start" value="<?= isset($data['start']) ? htmlentities($data['start']) : ''; ?>">
         <?php if (isset($errors['start'])): ?>
            <p><?= $errors['start'] ?> </p>
         <?php endif?>
         <textarea name="description" id="description" placeholder="desciption"><?= isset($data['description']) ? htmlentities($data['description']) : ''; ?></textarea>
         <button type="submit" class="btn">Envoyer</button>
      </form>  
   </div>
</div>