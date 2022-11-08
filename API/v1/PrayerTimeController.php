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
                $response['jumuah'] = get_option('jumuah');
                $response['next_prayer'] = $this->timetablePrinter->getNextIqamahTime($this->db->getPrayerTimeForToday(), false, true);
            } elseif ( $filter == 'month'){
                $response = $this->db->getPrayerTimeForMonth(date('m'));
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
            } else {
                $response = ['arguments'=> [
                    '&filter=today',
                    '&filter=month',
                    '&filter=year',
                    '&filter=ramadan',
                    '&filter=iqamah',
                    '&filter=tomorrow_fajr',
                    '&filter=iqamah_changes']
                ];
            }
            return new WP_REST_Response( [$response], 200 );
        }
    }