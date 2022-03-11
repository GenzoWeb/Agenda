<?php

use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$title = "Modifier rendez-vous";
$pdo = Connection::getPDO();
$events = new \App\calendar\Events($pdo);
$event = $events->getEventById($params['id']);
$errors = [];
$data = [
   'name' => $event->getName(),
   'description' => $event->getDescription(),
   'start' => $event->getStart()->format('Y-m-d H:i')
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $data = $_POST;
   $validator = new \App\calendar\EventValidator();
   $errors = $validator->validates($data);
   if (empty($errors)) {
      $event->setName($data['name']);
      $event->setDescription($data['description']);
      $event->setStart(DateTime::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['start'])->format('Y-m-d H:i:s'));

      $events->update($event);

      header('location: /Agenda/public?valider=rendez-vous');
      exit();
   }
}
?>

<div class="section_create">
   <h1>Modifier rendez-vous du <?= (new DateTime($data['start']))->format('d-m-Y') ?></h1>
   <div class="form_create">
      <form action="" method="post">
         <input type="text" name="name" id="name" placeholder="Nom du rendez vous *" value="<?= htmlentities($data['name']); ?>" required>
         <?php if (isset($errors['name'])): ?>
            <p><?= $errors['name'] ?> </p>
         <?php endif?>
         <input type="date" name="date" id="date" value="<?= htmlentities((new DateTime($data['start']))->format('Y-m-d')); ?>" required>
         <?php if (isset($errors['date'])): ?>
            <p><?= $errors['date'] ?> </p>
         <?php endif?>
         <input type="time" name="start" id="start" value="<?= htmlentities((new DateTime($data['start']))->format('H:i')); ?>">
         <?php if (isset($errors['start'])): ?>
            <p><?= $errors['start'] ?> </p>
         <?php endif?>
         <textarea name="description" id="description" placeholder="desciption"><?=htmlentities($data['description']); ?></textarea>
         <button type="submit" class="btn">Modifier</button>
      </form>  
   </div>
   <p class="white"><a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn">Retour</a></p>
</div>