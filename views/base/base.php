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
      <nav>
         <a href="\Agenda/public">Accueil</a>
         <a href="test">page test</a>
      </nav>

      <div>
         <?= $content ?>
      </div>
   </body>
</html>