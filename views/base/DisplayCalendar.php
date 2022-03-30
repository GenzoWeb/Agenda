<?php
namespace Views;

if (session_status() === PHP_SESSION_NONE) {
   session_start();
}  

use \App\calendar\Month as Month;
use App\Connection;
use DateTime;

require("../views/holidays/testDaysOff.php");

class DisplayCalendar extends Month {

   public function displayCalendarHtml()
   {
      $schoolHolidays = $this->holidaysSchool();
      $pdo = Connection::getPDO();
      $evenements = new \App\calendar\Events($pdo);
      $dayOff = new \App\calendar\Holidays($pdo);
      $sessionTest = new \App\Auth();
      $sessionTest->unset();

      for ( $i = 1; $i <= $this->numberMonth; $i++)
      {
         $starts = $this->getFirstDay();
         $starts = $starts->format("N") === '1' ? $starts : $this->getFirstDay()->modify('last monday');
         $end = (clone $starts)->modify('+' . (6 + 7 * ($this->getWeeks() - 1)) . ' days');
         $events = $evenements->getEventsByDay($starts, $end);
         $holidays = $dayOff->getHolidaysByDay($starts, $end);
      ?> 
         <div class="table_calendar">
            <table class="calendar">
               <thead>
                  <tr>
                     <th colspan="8">
                        <a class="link_month" href=<?="mois-" .$this->month . "-annee-" . $this->year?>><?= $this->getDate(); ?></a>
                     </th>
                  </tr>
               </thead>
               <tbody>
                  <tr class="days_calendar">
                     <td class="number_week">n°</td>
                     <?php foreach($this->days as $day): ?>
                     <td class="day_calendar">
                        <?= $day; ?>
                     </td>
                     <?php endforeach; ?>
                  </tr>
                  <?php for ($j = 0; $j < $this->getWeeks(); $j++): ?>
                  <tr>
                     <td class="number_week">
                        <?= $starts->modify("+" . $j . "week")->format('W')?>
                     </td>

                     <?php foreach($this->days as $k => $day):
                     $date = $starts->modify("+" . ($k + $j * 7) . "days");
                     $eventsByDay = $events[$date->format('Y-m-d')] ?? [];
                     $holidaysByDay = $holidays[$date->format('Y-m-d')] ?? [];
                     $noWork = testNoWorkingDay($date->format('d-m-Y'));
                     $class = "";

                     if ($date->format('w') <= 0  || $date->format('w') >= 6 || $noWork) {
                        $class = "week_end";
                     }

                     foreach($holidaysByDay as $holiB) {
                        if($holiB['semi'] === "0.5") {
                           $class = $class . " semi";
                        }
                     }
                   
                     if(isset($holidays[$date->format('Y-m-d')])) {
                        $class = $class . " days_off";
                     }?>

                     <td class="<?= $class ?>">
                        <?php
                        if($schoolHolidays) {
                           $numberHolidays = count($schoolHolidays);
                           $testDate = new DateTime($date->format('Y-m-d'));
                           if ($numberHolidays) {
                              if ($numberHolidays > 1) {
                                 foreach ($schoolHolidays as $s) {
                                    if($s['description'] !== "Pont de l'Ascension") {
                                       $startHolidays =  (new dateTime($s['start']))->modify('+1days');
                                       $endHolidays =  new dateTime($s['end']);
                                    }

                                    if ($testDate  >= $startHolidays &&  $testDate  <= $endHolidays) { ?>
                                       <div class="school_off"></div>
                                    <?php }  
                                 }
                              } else {
                                 $startHolidays = (new dateTime($schoolHolidays[0]['start']))->modify('+1days');
                                 $endHolidays = new dateTime($schoolHolidays[0]['end']);
      
                                 if ($testDate  >= $startHolidays &&  $testDate  <= $endHolidays) { ?>
                                    <div class="school_off"></div>
                                 <?php }  
                              }
                           }
                        } ?>

                        <div class="cell">
                        
                        <?php $this->testToday($date);
                        if ($eventsByDay || $holidaysByDay ):
                           $this->testEvent($holidaysByDay, $eventsByDay);
                        endif;?>
                        </div>
                     </td>
                     <?php endforeach; ?>
                  </tr>
                  <?php endfor; ?>
               </tbody>
            </table>
         </div>
         <?php
         $this->month += 1;
         if ($this->month === 13) {
            $this->month = 1;
            $this->year = $this->year + 1;
         }
         if ($this->month === 0) {
            $this->month = 12;
            $this->year = $this->year - 1;
         }
      }
   }


   public function testToday($day)
   {
      $today = date('Y-m-d');
      $month = intval(date('m'));
      $displayDay = $day->format('d');

      $class= "";
      if ($today === $day->format('Y-m-d') && $month === $this->month) :
      $class = "list_events active_today";
      else :
      $class = "list_events";
      endif;
      ?>

      <div class="<?= $class ?>">
      <?php if(isset($_SESSION['logged'])) : ?>
         <p class="cursor"><?= $displayDay ?></p>
         <div class="choice_event">
            <a href="repos/<?= $day->format('d/m/Y')?>">congés</a>
            <a href="rendez-vous/<?= $day->format('d/m/Y')?>">rdv</a>
         </div>
         <?php else : ?>
         <p><?= $displayDay ?></p>
         <?php endif; ?>
      </div>
   <?php
   }

   public function testEvent($holidays, $events) { ?>
      <div class="events">
         <?php
         if($holidays):
            foreach ($holidays as $holiday):
               if(isset($_SESSION['logged'])) : ?>
                  <a href="conges-<?=(new DateTime($holiday['start']))->format('Y-m-d')?>" class="holiday"><?= $holiday['name']; ?></a>
               <?php else : ?>
                  <p class="holiday"><?= $holiday['name']; ?></p>
               <?php endif;
            endforeach;
         endif;

         if($events):
            if (count($events) > 2): ?>
               <a href="rendez-vous-<?=(new DateTime($events[0]['start']))->format('Y-m-d')?>" class="event">Rdv</a>
            <?php
            else: ?>
               <div class="two_events">
               <?php
               foreach ($events as $event):
                  $hourEvent = (new DateTime($event['start']))->format('H');
                  $minEvent = (new DateTime($event['start']))->format('i');
               ?>
                  <a href="rendez-vous-<?=(new DateTime($events[0]['start']))->format('Y-m-d')?>" class="event"><?= 'Rdv à ' . $hourEvent . 'h' . $minEvent; ?></a>
               <?php
               endforeach;  ?>
               </div>
            <?php endif; ?>
            <a href="rendez-vous-<?=(new DateTime($events[0]['start']))->format('Y-m-d')?>" class="event_modified">Rdv</a>
         <?php endif; ?>
      </div>
   <?php
   }

   public function holidaysSchool() {
      $start = $this->getFirstDay();
      $firstMondayMonth = new dateTime($this->getFirstDay()->modify('last monday')->format('Y-m-d'));
      $lastDayMonth = new DateTime((clone $start)->modify('+' . ($this->numberMonth - 1) . 'months')->format( 'Y-m-t' ));
      $lastSundayMonth = $lastDayMonth->format('N') === '7' ? $lastDayMonth : $lastDayMonth->modify('next sunday');

      $schoolHolidays = new \App\API\SchoolHolidays();
      $school = $schoolHolidays->getSchoolHolidays($firstMondayMonth, $lastSundayMonth );
      
      if ($school) {
         if($school[0]['description'] !== "Pont de l'Ascension") {
            return $school;
         }
      }
   }
}