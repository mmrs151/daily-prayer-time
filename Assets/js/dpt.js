DPT = {
    isFirstMin: true,
    diffMin: 59,
    audioContext: null,

    init: function() {
        this.enableAudio();
        // console.log(timetable_params);
        this.monthlyCalendarChange();

        this.changeInputBackground();
        this.printDiv();
        this.dsRefreshNextPrayer();
        this.refreshBeforeIqamah();
        this.continiousMarquee();
        this.digitalClock();

        this.dimMonitorOvernight();
        this.dsRefreshQuranVerse();

        this.playFajrAdhan();
        this.playOtherAdhan();

        this.keepScreenOn();
        this.fadingMessages();

        this.updateTimeDifference();
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
                    setTimeout(DPT.dsRefreshNextPrayer, (1000 * 60 * 15) ); // 15 minutes
                    jQuery('.dsNextPrayer').html(response);

                },
                error: function(responseObj, strError){
                    console.log(strError);
                },
                timeout: (1000 * 30) // 30 seconds
            });
        }
    },


    dsRefreshQuranVerse: function () {

        if ( ! jQuery('#quranCheckbox').val() ) {
            return;
        }

            // Your code here
        jQuery.ajax({
            url: timetable_params.ajaxurl,
            data: {
                'action': 'get_ds_quran_verse',
            },
            success: function (response) {
                console.log(response);
                setTimeout(DPT.dsRefreshQuranVerse, (1000 * 30));
                jQuery('#quranVerse').html(response);
            },
            error: function (responseObj, strError) {
                console.log(strError);
            },
            timeout: (1000 * 30) // 30 seconds
        });
    },

    beep: function() {
        var activateBeep = jQuery('#activateBeep').val();
        if (!activateBeep) {
            return;
        }

        if (!DPT.audioContext) {
            console.error('AudioContext is not initialized. Please enable audio first.');
            return;
        }

        var oscillator = DPT.audioContext.createOscillator();
        var gainNode = DPT.audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(DPT.audioContext.destination);

        oscillator.type = 'triangle'; // You can change the type to 'square', 'sawtooth', 'triangle'
        oscillator.frequency.setValueAtTime(940, DPT.audioContext.currentTime); // Frequency in Hz
        gainNode.gain.setValueAtTime(1, DPT.audioContext.currentTime); // Volume

        oscillator.start();

        setTimeout(function() {
            oscillator.stop();
        }, 1000); // Beep duration in milliseconds (1000ms = 1 second)
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

    digitalClock: function ()
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

    enableAudio: function() {
        var activateAdhan = jQuery('#activateAdhan').val();
        var activateBeep = jQuery('#activateBeep').val();
        if (!activateAdhan && !activateBeep) {
            return;
        }

        if (localStorage.getItem('audioEnabled') === 'true') {
            this.initializeAudioContext();
            return;
        }


        // Create a button element for user interaction
        var button = document.createElement('button');
        button.innerHTML = 'Enable Audio';
        button.id = 'enableAudioButton';
        document.body.appendChild(button);
        button.style.position = 'fixed';
        button.style.top = '50%';
        button.style.left = '50%';
        button.style.transform = 'translateX(-50%)';
        button.style.zIndex = '1000';

        // Add click event listener to the button
        button.addEventListener('click', function() {
            DPT.initializeAudioContext();
            localStorage.setItem('audioEnabled', 'true'); // Store the flag in localStorage
            alert('Audio enabled. The adhan will play at the specified times.');
            button.style.display = 'none'; // Hide the button after enabling audio
        });

    },

    initializeAudioContext: function() {
        if (!this.audioContext) {
            this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (this.audioContext.state === 'suspended') {
            this.audioContext.resume();
        }
    },

    playFajrAdhan: function() {
        var adhan = jQuery('#fajrAdhanTime').val();
        if (!adhan) {
            return;
        }

        var timeParts = adhan.split(":");
        var adhanUrl = timetable_params.fajrAdhanUrl;

        DPT.executeFunctionOnTime(timeParts[0], timeParts[1], timeParts[2], function() {
            console.log('Playing Fajr adhan: ' + adhanUrl);
            DPT.playAudio(adhanUrl);
        });
    },

    playOtherAdhan: function() {
        var iqamah = jQuery('#otherAdhanTimes').val();
        if (!iqamah) {
            return;
        }
        iqamah = JSON.parse(iqamah);

        for (var i = 0; i < iqamah.length; i++) {
            var timeParts = iqamah[i].split(":");
            var adhanUrl = timetable_params.otherAdhanUrl;
            DPT.executeFunctionOnTime(timeParts[0], timeParts[1], timeParts[2], function() {
                console.log('Playing Adhan: ' + adhanUrl);
                DPT.playAudio(adhanUrl);
            });
        }
    },

    playAudio: function(url) {
        var activateAdhan = jQuery('#activateAdhan').val();
        if (!activateAdhan) {
            return;
        }

        var audioContext = new (window.AudioContext || window.webkitAudioContext)();
        var source = audioContext.createBufferSource();
        var request = new XMLHttpRequest();

        request.open('GET', url, true);
        request.responseType = 'arraybuffer';

        request.onload = function() {
            audioContext.decodeAudioData(request.response, function(buffer) {
                source.buffer = buffer;
                source.connect(audioContext.destination);
                source.start(0);
            }, function(error) {
                console.error('Error decoding audio data:', error);
            });
        };

        request.send();
    },

    fadingMessages: function(){
        var msg = jQuery('#fadingMessages').val();

        if (!msg) {
            return;
        }
        msg = JSON.parse(msg.trim());
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

    updateTimeDifference: function () {

        setInterval(() => updateTimeDifferenceInterval(), 1000);

        function updateTimeDifferenceInterval() {
            var timezoneOffset = parseFloat(document.getElementById('timezoneOffset').value) * 60; // Convert hours to minutes
            var now = new Date();
            now.setMinutes(now.getMinutes() + now.getTimezoneOffset() + timezoneOffset); // Adjust to WordPress timezone

            var dptScTimeValue = jQuery('#dptScTimeCountDown').text().trim()
            var timeParts = dptScTimeValue.split(':');
            var hours = parseInt(timeParts[0]);
            var minutes = parseInt(timeParts[1]);

            var targetTime = new Date();
            targetTime.setHours(hours, minutes, 0, 0);
            var timeDifference = targetTime - now;

            // If the target time is in the past, add 24 hours to it
            if (timeDifference < 0) {
                return;
            }

            // Convert time difference to hours, minutes, and seconds
            var diffHours = Math.floor(timeDifference / (1000 * 60 * 60));
            var diffMinutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
            var diffSeconds = Math.floor((timeDifference % (1000 * 60)) / 1000) + 1; // Add 1 second to prevent showing 0 seconds

            var timeDifferenceText;
            hourText = "hour" + (diffHours > 1 ? "s" : "");
            minuteText = "minute" + (diffMinutes > 1 ? "s" : "");
            secondText = "second";
            if (diffHours > 0) {
                timeDifferenceText = diffHours + " " + DPT.getLocalizedTime(hourText) + " " + diffMinutes + " " + DPT.getLocalizedTime(minuteText);
            } else if (diffMinutes > 0) {
                timeDifferenceText = diffMinutes + " " + DPT.getLocalizedTime(minuteText) + " " + diffSeconds + " " + DPT.getLocalizedTime(secondText);
            } else {
                timeDifferenceText = diffSeconds + " " + DPT.getLocalizedTime(secondText);
            }

            timeDifferenceText = DPT.getLocalizedNumber(timeDifferenceText);
            var timeLeftCountDownElements = document.getElementsByClassName('timeLeftCountDown');
            for (var i = 0; i < timeLeftCountDownElements.length; i++) {
                document.getElementsByClassName('timeLeftCountDown')[i].innerHTML = timeDifferenceText;

                timeLeftCountDownElements[i].classList.remove('green', 'orange', 'red');

                // Add appropriate class based on diffMinutes
                if (diffHours < 1) {
                    if (diffMinutes >= 15 && diffMinutes <= 29) {
                        timeLeftCountDownElements[i].classList.add('orange');
                    } else if (diffMinutes < 15) {
                        timeLeftCountDownElements[i].classList.add('red');
                    } else {
                        timeLeftCountDownElements[i].classList.add('green');
                    }
                }
            }

            if (diffHours == 0 && diffMinutes == 0 && diffSeconds == 1) {
                DPT.beep();
                DPT.timeoutScreen();
            }
        }
    },

    getLocalizedNumber: function (numbers) {
        var localNumbers  = jQuery('#localizedNumbers').val();
        if (! localNumbers) {
            return numbers;
        }
        localNumbers = JSON.parse(localNumbers);
        return numbers.split('').map(function(char) {
            return localNumbers[char] || char;
        }).join('');
    },

    getLocalizedTime: function (time) {
        var localTimes  = jQuery('#localizedTimes').val();
        if (! localTimes) {
            return time;
        }
        localTimes = JSON.parse(localTimes);
        return localTimes[time] || '';
    }
};
jQuery(document).ready(function() { DPT.init(); });

