<h3>API Documentation</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <span>The REST API Route depends on your Permalink setting. <a target="_blank" href="https://wordpress.org/support/article/settings-permalinks-screen/"><strong>Click to see how to set your permalink</strong></a>.
                <li>With 'Custom' permalink the route will be: <code><?php echo get_site_url(); ?>/wp-json/dpt/v1/prayertime?</code></li>
                <li>With 'Plain' permalink the route will be: <code><?php echo get_site_url(); ?>/?rest_route=/dpt/v1/prayertime&</code></li>
                <li>(<cite>Please note the <code>?</code> OR <code>&</code> symoble at the end of the URL</cite>)</li>
             </span>
            <p>&nbsp;</p>
            <span>
                URL Parameters:
                <li><code><?php echo get_site_url(); ?>/?rest_route=/dpt/v1/prayertime&filter=today</code>: will Return prayer time for today</li>
                <li><code>filter=month</code>: will Return prayer time for the month</li>
                <li><code>filter=year</code>: will Return prayer time whole year</li>
                <li><code>filter=ramadan</code>: will Return prayer time for Ramadaan</li>
                <li><code>filter=iqamah</code>: will Return Iqamah time for today</li>
                <li><code>filter=tomorrow_fajr</code>: will Return next day Fajr prayer time</li>
                <li><code>filter=iqamah_changes</code>: will Return the upcoming Iqamah time changes for tomorrow</li>
            </span>
            <span>
                Sample output for the <code>today</code> filter:
<pre>
{
  d_date: "2022-11-29",
  fajr_begins: "05:44:00",
  fajr_jamah: "06:30:00",
  sunrise: "07:28:00",
  zuhr_begins: "12:00:00",
  zuhr_jamah: "12:30:00",
  asr_mithl_1: "14:48:00",
  asr_mithl_2: "14:48:00",
  asr_jamah: "15:00:00",
  maghrib_begins: "16:33:00",
  maghrib_jamah: "16:33:00",
  isha_begins: "17:59:00",
  isha_jamah: "22:45:00",
  is_ramadan: "0",
  hijri_date: "Jumada Al-Awwal 4, 1444",

  jamah_changes: {
    maghrib_jamah: "16:32:00",
    isha_jamah: "19:30:00"
  },

  tomorrow: {
    d_date: "2022-11-30",
    fajr_begins: "05:45:00",
    fajr_jamah: "06:30:00",
    sunrise: "07:29:00",
    zuhr_begins: "12:01:00",
    zuhr_jamah: "12:30:00",
    asr_mithl_1: "14:47:00",
    asr_mithl_2: "14:47:00",
    asr_jamah: "15:00:00",
    maghrib_begins: "16:32:00",
    maghrib_jamah: "16:32:00",
    isha_begins: "17:59:00",
    isha_jamah: "19:30:00",
    is_ramadan: "0",
    hijri_date: "Jumada Al-Awwal 5, 1444"
  },

  hijri_date_convert: "6 Jumādā al-Ula 1444",
  jumuah: "1:30, 2:15, 2:45",
  
  next_prayer: {
    prayerName: "isha",
    timeLeft: 11
  }
}
</pre>
            </span>
        </div>
    </div>
</div>