<p class="green" style="font-size:20px;">
    <span class="red">! important !</span> Please
    <a href="plugins.php"> re-activate</a>
    the plugin if your data is not imported
</p>

<h2>Helps and Tips</h2>
<p>
    <h3><u>Shortcodes:</u></h3>
    <ol>
        <li>[monthlytable]</li>
        <li>[dailytable_vertical]</li>
        <li>[dailytable_horizontal]</li>
        <li>[display_ramadan_time] <i>(no shortcode options)</i></li>
        <li>[daily_next_prayer] <i>display_dates=true</i></li>
        <li>[fajr_prayer]</li>
        <li>[sunrise]</li>
        <li>[zuhr_prayer]</li>
        <li>[asr_prayer]</li>
        <li>[maghrib_prayer]</li>
        <li>[isha_prayer]</li>
        <li>[fajr_start]</li>
        <li>[zuhr_start]</li>
        <li>[asr_start]</li>
        <li>[maghrib_start]</li>
        <li>[isha_start]</li>
        <li>[digital_screen]</li>
        <li>[display_iqamah_update] <i>threshold={min}</i></li>
    </ol>

    <h3>Shortcode Options:</h3>
    <ol>
        <li>asr=hanafi</li>
        <li>display=iqamah_only/azan_only</li>
        <li>hide_time_remaining=true</li>
        <li>hide_ramadan=true</li>
        <li>announcement="Any text" day=everyday/saturday/sunday/monday/tuesday/wednesday/thursday/friday</li>
        <li>heading="any text"</li>
        <li>use_div_layout=true (restrictive, works with horizontal, no other options)</li>
        <li>start_time=true (only for single prayer time)</li>
        <li>view='vertical' (only for digital_screen)</li>
    </ol>
</p>
<p>
    <h3>shortcodes examples</h3>
    <ol>

        <li><b>[sunrise]</b> - Display sunrise for the day, use span class dpt_sunrise to decorate your view</li>
        <li><b>[isha_prayer]</b> - Display Isha prayer time, use span class dpt_jamah to write your css for jamah time</li>
        <li><b>[fajr_prayer start_time=true]</b> - Display fajr prayer with start time, use span class dpt_start to design your css for start time</li>
        <li><b>[daily_next_prayer]</b> - Display only next prayer on post or page</li>
        <li><b>[monthlytable]</b> - Display Yearly and Monthly prayer time with ajax month selector</li>
        <li><b>[monthlytable display=iqamah_only]</b> - Display Iqamah only for Yearly and Monthly prayer time with ajax month selector</li>
        <li><b>[monthlytable display=azan_only]</b> - Display monthly time table heading in any language, default is 'Monthly Time Table for'</li>
        <li><b>[monthlytable heading="Månedlige Tidsplan"]</b> - Display monthly time table heading in any language, default is 'Monthly Time Table for'</li>
        <li><b>[monthlytable heading="Månedlige Tidsplan" display=azan_only]</b> - Please notice the use of " " while using multiple words in a shortcode option</li>
        <li><b>[dailytable_vertical]</b> - Display daily timetable vertically</li>
        <li><b>[dailytable_vertical asr=hanafi]</b> - Display daily timetable vertically with Hanafi Asr start method</li>
        <li><b>[dailytable_horizontal]</b> - Display daily timetable horizontally</li>
        <li><b>[dailytable_horizontal display=iqamah_only]</b> - Display daily azan only timetable horizontally</li>
        <li><b>[dailytable_horizontal asr=hanafi]</b> - Display daily timetable horizontally with Hanafi Asr start method</li>
        <li><b>[dailytable_horizontal asr=hanafi display=azan_only]</b> - Display daily iqamah only timetable horizontally with Hanafi Asr start method</li>
        <li><b>[dailytable_vertical asr=hanafi announcement="First Khutbah: 1:15. Second Khutbah: 1:45" day=friday]</b> - Display announcement on your given day or everyday</li>
        <li><b>[digital_screen]</b> - Display prayer time on big monitors in the masjid</li>
        <li><b>[digital_screen view='vertical']</b> - Display prayer time in Portrait mode</li>

    </ol>
        <a href="https://wordpress.org/plugins/daily-prayer-time-for-mosques/screenshots/" target="_new">Please check the screen shots <i class="fa fa-external-link" aria-hidden="true"></i></a>
</p>
<p>
    <h3><u>How to update ramadan timetable</u></h3>
    Simply put '1' for the column(is_ramadan) in the sample csv for the days belongs to ramadan before upload
</p>
<p>
    <h3><u>How to use custom hijri date</u></h3>
    Insert your own calculated Hijri date in the csv column(hijri_date) and allow visibility from settings
</p>

