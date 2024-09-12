DPTAdmin = {
    init: function() {
        this.enableTooltip();
        this.displaySliderOptions();
        this.displaytemplateOptions();
        this.displayCustomAngleFields();
        this.makeCitySearchable();
        this.keepTabActive();
    },

    enableTooltip: function() {
        jQuery(function () {
            jQuery('[data-toggle="tooltip"]').tooltip()
        })
    },

    displaySliderOptions: function () {
        var sliderChbox = jQuery("input#slider-chbox");
        if (! sliderChbox.is(':checked')) {
            jQuery(".ds-slides").hide();
        }

        jQuery('input#slider-chbox').on('change', (function () {
            if (jQuery(this).is(":checked")) {
                jQuery(".ds-slides").show('slow');
            } else {
                jQuery(".ds-slides").hide('slow');
            }
        }));
    },

    displaytemplateOptions: function () {
        jQuery('input.oneChbox').on('change', function() {
            jQuery('input.oneChbox').not(this).prop('checked', false);
        });

        var templateChbox = jQuery("input#template-chbox");
        if (! templateChbox.is(':checked')) {
            jQuery(".ds-templates").hide();
        }

        jQuery('input.templateChbox').on('change', (function () {
            if (jQuery(this).is(":checked")) {
                jQuery(".ds-templates").show('slow');
            } else {
                jQuery(".ds-templates").hide('slow');
            }
        }));
    },

    displayCustomAngleFields: function() {
        var id = jQuery( "#calculationMethod option:selected" ).val();
        if ( id === '6') {
            jQuery("#customMethod").show()
        }
        jQuery('#calculationMethod').on('change',function(){
            if( jQuery(this).val() === '6'){ //custom settings
                jQuery("#customMethod").show()
            } else{
                jQuery("#customMethod").hide()
            }
        });
    },

    makeCitySearchable: function() {
        var id = jQuery( "#csvUpload" ).html();
        if (typeof id != 'undefined') {
    
            jQuery('.form-select').selectpicker();
        }
    },

    keepTabActive: function() {
        jQuery('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
          localStorage.setItem('lastTab', jQuery(this).attr('href'));
        });
        var lastTab = localStorage.getItem('lastTab');
        
        if (lastTab) {
          jQuery('[href="' + lastTab + '"]').tab('show');
        }
      },
};
jQuery(document).ready(function() { DPTAdmin.init(); });
