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
   'date' => $event->getStart()->format('Y-m-d'),
   'start' => $event->getStart()->format('H:i'),
   'description' => $event->getDescription(),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $diffPost = array_diff($data, $_POST);
   if ($diffPost) {
      $post = $_POST;
      $validator = new \App\calendar\EventValidator();
      $errors = $validator->validatesEvent($post, $event->getId() );
      if (empty($errors)) {
         $event->setName($post['name']);
         $event->setDescription($post['description']);
         $event->setStart(DateTime::createFromFormat('Y-m-d H:i', $post['date'] . ' ' . $post['start'])->format('Y-m-d H:i:s'));

         $events->update($event);

         $_SESSION['valid'] = "Votre rendez-vous a bien été modifié.";
         header('location: /Agenda/public');
         exit();
      }
   }
}
?>

<div class="section_create">
   <h1>Modifier rendez-vous du <?= (new DateTime($data['start']))->format('d-m-Y') ?></h1>
   <div class="form_create">
      <form action="" method="post">
         <input type="text" name="name" id="name" placeholder="Nom du rendez vous *" value="<?= htmlentities($data['name']); ?>" required>
         <?php if (isset($errors['name'])): ?>
            <p class="alert"><?= $errors['name'] ?> </p>
         <?php endif?>
         <input type="date" name="date" id="date" value="<?= htmlentities($data['date']); ?>" required>
         <?php if (isset($errors['date'])): ?>
            <p class="alert"><?= $errors['date'] ?> </p>
         <?php endif?>
         <input type="time" name="start" id="start" value="<?= htmlentities($data['start']); ?>">
         <?php if (isset($errors['start'])): ?>
            <p class="alert"><?= $errors['start'] ?> </p>
         <?php endif?>
         <textarea name="description" id="description" placeholder="desciption"><?=htmlentities($data['description']); ?></textarea>
         <button type="submit" class="btn">Modifier</button>
      </form>  
   </div>
   <p class="white"><a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn">Retour</a></p>
</div>