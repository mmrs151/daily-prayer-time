jQuery(document).ready(function() {
    jQuery('.selectpicker').selectpicker();
// Tabs
    jQuery('#tabs').tabs().show();
    jQuery('#tabs').tabs({
        active   : jQuery.cookie('activetab'),
        activate : function( event, ui ){
            jQuery.cookie( 'activetab', ui.newTab.index(),{
                expires : 10
            });
        }
    });
});

