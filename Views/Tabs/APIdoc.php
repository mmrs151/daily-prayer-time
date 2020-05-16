<h3>API Documentation</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <span>The REST API Route depends on your Permalink setting. <a target="_blank" href="https://wordpress.org/support/article/settings-permalinks-screen/"><strong>Click to see how to set your permalink</strong></a>.
                <li>With 'Custom' permalink the route will be: <code>http://your-masjid.com/wp-json/dpt/v1/prayertime?</code></li>
                <li>Without 'Plain' permalink the route will be: <code>http://your-masjid.com/?rest_route=/dpt/v1/prayertime&</code></li>
                <li>(<cite>Please note the <code>?</code> OR <code>&</code> symoble at the end of the URL</cite>)</li>
             </span>
            <p>&nbsp;</p>
            <span>
                URL Parameters:
                <li><code>filter=today</code>: will Return prayer time for today</li>
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
    "d_date": "2020-05-16",
    "fajr_begins": "02:51:00",
    "fajr_jamah": "03:51:00",
    "sunrise": "05:07:00",
    "zuhr_begins": "12:57:00",
    "zuhr_jamah": "14:27:00",
    "asr_mithl_1": "17:10:00",
    "asr_mithl_2": "17:10:00",
    "asr_jamah": "17:55:00",
    "maghrib_begins": "20:48:00",
    "maghrib_jamah": "20:53:00",
    "isha_begins": "23:04:00",
    "isha_jamah": "23:19:00",
    "is_ramadan": "0",
    "hijri_date": "0",
    "jamah_changes": null
}
</pre>
            </span>
        </div>
    </div>
</div>