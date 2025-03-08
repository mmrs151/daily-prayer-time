<?php
    require_once (__DIR__. '/../../Models/db.php');
    require_once (__DIR__. '/../../Models/HijriDate.php');
    require_once (__DIR__. '/../../Views/TimetablePrinter.php');
    
    class PrayerTimeController extends WP_REST_Controller
    {
        /** @var DatabaseConnection */
        private $db;

        /** @var TimetablePrinter */
        private $timetablePrinter; 

        /** @var int */
        private  $version;
        /** @var string */
        protected $namespace;
        /** @var string */
        private $base;
        
        public function __construct()
        {
            $this->db = new DatabaseConnection();
            $this->timetablePrinter = new TimetablePrinter();
            $this->version = '1';
            $this->namespace = 'dpt/v' . $this->version;
            $this->base = 'prayertime';
            add_action( 'rest_api_init', array( $this, 'register_routes' ) );
        }
    
        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            register_rest_route( $this->namespace, '/' . $this->base, array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_prayer_times' ),
                    'permission_callback' => function() { return ''; }
                ),
            ));
        }
        
        /**
         * Get a collection of items
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_prayer_times( $request ) {
            $filter = $request->get_param('filter');
            
            if ( $filter == 'today' ) {
                $response = $this->db->getPrayerTimeForToday(1);
                $hijriDate = new HijriDate();
                $response['hijri_date_convert'] = $hijriDate->getDate(date("d"), date("m"), date("Y"), true);
                $response['jumuah'] = array_filter([get_option('jumuah1'), get_option('jumuah2'), get_option('jumuah3')]);
                $response['next_prayer'] = $this->timetablePrinter->getNextIqamahTime($this->db->getPrayerTimeForToday(), false, true);
            } elseif ( $filter == 'month'){
                $response = $this->db->getPrayerTimeForMonth(date('m'), date('Y'));
            } elseif ( $filter == 'ramadan'){
                $response = $this->db->getPrayerTimeForRamadan();
            } elseif ( $filter == 'year'){
                $response = $this->db->getRows();
            } elseif ( $filter == 'iqamah'){
                $response = $this->db->getIqamahTimeForToday();
            } elseif ( $filter == 'tomorrow_fajr'){
                $response = $this->db->getFajrJamahForTomorrow();
            } elseif ( $filter == 'iqamah_changes'){
                $response = $this->db->getJamahChanges(1);
            } elseif ('download_timetable' == $filter) {
                $this->download_timetable();
            }
            else {
                $response = [
                    'arguments'=>
                        [
                            '&filter=today',
                            '&filter=month',
                            '&filter=year',
                            '&filter=ramadan',
                            '&filter=iqamah',
                            '&filter=tomorrow_fajr',
                            '&filter=iqamah_changes',
                            '&filter=download_timetable'
                        ]
                ];
            }
            return new WP_REST_Response( [$response], 200 );
        }


        public function download_timetable() {
            global $wpdb;

            $blog_id = get_current_blog_id();
            $tableName = $wpdb->prefix . "timetable";

            $latest_year_query = "SELECT MAX(YEAR(d_date)) FROM $tableName";
            $latest_year = $wpdb->get_var($latest_year_query);

            if (!$latest_year) {
                error_log("No valid year found in table: " . $tableName);
                die('No data found.');
            }

            error_log("Fetching data for the latest year: " . $latest_year . " from table: " . $tableName);

            $query = $wpdb->prepare("SELECT * FROM $tableName WHERE YEAR(`d_date`) = %d", $latest_year);

            error_log("Running query: " . $query);

            $results = $wpdb->get_results($query, ARRAY_A);

            if (!$results) {
                error_log("No data found in table: " . $tableName);
                die('No data found.');
            }

            $blog_name = get_bloginfo('name');
            $filename = "timetable_" . sanitize_title_with_dashes($blog_name) . ".csv"; // Sanitize the blog name to be URL-safe

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $output = fopen('php://output', 'w');

            $columns = array_keys($results[0]);
            fputcsv($output, $columns);

            foreach ($results as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
            exit;
        }
    }