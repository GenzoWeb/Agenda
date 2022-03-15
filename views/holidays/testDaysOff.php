
<?php

function testNoWorkingDay($date)
{
   $year = (new \DateTime($date))->format('Y');
   
   $easterDate  = easter_date($year);
   $easterDay   = date('j', $easterDate);
   $easterMonth = date('n', $easterDate);
   $easterYear   = date('Y', $easterDate);

   $holidays = array(
      // These days have a fixed date
      date("d-m-Y", mktime(0, 0, 0, 1,  1,  $year)),  // 1er janvier
      date("d-m-Y", mktime(0, 0, 0, 5,  1,  $year)),  // Fête du travail
      date("d-m-Y", mktime(0, 0, 0, 5,  8,  $year)),  // Victoire des alliés
      date("d-m-Y", mktime(0, 0, 0, 7,  14, $year)),  // Fête nationale
      date("d-m-Y", mktime(0, 0, 0, 8,  15, $year)),  // Assomption
      date("d-m-Y", mktime(0, 0, 0, 11, 1,  $year)),  // Toussaint
      date("d-m-Y", mktime(0, 0, 0, 11, 11, $year)),  // Armistice
      date("d-m-Y", mktime(0, 0, 0, 12, 25, $year)),  // Noel

      // These days have a date depending on easter
      date("d-m-Y", mktime(0, 0, 0, $easterMonth, $easterDay + 2,  $easterYear)), // Pâques
      date("d-m-Y", mktime(0, 0, 0, $easterMonth, $easterDay + 40, $easterYear)), // Ascension
      // date("d-m-Y", mktime(0, 0, 0, $easterMonth, $easterDay + 51, $easterYear)), // Pentecôte (journée de solidarité)
   );

   // dd(sort($holidays));

   if (in_array($date, $holidays)) {
      return true;
   } else {
      return false;
   }
}