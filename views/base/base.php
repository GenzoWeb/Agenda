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
                  <li><a href="\Agenda/public">Accueil</a></li>
                  <li>
                     <a href="annee-<?= intval(date('Y'))?>">Calendriers annuels</a>
                     <ul class="year_list">
                        <?php for ($i = 0; $i < 4; $i++):?>
                        <li><a href="annee-<?= intval(date('Y') + ($i - 1))?>">Calendrier <?= intval(date('Y') + ($i - 1))?></a></li>
                        <?php endfor;?>
                     </ul>
               </ul>
            </nav>
         </header>
         <div class="content">
            <?= $content ?>
         </div>
      </div>
   </body>
</html>