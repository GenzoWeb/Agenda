<?php
namespace Views;

class DisplayCalendar extends \App\Calendar\Month {
   public function displayCalendarHtml() 
   {
      for ( $i = 1; $i <= $this->numberMonth; $i++)
      {
         $starts = $this->getFirstDay();
         $starts = $starts->format("N") === '1' ? $starts : $this->getFirstDay()->modify('last monday');
      ?>
         <div>
            <table class="calendar">
               <thead>
                  <tr>
                     <th colspan="7">
                        <?= $this->getDate(); ?>
                     </th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                  <?php foreach($this->days as $day): ?>
                     <td>
                        <?= $day; ?>
                     </td>
                     <?php endforeach; ?>
                  </tr>
                  <?php for ($j = 0; $j < $this->getWeeks(); $j++): ?>
                  <tr>
                     <?php foreach($this->days as $k => $day): ?>
                     <td>
                        <p><?= $starts->modify("+" . ($k + $j * 7) . "days")->format('d'); ?></p>
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
}