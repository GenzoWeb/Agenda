<?php

use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$title = "Modifier congés";
$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$holiday = $holidays->getHolidayById($params['id']);
$nameHolidays = $holidays->getNameHolidays();
$errors = [];
$data = [
   'name' => $holiday->getName(),
   'start' => $holiday->getStart()->format('Y-m-d'),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   $diffPost = array_diff($_POST, $data);
   if ($diffPost) {
      $post = $_POST;
      $validator = new \App\calendar\EventValidator();

      if(!isset($diffPost['start'])) {
         $testDate = $validator->validatesHoliday($diffPost);
      } else {
         $testDate = $validator->validatesHoliday($post);
      }

      if (isset($testDate['valid'])) {
         $holiday->setName($post['name']);
         $holiday->setStart($post['start']);
         $holiday->setSemi($holiday->getSemi());
         
         $holidays->update($holiday);

         $_SESSION['valid'] = "Votre congés a bien été modifié.";
         header('location: /Agenda/public');
         exit();
      } else {
         $errors = $testDate;
      }
   }
}
?>

<div class="section_create">
   <h1>Modifier congés du <?= (new DateTime($data['start']))->format('d-m-Y') ?></h1>
   <div class="form_create form_holidays">
      <form action="" method="post">
         <select name="name" id="name" placeholder="Nom du congés *" required>
         <?php foreach($nameHolidays as $nameH) :
            if ($nameH === $data['name']) : ?>
            <option value="<?= $nameH ?>" selected><?= $nameH ?></option>
            <?php else : ?>
               <option value="<?= $nameH ?>"><?= $nameH ?></option>
            <?php endif;
         endforeach; ?>
         </select>
         <?php if (isset($errors['name'])): ?>
            <p><?= $errors['name'] ?> </p>
         <?php endif?>
         <input type="date" name="start" id="start" value="<?= htmlentities((new DateTime($data['start']))->format('Y-m-d')); ?>" required>
         <?php if (isset($errors['start'])): ?>
            <p><?= $errors['start'] ?> </p>
         <?php endif?>
         <?php if (isset($_SESSION['exist'])): ?>
            <p><?= $_SESSION['exist']?></p>
         <?php endif?>
         <button type="submit" class="btn">Modifier</button>
      </form>  
   </div>
   <p class="white"><a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn" id="test">Retour</a></p>
</div>