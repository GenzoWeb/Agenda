<?php
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
   <head>   
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="La description">
      <link rel="stylesheet" type="text/css" href="\Agenda/assets/css/reset.css"/>
      <link rel="stylesheet" type="text/css" href="\Agenda/assets/css/style.css"/>
      <title><?= $title ?? 'Accueil'?></title>
   </head>
   <body>
      <div class="container">
         <header id="header">
            <nav>
               <ul>
                  <li><a href="/Agenda/public">Accueil</a></li>
                  <li>
                     <a href="annee-<?= intval(date('Y'))?>">Calendriers annuels</a>
                     <ul class="menu_list">
                        <?php for ($i = -1; $i < 3; $i++):?>
                        <li><a href="/Agenda/public/annee-<?= intval(date('Y') + $i)?>">Calendrier <?= intval(date('Y') + $i)?></a></li>
                        <?php endfor;?>
                     </ul>
                  </li>
                  <li class="menu">
                     <p>Ajoutez</p>
                     <ul class="menu_list">
                        <li><a href="/Agenda/public/repos">Congés</a></li>
                        <li><a href="/Agenda/public/rendez-vous">Rendez-vous</a></li>
                        <li><a href="/Agenda/public/gestion-compteur">Nombre de jours</a></li>
                        <li><a href="/Agenda/public/gestion-modif-compteur">Modifier compteurs</a></li>
                        <li><a href="/Agenda/public/gestion-suppr">Supprimez</a></li>
                     </ul>
                  </li>
                  <?php if (isset($_SESSION['logged'])): ?>
                  <li class="logout"><a href="/Agenda/public/logout">Se déconnecter</a></li>
                  <?php endif ?>
               </ul>
            </nav>
         </header>
         <div class="content">
            <?= $content ?>
         </div>
      </div>
      <script src="https://unpkg.com/boxicons@2.1.1/dist/boxicons.js"></script>
   </body>
</html>