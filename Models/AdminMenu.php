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
                'manage_options',
                'dpt',
                array(&$this, 'renderMainPage'),
                plugins_url('../Assets/images/icon19.png', __FILE__)
            );
    
            add_submenu_page('dpt',
                'Settings',
                'Settings',
                'manage_options',
                'dpt',
                array(&$this, 'renderMainPage')
            );
    
            add_submenu_page(
                'dpt',
                'Helps and Tips',
                'Helps and Tips',
                'manage_options',
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
    
    }