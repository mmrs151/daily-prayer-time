jQuery(document).ready(function() {
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


jQuery(function () {
    jQuery('[data-toggle="tooltip"]').tooltip()
})
