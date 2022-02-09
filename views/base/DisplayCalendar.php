<?php
namespace Views;

use DateTime;

class DisplayCalendar extends \App\Calendar\Month {
   public function displayCalendarHtml()
   {
      $evenements = new \App\calendar\Events();
      $dayOff = new \App\calendar\Holidays();

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
                     if(isset($holidays[$date->format('Y-m-d')])): ?>
                     <td class="days_off">
                     <?php else: ?>
                     <td>
                     <?php 
                     endif;
                     $this->testToday($date);
                     if ($eventsByDay):
                        $this->testEvent($holidaysByDay, $eventsByDay);
                     endif;?>
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
      $today = date('m-d-Y');
      $month = intval(date('m'));
      $displayDay = $day->format('d');

      if ( $today === $day->format('m-d-Y') && $month === $this->month): ?>
         <p class="active_today"> <?= $displayDay ?></p>
      <?php
      else: ?>
         <p><?= $displayDay ?></p>
      <?php
      endif;
   }

   public function testEvent($holidays, $events) { ?>
      <div class="events">
         <?php
         foreach ($holidays as $holiday):
         ?>
            <p class="holiday"><?= $holiday['name']; ?></p>
         <?php 
         endforeach;
         if (count($events) > 2): ?>
            <p class="event">(X)</p>
         <?php
         else:
            foreach ($events as $event):
               $hourEvent = (new DateTime($event['start']))->format('H');
               $minEvent = (new DateTime($event['start']))->format('i');
               ?>
               <p class="event"><?= 'Rdz à ' . $hourEvent . 'h' . $minEvent; ?></p>
            <?php
            endforeach; 
         endif;?>
            <p class="event_modified">(X)</p>
      </div>
   <?php
   }
}