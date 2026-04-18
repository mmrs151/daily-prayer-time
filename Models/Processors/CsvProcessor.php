<?php

require_once(__DIR__. '/../Validator.php');
require_once(__DIR__. '/../db.php');

if ( ! class_exists('DPTCsvProcessor')) {
    class DPTCsvProcessor
    {
        /**
         * @var array
         */
        private $file;
    
        /**
         * DPTCsvProcessor constructor.
         *
         * @param array $file
         */
        public function __construct($file)
        {
            $this->file = $file;
        }
    
        /**
         * @return bool
         */
        public function isValidFile()
        {
            $fileExtension = pathinfo($this->file["timetable"]["name"], PATHINFO_EXTENSION);
    
            if ( isset($this->file)
                 && $fileExtension === 'csv'
                 && in_array( $this->getFileType(), $this->getAllowedMimes() )
            ) { return true; }
    
            return false;
        }
    
    
        public function process()
        {
            $temp = $this->file["timetable"]["tmp_name"];
            $row = 0;
    
            if (($handle = fopen($temp, "r")) !== FALSE) {
                $validator = new Validator();
                $db = new DatabaseConnection();
                $file = file($temp);
                if (! $validator->isValidNumberOfRows($file)) {
                    $this->goBack();
                    exit;
                }
    
                $header = fgetcsv($handle);
                if (! $validator->checkHeader($header) ) {
                    $this->goBack();
                    exit;
                }

                $inserted = [];
                $updated = [];
    
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (! $validator->isValidData($data)) {
                        $this->goBack();
                        exit;
                    } else {
                        $row++;
                        $data = $validator->getValidData();
                        $result = $db->insertRow($data);
                        $date = $result['date'];
                        
                        if ($result['result'] == 1) {
                            $inserted[] = $date;
                        } elseif ($result['result'] == 2) {
                            $updated[] = $date;
                        }
                    }
                }
            }
            $this->displayResults($row, $inserted, $updated);
            fclose($handle);
        }

        private function displayResults($totalProcessed, $inserted, $updated)
        {
            $insertedCount = count($inserted);
            $updatedCount = count($updated);
            
            echo "<div class='donation-link dptCenter'>" . esc_html($totalProcessed) . " Rows processed</div>";
            echo "<div class='dptCenter' style='margin-bottom: 15px;'>📥 " . $insertedCount . " Inserted &nbsp;|&nbsp; 🔄 " . $updatedCount . " Updated</div>";
            
            echo '<div class="dpt-csv-results" style="max-width: 600px; margin: 20px auto;">';
            
            if ($insertedCount > 0) {
                echo '<details style="margin-bottom: 10px;">';
                echo '<summary style="cursor: pointer; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; font-weight: bold;">';
                echo '📥 INSERTED (' . $insertedCount . ' rows) - Click to view</summary>';
                echo '<div style="padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; margin-top: 5px;">';
                echo '<ul style="columns: 3; column-gap: 20px; list-style: none; margin: 0; padding: 0;">';
                foreach ($inserted as $date) {
                    echo '<li>• ' . esc_html($date) . '</li>';
                }
                echo '</ul></div></details>';
            }
            
            if ($updatedCount > 0) {
                echo '<details style="margin-bottom: 10px;">';
                echo '<summary style="cursor: pointer; padding: 10px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; font-weight: bold;">';
                echo '🔄 UPDATED (' . $updatedCount . ' rows) - Click to view</summary>';
                echo '<div style="padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; margin-top: 5px;">';
                echo '<ul style="columns: 3; column-gap: 20px; list-style: none; margin: 0; padding: 0;">';
                foreach ($updated as $date) {
                    echo '<li>• ' . esc_html($date) . '</li>';
                }
                echo '</ul></div></details>';
            }
            
            echo '</div>';
            
            $this->donationLink();
        }
    
        /**
         * @return string | null
         */
        public function getFileType()
        {
            return $this->file["timetable"]["type"];
        }
    
        /**
         * @return array
         */
        private function getAllowedMimes()
        {
            return array(
                'text/plain',
                'text/comma-separated-values',
                'text/csv',
                'application/csv',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'text/tsv'
            );
        }
    
        public function donationLink()
        {
            ?>
            <div class="donation" xmlns="http://www.w3.org/1999/html"><h2 class="text-danger">Appeal to Send Sadaqa to my GRAVE</h2>
                <div class="donation-text"><i><b>Surat Al-Baqarah [2:261]</b></i></br>“The likeness of those who spend for Allah’s sake is as the likeness of a grain of corn, it grows seven ears every single ear has a hundred grains, and Allah multiplies (increases the reward of) for whom He wills, and Allah is sufficient for His creatures’ needs, All-Knower).”</div>
                <div class="donation-text">
                    <li>“Giving in charity doesn’t decrease you wealth in the slightest.” <b><i>[Narrated by Muslim 2588]</i></b></li>
                    <li>“Give (in charity) and do not give reluctantly lest Allaah should give you in a limited amount; and do not withhold your money lest Allaah should withhold it from you.”<b><i>[Saheeh al-Bukhaaree (2590, 2591) and Saheeh Muslim (2244).]</i></b></li>
                    <li>“charity extinguishes (removes) sins just as water extinguishes fire”<b><i>[Sunan At-Tirmidhi, 2616]</i></b></li>
                </div>
                <div class="donation-link dptCenter"><a href="https://donate.uwt.org/Account/Index.aspx" target="_blank">Send Sadaqa to my Grave</a> </div>
            </div>
            <?php
        }
    
        public function goBack()
        {
            echo "<a href='javascript:window.location = document.referrer;'><h3 class='green'><< Go back and Retry</h3> </a>.<br />";
        }
    }
    
}
