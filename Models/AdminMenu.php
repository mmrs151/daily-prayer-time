<?php
    class AdminMenu
    {
        public function __construct()
        {
            add_action( 'admin_menu', array( &$this, 'addMenuPages' ) );
        }
    
        public function addMenuPages()
        {
            add_menu_page(
                'Daily Prayer Time',
                'Prayer time',
                'publish_pages',
                'dpt',
                array(&$this, 'renderMainPage'),
                plugins_url('../Assets/images/icon19.png', __FILE__)
            );
    
            add_submenu_page('dpt',
                'Settings',
                'Settings',
                'publish_pages',
                'dpt',
                array(&$this, 'renderMainPage')
            );
    
            add_submenu_page(
                'dpt',
                'API Doc',
                'API Doc',
                'publish_pages',
                'dpt-api-doc',
                array(&$this, 'dpt_api_doc')
            );

            add_submenu_page(
                'dpt',
                'Helps and Tips',
                'Helps and Tips',
                'publish_pages',
                'helps-and-tips',
                array(&$this, 'helps_and_tips')
            );
        }
    
        public function renderMainPage()
        {
            include __DIR__ . '/../Views/widget-admin.php';
        }
    
        public function helps_and_tips()
        {
            include __DIR__ . '/../Views/HelpsAndTips.php';
        }

        public function dpt_api_doc()
        {
            include __DIR__ . '/../Views/DptApiDoc.php';
        }
    
    }