<?php
$basicShortcodes = [
    '[monthlytable]', '[dailytable_vertical]', '[dailytable_horizontal]', '[display_ramadan_time]', '[daily_next_prayer]'
];

$prayerTimes = [
    '[fajr_prayer]', '[sunrise]', '[ishraq]', '[zawal]', '[zuhr_prayer]', '[asr_prayer]', '[maghrib_prayer]', '[isha_prayer]', '[jummah_prayer]'
];

$individualTimes = [
    '[fajr_start]', '[zuhr_start]', '[asr_start]', '[maghrib_start]', '[isha_start]'
];

$specialFeatures = [
    '[display_iqamah_update]', '[quran_verse]', '[digital_screen]', '[hijri_date]'
];

$options = [
    ['key' => 'asr', 'value' => 'hanafi', 'desc' => 'Use Hanafi Asr start method'],
    ['key' => 'display', 'value' => 'iqamah_only/azan_only', 'desc' => 'Show only azan or iqamah times'],
    ['key' => 'hide_time_remaining', 'value' => 'true', 'desc' => 'Hide countdown timer'],
    ['key' => 'hide_ramadan', 'value' => 'true', 'desc' => 'Hide Ramadan row'],
    ['key' => 'announcement', 'value' => '"Any text"', 'desc' => 'Show announcement text'],
    ['key' => 'day', 'value' => 'friday', 'desc' => 'Day for announcement: everyday/saturday/.../friday'],
    ['key' => 'heading', 'value' => '"any text"', 'desc' => 'Custom heading'],
    ['key' => 'use_div_layout', 'value' => 'true', 'desc' => 'Simple div layout (horizontal only)'],
    ['key' => 'start_time', 'value' => 'true', 'desc' => 'Show prayer start time (single prayer only)'],
];

$digitalOptions = [
    ['key' => 'view', 'value' => 'vertical/presentation', 'desc' => 'Display mode'],
    ['key' => 'slides', 'value' => 'image1,image2', 'desc' => 'Slide images'],
    ['key' => 'dim', 'value' => 'number', 'desc' => 'Minutes to dim after Jamaat'],
    ['key' => 'scroll', 'value' => 'text', 'desc' => 'Scrolling text'],
    ['key' => 'scroll_link', 'value' => 'url', 'desc' => 'Make scroll text clickable'],
    ['key' => 'blink', 'value' => 'text', 'desc' => 'Blinking text'],
    ['key' => 'blink_link', 'value' => 'url', 'desc' => 'Make blink text clickable'],
    ['key' => 'disable_overnight_dim', 'value' => 'true', 'desc' => 'Disable overnight dimming'],
    ['key' => 'deactivate_tomorrow', 'value' => 'true', 'desc' => 'Hide tomorrow after prayer finishes'],
    ['key' => 'mute_adhan', 'value' => 'true', 'desc' => 'Disable Adhan sound'],
];

$examples = [
    ['[monthlytable]', 'Display yearly and monthly prayer time with ajax month selector'],
    ['[monthlytable display=iqamah_only]', 'Display Iqamah times only'],
    ['[monthlytable display=azan_only]', 'Display Azan times only'],
    ['[monthlytable heading="Månedlige Tidsplan"]', 'Custom heading in any language'],
    ['[dailytable_vertical]', 'Display daily timetable vertically'],
    ['[dailytable_vertical asr=hanafi]', 'Vertical with Hanafi Asr method'],
    ['[dailytable_horizontal]', 'Display daily timetable horizontally'],
    ['[dailytable_horizontal display=iqamah_only]', 'Horizontal with Iqamah times only'],
    ['[dailytable_horizontal asr=hanafi]', 'Horizontal with Hanafi Asr'],
    ['[dailytable_vertical asr=hanafi announcement="First Khutbah: 1:15" day=friday]', 'Announcement on Friday'],
    ['[sunrise]', 'Display sunrise time'],
    ['[isha_prayer]', 'Display Isha prayer time'],
    ['[fajr_prayer start_time=true]', 'Display fajr with start time'],
    ['[daily_next_prayer]', 'Display only next prayer'],
    ['[digital_screen]', 'Display on big monitors'],
    ['[digital_screen view=vertical]', 'Portrait mode'],
    ['[digital_screen dim=5]', 'Dim after 5 minutes'],
    ['[quran_verse min_word=20 max_word=30 language=bangla]', 'Quran verse in Bengali'],
];
?>
<style>
.dpt-help-accordion { margin-bottom: 10px; }
.dpt-help-accordion .accordion-header { 
    background: #2271b1; color: #fff; padding: 12px 15px; cursor: pointer; 
    border-radius: 4px; display: flex; justify-content: space-between; align-items: center;
}
.dpt-help-accordion .accordion-header:hover { background: #1d5a8a; }
.dpt-help-accordion .accordion-header:after { content: '▼'; font-size: 10px; transition: transform 0.3s; }
.dpt-help-accordion.active .accordion-header:after { transform: rotate(180deg); }
.dpt-help-accordion .accordion-content { display: none; padding: 15px; border: 1px solid #ddd; border-top: none; }
.dpt-help-accordion.active .accordion-content { display: block; }
.dpt-shortcode-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; margin-bottom: 15px; }
.dpt-shortcode-card { 
    background: #f6f7f7; border: 1px solid #dcdcde; padding: 10px 12px; border-radius: 4px; 
    font-family: monospace; font-size: 13px; cursor: pointer; transition: background 0.2s; position: relative;
}
.dpt-shortcode-card:hover { background: #e6e6e6; border-color: #2271b1; }
.dpt-shortcode-card.copied { background: #d1e7dd; border-color: #0f5132; }
.dpt-option-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 12px; }
.dpt-option-card { background: #f6f7f7; border: 1px solid #dcdcde; padding: 10px; border-radius: 4px; }
.dpt-option-card code { background: #fff; padding: 2px 6px; border-radius: 3px; font-size: 12px; }
.dpt-example-list { list-style: none; padding: 0; }
.dpt-example-list li { padding: 8px 0; border-bottom: 1px solid #eee; }
.dpt-example-list li:last-child { border-bottom: none; }
.dpt-example-list code { background: #f6f7f7; padding: 2px 6px; border-radius: 3px; font-size: 13px; }
.dpt-example-list .desc { color: #666; font-size: 12px; display: block; margin-top: 4px; }
.dpt-tip { background: #fff8c5; border-left: 4px solid #f0c33c; padding: 10px 15px; margin-bottom: 20px; font-size: 14px; }
.dpt-tip a { color: #2271b1; }
</style>

<p class="dpt-tip">
    <span class="red">! Important !</span> Please <a href="plugins.php">re-activate</a> the plugin if your data is not imported
</p>

<h2>Helps and Tips</h2>

<div id="dpt-help-accordions">

    <!-- Basic Shortcodes -->
    <div class="dpt-help-accordion active">
        <div class="accordion-header" onclick="toggleHelpAccordion(this)">Basic Shortcodes</div>
        <div class="accordion-content">
            <p>Most commonly used shortcodes for displaying prayer times:</p>
            <div class="dpt-shortcode-grid">
                <?php foreach ($basicShortcodes as $sc): ?>
                    <div class="dpt-shortcode-card" onclick="copyShortcode(this, '<?php echo esc_attr($sc); ?>')"><?php echo esc_html($sc); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Prayer Times -->
    <div class="dpt-help-accordion">
        <div class="accordion-header" onclick="toggleHelpAccordion(this)">Prayer Times</div>
        <div class="accordion-content">
            <p>Display individual prayer times with Iqamah:</p>
            <div class="dpt-shortcode-grid">
                <?php foreach ($prayerTimes as $sc): ?>
                    <div class="dpt-shortcode-card" onclick="copyShortcode(this, '<?php echo esc_attr($sc); ?>')"><?php echo esc_html($sc); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Individual Times -->
    <div class="dpt-help-accordion">
        <div class="accordion-header" onclick="toggleHelpAccordion(this)">Individual Times (Start/Jamah)</div>
        <div class="accordion-content">
            <p>Display only start time or jamah time for each prayer:</p>
            <div class="dpt-shortcode-grid">
                <?php foreach ($individualTimes as $sc): ?>
                    <div class="dpt-shortcode-card" onclick="copyShortcode(this, '<?php echo esc_attr($sc); ?>')"><?php echo esc_html($sc); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Special Features -->
    <div class="dpt-help-accordion">
        <div class="accordion-header" onclick="toggleHelpAccordion(this)">Special Features</div>
        <div class="accordion-content">
            <p>Additional features like Quran verse, digital screen, etc:</p>
            <div class="dpt-shortcode-grid">
                <?php foreach ($specialFeatures as $sc): ?>
                    <div class="dpt-shortcode-card" onclick="copyShortcode(this, '<?php echo esc_attr($sc); ?>')"><?php echo esc_html($sc); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Options Reference -->
    <div class="dpt-help-accordion">
        <div class="accordion-header" onclick="toggleHelpAccordion(this)">Shortcode Options Reference</div>
        <div class="accordion-content">
            <p>Add these options to any shortcode (e.g., [dailytable_horizontal asr=hanafi]):</p>
            <div class="dpt-option-grid">
                <?php foreach ($options as $opt): ?>
                    <div class="dpt-option-card">
                        <code><?php echo esc_html($opt['key']); ?>=<?php echo esc_html($opt['value']); ?></code>
                        <br><small><?php echo esc_html($opt['desc']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
            <p style="margin-top: 15px;"><strong>Digital Screen Options:</strong></p>
            <div class="dpt-option-grid">
                <?php foreach ($digitalOptions as $opt): ?>
                    <div class="dpt-option-card">
                        <code><?php echo esc_html($opt['key']); ?>=<?php echo esc_html($opt['value']); ?></code>
                        <br><small><?php echo esc_html($opt['desc']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Examples -->
    <div class="dpt-help-accordion">
        <div class="accordion-header" onclick="toggleHelpAccordion(this)">Practical Examples</div>
        <div class="accordion-content">
            <p>Click any example to copy it:</p>
            <ul class="dpt-example-list">
                <?php foreach ($examples as $ex): ?>
                    <li>
                        <code onclick="copyShortcode(this, '<?php echo esc_attr($ex[0]); ?>')" style="cursor:pointer;"><?php echo esc_html($ex[0]); ?></code>
                        <span class="desc"><?php echo esc_html($ex[1]); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p><a href="https://wordpress.org/plugins/daily-prayer-time-for-mosques/screenshots/" target="_new">View screenshots <i class="fa fa-external-link" aria-hidden="true"></i></a></p>
        </div>
    </div>

    <!-- Ramadan & Hijri -->
    <div class="dpt-help-accordion">
        <div class="accordion-header" onclick="toggleHelpAccordion(this)">How To: Ramadan & Custom Hijri</div>
        <div class="accordion-content">
            <p><strong>Update Ramadan timetable:</strong><br>Put '1' for column (is_ramadan) in the sample CSV for days belonging to Ramadan before upload.</p>
            <p><strong>Use custom Hijri date:</strong><br>Insert your own calculated Hijri date in the CSV column (hijri_date) and enable visibility from settings.</p>
        </div>
    </div>

</div>

<script>
function toggleHelpAccordion(header) {
    header.parentElement.classList.toggle('active');
}

function copyShortcode(el, text) {
    navigator.clipboard.writeText(text).then(function() {
        var original = el.innerText;
        el.classList.add('copied');
        el.innerText = 'Copied!';
        setTimeout(function() {
            el.classList.remove('copied');
            el.innerText = original;
        }, 1500);
    });
}
</script>