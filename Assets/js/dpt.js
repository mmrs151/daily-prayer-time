var isTimerOn = false;

DPT = {
    init: function() {

        this.monthlyCalendarChange();
        this.displaySliderOptions();
        this.changeInputBackground();
        this.printDiv();
        this.bs3screenOptions();
        this.startTimer();
        this.dsRefreshNextPrayer();
        this.refreshBeforeIqamah();
        this.digitialClock();
        this.displayCustomAngleFields();
    },

    displayCustomAngleFields: function() {
        var id = jQuery( "#calculationMethod option:selected" ).val();
        if ( id === '6') {
            jQuery("#customMethod").show()
        }
        jQuery('#calculationMethod').on('change',function(){
            if( jQuery(this).val() === '6'){ //custom settings
                console.log(jQuery(this).val());
                jQuery("#customMethod").show()
            } else{
                jQuery("#customMethod").hide()
            }
        });
    },

    monthlyCalendarChange: function () {
        jQuery('#monthAjax').on('change', '#month', function() {
            jQuery.blockUI({
                timeout:   1000,
            });
            var display = jQuery('#display').val();
            var month = this.value;
            jQuery.ajax({
                url: timetable_params.ajaxurl,
                data: {
                    'action':'get_monthly_timetable',
                    'month' : month,
                    'display': display
                },
                success: function(response){
                    jQuery('#monthlyTimetable').html(response);
                },
                error: function(responseObj, strError){
                    console.log(strError);
                }
            });
        });

        jQuery('#month').trigger('change');
    },

    displaySliderOptions: function () {
        var sliderChbox = jQuery("input#slider-chbox");

        sliderChbox.on('click', function() {
            jQuery(".ds-slides").toggle("slow");
        });

        if (! sliderChbox.is(':checked')) {
            jQuery(".ds-slides").hide();
        }
    },

    changeInputBackground: function () {
        jQuery("input").on('change', function() {
            jQuery(this).css("background-color","#F6F8CE");
        });
    },

    printDiv: function (divName) {
        if (divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    },

    bs3screenOptions: function( ) {
        jQuery("#contextual-help-link").click(function () {
            jQuery("#contextual-help-wrap").css("cssText", "display: block !important;");
        });
        jQuery("#show-settings-link").click(function () {
            jQuery("#screen-options-wrap").css("cssText", "display: block !important;");
        });
    },

    dsRefreshNextPrayer: function () {
        if (! jQuery('.x-board')[0]) {
            return;
        }
        jQuery.ajax({
            url: timetable_params.ajaxurl,
            data: {
                'action':'get_ds_next_prayer',
            },
            success: function(response){
                setTimeout(DPT.dsRefreshNextPrayer, (1000 * 60 * 1) );
                jQuery('.dsNextPrayer').html(response);
                if (! isTimerOn ) {
                    DPT.startTimer();
                    isTimerOn = true;
                }
            },
            error: function(responseObj, strError){
                console.log(strError);
            },
            timeout: (1000 * 30) // 30 seconds
        });
    },

    startTimer: function () {
        var presentTime = '';

        if (document.getElementsByClassName('timeLeftCountDown')[0]) {
            presentTime = document.getElementsByClassName('timeLeftCountDown')[0].innerHTML.trim();
            presentTime = presentTime.split(' ')[0];

            var timeArray = presentTime.split(/[:]+/);
            if (timeArray && timeArray.length === 2) {

                var m = timeArray[0];
                var s = DPT.checkSecond((timeArray[1] - 1));

                if(s == "59"){ m = m - 1;}

                if ( m >= 0) {
                    var timeLeftCountDownElement = document.getElementsByClassName('timeLeftCountDown');
                    for(var i = 0; i < timeLeftCountDownElement.length; i++) {
                        document.getElementsByClassName('timeLeftCountDown')[i].innerHTML = m + ":" + s;
                    }
                }

                if(m == 0 && s == 0) {
                    DPT.timeoutScreen();
                }
            }
            setTimeout(DPT.startTimer, 1000);
        }
    },

    timeoutScreen: function() {
        var min = jQuery('#screenTimeout').val()
        if (min) {
            jQuery("body").append("<div id='overlay'></div>");
            jQuery('#overlay').animate({
                opacity: 0.9,
            }, (10000), function() {
            });
            // setTimeout(function() { jQuery('#overlay').remove(); }, 1000 * 60 * parseInt(min) );
            setTimeout(function() { window.location.reload() }, 1000 * 60 * parseInt(min) );
        } else {
            setTimeout(function() { window.location.reload() }, 1000 * 60 );
        }
    },

    checkSecond: function (sec) {
        if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
        if (sec < 0) {sec = "59"};
        return sec;
    },

    executeFunctionOnTime: function (hours, minutes, seconds, func) {
        var now = new Date();
        var then = new Date();
    
        if(now.getHours() > hours ||
           (now.getHours() == hours && now.getMinutes() > minutes) ||
            now.getHours() == hours && now.getMinutes() == minutes && now.getSeconds() >= seconds) {
            then.setDate(now.getDate() + 1);
        }
        then.setHours(hours);
        then.setMinutes(minutes);
        then.setSeconds(seconds);
    
        var timeout = (then.getTime() - now.getTime());
        setTimeout(func, timeout);
    },

    refreshBeforeIqamah: function()
    {
        var iqamah = jQuery('#refreshPoint').val();
        if ( ! iqamah ) {
            return;
        }
        iqamah = JSON.parse(iqamah);
        for(var i = 0; i < iqamah.length; i ++)
        {
            var timeParts = iqamah[i].split(":");
            DPT.executeFunctionOnTime(timeParts[0], timeParts[1], timeParts[2], function() { window.location.reload(true); });
        }

        DPT.executeFunctionOnTime('00', '00', '00', function() { window.location.reload(true); });
        DPT.executeFunctionOnTime('02', '00', '00', function() { window.location.reload(true); });
    },

    digitialClock: function ()
    {
        var newDate = new Date();

        newDate.setDate(newDate.getDate());

        setInterval( function() {
            var minutes = new Date().getMinutes();
            jQuery("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
            },1000);
            
        setInterval( function() {
            var date = new Date();
            var hours = date.getHours() == 0
                ? 12 : date.getHours() > 12
                ? date.getHours() - 12
                : date.getHours();
            jQuery("#hours").html(hours);
            }, 1000);

        setInterval( function() {
            var ampm = new Date().getHours() < 12 ? 'AM' : 'PM';
            jQuery("#ampm").html( ampm );
            }, 1000);
    }
};
jQuery(document).ready(function() { DPT.init(); });

