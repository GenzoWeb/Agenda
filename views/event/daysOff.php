<?php
$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$title = "Ajoutez jour de congés";
$day = "";
if ($params) {
   $day = $params['year'] . '-' . $params['month'] . '-' . $params['day'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $errors = [];
   $validator = new \App\calendar\EventValidator();
   $errors = $validator->validates($_POST);
   if (!empty($errors)) {

   }
}
?>

<div class="section_create">
   <h1>Ajouter congés</h1>
   <div class="form_create">
      <form action="" method="post">
         <select name="name" id="name" placeholder="Nom du rendez vous *" required>
            <option value="">Choisir un type de congés</option>
            <option value="RTT S">RTT S</option>
            <option value="RTT E">RTT E</option>
            <option value="CP">CP</option>
            <option value="CA">CA</option>
            <option value="Autre">Autre</option>
         </select>
         <input type="date" name="start" id="start" value="<?= $day ?>" required>
         <input type="date" name="end" id="end">
         <button type="submit" class="btn">Envoyer</button>
      </form>  
   </div>
</div>