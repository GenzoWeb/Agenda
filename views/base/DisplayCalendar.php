<?php
namespace Views;

class DisplayCalendar extends \App\Calendar\Month {
   public function displayCalendarHtml() : void 
   {
      for ( $i = 1; $i <= $this->numberMonth; $i++)
      {
         $starts = $this->getFirstDay();
         $starts = $starts->format("N") === '1' ? $starts : $this->getFirstDay()->modify('last monday');
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
                     <?php foreach($this->days as $k => $day): ?>
                     <td>
                        <?php $this->testToday($starts->modify("+" . ($k + $j * 7) . "days")) ?>
                     </td>
                     <?php endforeach; ?>
                  </tr>
                  <?php endfor;?>
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

   public function testToday($day): void
   {
      $today = date('m-d-Y');
      $month = intval(date('m'));
      $displayDay = $day->format('d');

      if ( $today === $day->format('m-d-Y') && $month === $this->month) { ?>
         <p class="active_today"> <?= $displayDay ?></p>
      <?php
      } else { ?>
         <p><?= $displayDay ?></p>
      <?php
      }
   }
}