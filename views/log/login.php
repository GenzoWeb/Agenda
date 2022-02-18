<?php
$error = null;
$password = '$2y$12$T2fEyWzCtGOr4z2aNLylSu/2hwEpF8KZnYKoNSp5N/34ruiTYFtuq';
$title = "Se connecter";
$pseudo="";
$pass="";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $pseudo = $_POST['pseudo'];
   $pass = $_POST['password'];

   if ($pseudo === 'genzo' && password_verify($pass, $password)) {
      session_start();
      $_SESSION['logged'] = 1;
      header('location:' . $_SESSION['url']);
   } else {
      $error = "Identifiants incorrects";
   }

   if (empty($pseudo) || empty($pass)){
      $error = "Veuillez remplir tout les champs";
   }
}

?>

<div class="section_login">
   <h1>Se connecter</h1>
   <div class="login">
      <?php if ($error): ?>
      <div><p class="alert"><?= $error ?></p></div>
      <?php endif; ?>

      <form action="" method="post">
         <input type="text" name="pseudo" id="pseudo" placeholder="Pseudo" value="<?= $pseudo ?>" >
         <input type="password" name="password" id="inputPassword" placeholder="Mot de passe" value="<?= $pass ?>" >
         <button class="btn" type="submit">Se connecter</button>
      </form>  
   </div>
</div>