<?php
    
    class PrayerTimeController extends WP_REST_Controller {
        
        public function __construct()
        {
            add_action( 'rest_api_init', array( $this, 'register_routes' ) );
        }
    
        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version = '1';
            $namespace = 'dpt/v' . $version;
            $base = 'prayertime';
            register_rest_route( $namespace, '/' . $base, array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_prayer_times' ),
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
            $data = $request->get_param('filter');
            
            return new WP_REST_Response( $data, 200 );
        }
    }