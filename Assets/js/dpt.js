var isTimerOn = false;

DPT = {
    isFirstMin: true,
    diffMin: 59,

    init: function() {
        console.log(timetable_params);
        this.monthlyCalendarChange();

        this.changeInputBackground();
        this.printDiv();
        this.startTimer();
        this.dsRefreshNextPrayer();
        this.refreshBeforeIqamah();
        this.continiousMarquee();
        this.digitialClock();

        this.dimMonitorOvernight();
        this.dsRefreshQuranVerse();

        this.playFajrAdhan();
        this.playOtherAdhan();

        this.keepScreenOn();
        this.fadingMessages();

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

    keepScreenOn: function () {
        if (jQuery('div.x-board')[0]) {

            var noSleep = new NoSleep();
            noSleep.enable();
        }
    },

    dsRefreshNextPrayer: function () {
        if (
            jQuery('.x-board')[0] 
            || jQuery('.x-board-modern')[0]
            || jQuery('.d-masjid-e-usman')[0]
        ) {        

            jQuery.ajax({
                url: timetable_params.ajaxurl,
                data: {
                    'action':'get_ds_next_prayer',
                },
                success: function(response){
                    setTimeout(DPT.dsRefreshNextPrayer, (1000 * 60) ); // 60 seconds
                    jQuery('.dsNextPrayer').html(response);
                    if (! isTimerOn ) {
                        document.getElementById('playBeepButton')
                            .addEventListener('click', function() {
                            DPT.beep();
                        });
                        min = parseInt(jQuery('#nextPrayerTimeDifff').text() - 1);
                        DPT.startTimer(min);
                        isTimerOn = true;
                    }
                },
                error: function(responseObj, strError){
                    console.log(strError);
                },
                timeout: (1000 * 30) // 30 seconds
            });
        }
    },


    dsRefreshQuranVerse: function () {
        // if (! jQuery('.x-board')[0]) {
        //     return;
        // }
        jQuery.ajax({
            url: timetable_params.ajaxurl,
            data: {
                'action':'get_ds_quran_verse',
            },
            success: function(response){
                setTimeout(DPT.dsRefreshQuranVerse, (1000 * 30) );
                jQuery('#quranVerse').html(response);
            },
            error: function(responseObj, strError){
                console.log(strError);
            },
            timeout: (1000 * 30) // 30 seconds
        });
    },

    startTimer: function (min) {
        var presentTime = '';
        if (document.getElementsByClassName('timeLeftCountDown')[0]) {
            presentTime = document.getElementsByClassName('timeLeftCountDown')[0].innerHTML.trim();
            presentTime = presentTime.split(' ')[0];

            var timeArray = presentTime.split(/[:]+/);
            if (timeArray && timeArray.length == 2) {
                if (DPT.isFirstMin) {
                    DPT.diffMin = timeArray[0] - 1;
                }

                var s = DPT.getRemainingSecond(); //DPT.checkSecond(timeArray[1] - 1); 
                if(s == "00"){
                    DPT.diffMin = DPT.diffMin - 1;
                }

                if ( DPT.diffMin >= 0) {
                    var timeLeftCountDownElement = document.getElementsByClassName('timeLeftCountDown');
                    for(var i = 0; i < timeLeftCountDownElement.length; i++) {
                        document.getElementsByClassName('timeLeftCountDown')[i].innerHTML = DPT.diffMin + ":" + s;
                    }
                }
                if(DPT.diffMin == 0 && s == 1) {
                    document.getElementById('playBeepButton').click();
                    DPT.timeoutScreen();
                }
            }

            DPT.isFirstMin = false;

            setTimeout(DPT.startTimer, 1000); // 1 second
        }
    },

    checkSecond: function (sec) {
            if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
            if (sec < 0) {sec = "59"};
            return sec;
        },

    getRemainingSecond: function () {

        const now = new Date();
        const seconds = now.getSeconds();
        sec = 0;
        if (seconds > 0) {
            sec =  60 - seconds;
        }
        if (sec < 10 && sec >= 0) {
            sec = "0" + sec;
        };

        return sec;
    },

    beep: function() {
        var audioContext = new (window.AudioContext || window.webkitAudioContext)();
        var oscillator = audioContext.createOscillator();
        var gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.type = 'triangle'; // You can change the type to 'square', 'sawtooth', 'triangle'
        oscillator.frequency.setValueAtTime(940, audioContext.currentTime); // Frequency in Hz
        gainNode.gain.setValueAtTime(1, audioContext.currentTime); // Volume

        oscillator.start();

        var playPromise = new Promise((resolve) => {
            setTimeout(function() {
                oscillator.stop();
                resolve();
            }, 1000); // Beep duration in milliseconds (5000ms = 5 seconds)
        });

        playPromise.then(() => {
            console.log('Beep sound played successfully');
        }).catch((error) => {
            console.error('Error playing beep sound:', error);
        });
    },

    timeoutScreen: function() {
        var min = jQuery('#screenTimeout').val()
        var khutbahTimeout = jQuery('#khutbahDim').val()
        var taraweehTimeout = jQuery('#taraweehDim').val()
        min = parseInt(min);

        if (khutbahTimeout > 0) {
            min = parseInt(khutbahTimeout); // khutbah is the prayer
        }

        if (taraweehTimeout > 0) {
            min += parseInt(taraweehTimeout); // taraweeh is the additional prayer
        }

        if (min > 0) {
            jQuery("body").append("<div id='overlay' class='iqamah'></div>");
            jQuery('#overlay').animate({
                opacity: 0.9,
            }, (10000), function() {
            });
            setTimeout(function() { jQuery('#overlay').remove(); }, 1000 * 60 * min );
            setTimeout(function() { window.location.reload() }, 1000 * 60 * min );
        } else {
            setTimeout(function() { window.location.reload() }, 1000 * 60 );
        }
    },

    dimMonitorOvernight: function() {
        // dim monitor between isha and fajr
        var canDimOvernight = jQuery('#overnightDim').val()

        if (canDimOvernight == '1') {
            jQuery("body").append("<div id='overlay' class='overnight'></div>");
            jQuery('#overlay').animate({
                    opacity: 0.9,
                }, (1000*60*2), function() {
            });

        }
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
    },

    continiousMarquee: function ()
    {
        var speed = jQuery('#scrollSpeed').val();
        jQuery('.marquee').marquee({
            //speed in milliseconds of the marquee
            duration: parseInt(speed) * 1000,
            //gap in pixels between the tickers
            gap: 50,
            //time in milliseconds before the marquee will start animating
            delayBeforeStart: 0,
            //'left' or 'right'
            direction: 'left',
            //true or false - should the marquee be duplicated to show an effect of continues flow
            duplicated: true
        });
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
            var wpHour = jQuery('#clockHour').val();
            var date = new Date();
            if (wpHour > 12 ) {
                var hours = date.getHours();
            } else {
                var hours = date.getHours() == 0
                ? 12 : date.getHours() > 12
                ? date.getHours() - 12
                : date.getHours();

            }
            jQuery("#hours").html(hours);
            }, 1000);

        setInterval( function() {
            var s = new Date().getSeconds();
            s = (s < 10) ? "0" + s : s;
            var ampm = new Date().getHours() < 12 ? 'AM' : 'PM';
            jQuery("#sec").html(s);
            jQuery("#ampm").html(ampm);
            }, 1000);
    },

    playFajrAdhan: function() 
    {
        var activateAdhan = jQuery('#activateAdhan').val();
        if ( ! activateAdhan ) {
            return;
        }

        var adhan = jQuery('#fajrAdhanTime').val();
        if ( ! adhan ) {
            return;
        }
        adhan = JSON.parse(adhan);
        var timeParts = adhan.split(":");
        DPT.executeFunctionOnTime(timeParts[0], timeParts[1], timeParts[2], function(adhan){
            var audio = new Audio(timetable_params.fajrAdhan);
            var playPromise = audio.play();
            if (playPromise !== undefined) {
                playPromise.then(_ => {
                  // Automatic playback started!
                  // Show playing UI.
                })
                .catch(error => {
                  // Auto-play was prevented
                  // Show paused UI.
                })
            }
        });
    },

    playOtherAdhan: function() 
    {
        var activateAdhan = jQuery('#activateAdhan').val();

        if ( ! activateAdhan ) {
            return;
        }

        var iqamah = jQuery('#otherAdhanTimes').val();
        if ( ! iqamah ) {
            return;
        }
        iqamah = JSON.parse(iqamah);
        for(var i = 0; i < iqamah.length; i ++)
        {
            var timeParts = iqamah[i].split(":");
            DPT.executeFunctionOnTime(timeParts[0], timeParts[1], timeParts[2], function(){
                var audio = new Audio(timetable_params.otherAdhan);
                var playPromise = audio.play();
                if (playPromise !== undefined) {
                    playPromise.then(_ => {
                      // Automatic playback started!
                      // Show playing UI.
                    })
                    .catch(error => {
                      // Auto-play was prevented
                      // Show paused UI.
                    })
                }
            });
        }
    },

    fadingMessages: function(){
        var msg = jQuery('#fadingMessages').val();
        if (! msg ) {
            return;
        }
        msg = JSON.parse(msg);

        fade();
        setInterval(fade, 10000);

        var i = 0;
        function fade() {
            jQuery('.date-eng').fadeOut(5000, function() {
                jQuery('.date-eng').html(msg[i++ % msg.length]);
                jQuery('.date-eng').fadeIn(500)
            });
        }
    },
};
jQuery(document).ready(function() { DPT.init(); });

