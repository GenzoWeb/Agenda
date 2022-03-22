<?php
namespace App\API;

use DateTime;

class SchoolHolidays {
   public function getSchoolHolidays(DateTime $start = null, DateTime $end = null) : ?array
   {
      $startDate = $start->format('Y-m-d');
      $endDate = $end->format('Y-m-d');

      if ( $startDate > '2022-06-15' && $startDate < '2022-09-15') {
         $year = $start->format('Y');
         $schoolYear = $year - 1 . '-' . $year;
         $url = "https://data.education.gouv.fr//api/records/1.0/search/?dataset=fr-en-calendrier-scolaire&q=&facet=description&facet=start_date&facet=end_date&refine.location=Nancy-Metz&refine.description=Vacances+d'%C3%89t%C3%A9&refine.annee_scolaire={$schoolYear}&exclude.population=Enseignants";
      }
      else {
         $endDate = $end->modify('+15days')->format('Y-m-d');
         $url = "https://data.education.gouv.fr/api/records/1.0/search/?dataset=fr-en-calendrier-scolaire&q=end_date%3A%5B{$startDate}T23%3A00%3A00Z+TO+{$endDate}T21%3A59%3A59Z%5D&facet=description&facet=start_date&facet=end_date&refine.location=Nancy-Metz&exclude.population=Enseignants";
      }

      $curl = curl_init($url);
      curl_setopt_array($curl, [
         // CURLOPT_CAINFO => __DIR__ . DIRECTORY_SEPARATOR . 'certif.cer',
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_TIMEOUT => 5
      ]);
      $data = curl_exec($curl);

      if ($data === false || curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
      $curl = curl_init($url);
         return null;
      }
      $results = [];
      $data = json_decode($data, true);

      foreach ($data['records'] as $dateSchoolHoliday) {
         $results[] = [
            'description' => $dateSchoolHoliday['fields']['description'],
            'start' => (new \DateTime($dateSchoolHoliday['fields']['start_date']))->format('d-m-Y'),
            'end' => (new \DateTime($dateSchoolHoliday['fields']['end_date']))->format('d-m-Y'),
         ];
      }

      return $results;

      curl_close($curl);
   }
}
