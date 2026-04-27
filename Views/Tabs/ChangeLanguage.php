<?php
require_once(__DIR__ . '/../TimetablePrinter.php');
$timetable = new TimetablePrinter();
$names = $timetable->getLocalPrayerNames();
$months = $timetable->getLocalMonths();
$headers = $timetable->getLocalHeaders();
$numbers = $timetable->getLocalNumbers();
$timesKeys = $timetable->getLocalTimesKeys();
$timesValues = array_values($timetable->getLocalTimes());
$times = array_combine($timesKeys, $timesValues);

$presets = [
    'English' => [
        'prayersLocal' => ['fajr' => 'Fajr', 'sunrise' => 'Sunrise', 'ishraq' => 'Ishraq', 'zuhr' => 'Zuhr', 'asr' => 'Asr', 'maghrib' => 'Maghrib', 'isha' => 'Isha', 'zawal' => 'Zawal'],
        'monthsLocal' => ['january' => 'January', 'february' => 'February', 'march' => 'March', 'april' => 'April', 'may' => 'May', 'june' => 'June', 'july' => 'July', 'august' => 'August', 'september' => 'September', 'october' => 'October', 'november' => 'November', 'december' => 'December'],
        'headersLocal' => ['prayer' => 'Prayer', 'begins' => 'Begins', 'iqamah' => 'Iqamah', 'standard' => 'Standard', 'hanafi' => 'Hanafi', 'fast_begins' => 'Suhoor End', 'fast_ends' => 'Iftar Start', 'jumuah' => 'Jumuah'],
    ],
    'Arabic' => [
        'prayersLocal' => ['fajr' => 'الفجر', 'sunrise' => 'الشروق', 'ishraq' => 'الاشراق', 'zuhr' => 'الظهر', 'asr' => 'العصر', 'maghrib' => 'المغرب', 'isha' => 'العشاء', 'zawal' => 'الزوال'],
        'monthsLocal' => ['january' => 'يناير', 'february' => 'فبراير', 'march' => 'مارس', 'april' => 'أبريل', 'may' => 'مايو', 'june' => 'يونيو', 'july' => 'يوليو', 'august' => 'أغسطس', 'september' => 'سبتمبر', 'october' => 'أكتوبر', 'november' => 'نوفمبر', 'december' => 'ديسمبر'],
        'headersLocal' => ['prayer' => 'الصلاة', 'begins' => 'البدء', 'iqamah' => 'الإقامة', 'standard' => 'قياسي', 'hanafi' => 'حنفي', 'fast_begins' => 'السحور', 'fast_ends' => 'الإفطار', 'jumuah' => 'الجمعة'],
    ],
    'Urdu' => [
        'prayersLocal' => ['fajr' => 'فجر', 'sunrise' => 'سورج', 'ishraq' => 'اشراق', 'zuhr' => 'ظہر', 'asr' => 'عصر', 'maghrib' => 'مغرب', 'isha' => 'عشاء', 'zawal' => 'ظول'],
        'monthsLocal' => ['january' => 'جنوری', 'february' => 'فروری', 'march' => 'مارچ', 'april' => 'اپریل', 'may' => 'مئی', 'june' => 'جون', 'july' => 'جولائی', 'august' => 'اگست', 'september' => 'ستمبر', 'october' => 'اکتوبر', 'november' => 'نومبر', 'december' => 'دسمبر'],
        'headersLocal' => ['prayer' => 'نماز', 'begins' => 'شروع', 'iqamah' => 'اقامت', 'standard' => 'Standard', 'hanafi' => 'حنفی', 'fast_begins' => 'سحری', 'fast_ends' => 'افطار', 'jumuah' => 'جمعہ'],
    ],
    'Turkish' => [
        'prayersLocal' => ['fajr' => 'Sabah', 'sunrise' => 'Güneş', 'ishraq' => 'Işrak', 'zuhr' => 'Öğle', 'asr' => 'İkindi', 'maghrib' => 'Akşam', 'isha' => 'Yatsı', 'zawal' => 'Zeval'],
        'monthsLocal' => ['january' => 'Ocak', 'february' => 'Şubat', 'march' => 'Mart', 'april' => 'Nisan', 'may' => 'Mayıs', 'june' => 'Haziran', 'july' => 'Temmuz', 'august' => 'Ağustos', 'september' => 'Eylül', 'october' => 'Ekim', 'november' => 'Kasım', 'december' => 'Aralık'],
        'headersLocal' => ['prayer' => 'Namaz', 'begins' => 'Başlangıç', 'iqamah' => 'İkamet', 'standard' => 'Standart', 'hanafi' => 'Hanefi', 'fast_begins' => 'İmsak', 'fast_ends' => 'İftar', 'jumuah' => 'Cuma'],
    ],
    'Bengali' => [
        'prayersLocal' => ['fajr' => 'ফজর', 'sunrise' => 'সূর্যোদয়', 'ishraq' => 'ঈশারাক', 'zuhr' => 'যোহর', 'asr' => 'আসর', 'maghrib' => 'মাগরিব', 'isha' => 'এশা', 'zawal' => 'যাওয়াল'],
        'monthsLocal' => ['january' => 'জানুয়ারি', 'february' => 'ফেব্রুয়ারি', 'march' => 'মার্চ', 'april' => 'এপ্রিল', 'may' => 'মে', 'june' => 'জুন', 'july' => 'জুলাই', 'august' => 'আগস্ট', 'september' => 'সেপ্টেম্বর', 'october' => 'অক্টোবর', 'november' => 'নভেম্বর', 'december' => 'ডিসেম্বর'],
        'headersLocal' => ['prayer' => 'নামাজ', 'begins' => 'শুরু', 'iqamah' => 'ইকামত', 'standard' => 'সাধারণ', 'hanafi' => 'হানাফি', 'fast_begins' => 'সাহারী', 'fast_ends' => 'ইফতার', 'jumuah' => 'জুমা'],
    ],
];
?>
<style>
.dpt-lang-accordion { margin-bottom: 10px; }
.dpt-lang-accordion .accordion-header { 
    background: #2271b1; color: #fff; padding: 12px 15px; cursor: pointer; 
    border-radius: 4px; display: flex; justify-content: space-between; align-items: center;
}
.dpt-lang-accordion .accordion-header:hover { background: #1d5a8a; }
.dpt-lang-accordion .accordion-header:after { content: '▼'; font-size: 10px; transition: transform 0.3s; }
.dpt-lang-accordion.active .accordion-header:after { transform: rotate(180deg); }
.dpt-lang-accordion .accordion-content { display: none; padding: 15px; border: 1px solid #ddd; border-top: none; }
.dpt-lang-accordion.active .accordion-content { display: block; }
.dpt-lang-presets { margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
.dpt-lang-presets button { 
    padding: 8px 16px; border: 1px solid #2271b1; background: #fff; color: #2271b1; 
    border-radius: 4px; cursor: pointer; font-weight: 500;
}
.dpt-lang-presets button:hover { background: #2271b1; color: #fff; }
.dpt-lang-inputs { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; }
.dpt-lang-inputs .input-group { display: flex; flex-direction: column; }
.dpt-lang-inputs .input-group label { font-weight: 600; margin-bottom: 5px; font-size: 13px; }
.dpt-lang-inputs .input-group input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
.dpt-lang-inputs.narrow { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); }
</style>

<h3>Change Language</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <form name="languageSettings" method="post">
            <?php echo wp_nonce_field( 'languageSettings'); ?>

                <p><strong>Quick Presets:</strong></p>
                <div class="dpt-lang-presets">
                    <?php foreach ($presets as $name => $data): ?>
                        <button type="button" onclick="applyPreset('<?php echo esc_attr($name); ?>', <?php echo htmlspecialchars(json_encode($data['prayersLocal'])); ?>, <?php echo htmlspecialchars(json_encode($data['monthsLocal'])); ?>, <?php echo htmlspecialchars(json_encode($data['headersLocal'])); ?>)"><?php echo esc_html($name); ?></button>
                    <?php endforeach; ?>
                </div>

                <div id="dpt-lang-accordions">
                    <!-- Prayer Names -->
                    <div class="dpt-lang-accordion">
                        <div class="accordion-header" onclick="toggleAccordion(this)">Display prayer name in your language</div>
                        <div class="accordion-content">
                            <div class="dpt-lang-inputs">
                                <?php foreach ($names as $key => $val): ?>
                                    <div class="input-group">
                                        <label><?php echo esc_html(ucfirst($key)); ?></label>
                                        <input type="text" name="prayersLocal[<?php echo esc_attr($key); ?>]" value="<?php echo stripslashes(esc_attr($val)); ?>" />
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Month Names -->
                    <div class="dpt-lang-accordion">
                        <div class="accordion-header" onclick="toggleAccordion(this)">Translate month name in your own language</div>
                        <div class="accordion-content">
                            <div class="dpt-lang-inputs narrow">
                                <?php foreach ($months as $key => $val): ?>
                                    <div class="input-group">
                                        <label><?php echo esc_html(ucfirst($key)); ?></label>
                                        <input type="text" name="monthsLocal[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($val); ?>" />
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Table Headings -->
                    <div class="dpt-lang-accordion">
                        <div class="accordion-header" onclick="toggleAccordion(this)">Other table headings in your language</div>
                        <div class="accordion-content">
                            <div class="dpt-lang-inputs">
                                <?php foreach ($headers as $key => $val): ?>
                                    <div class="input-group">
                                        <label><?php echo esc_html(ucfirst($key)); ?></label>
                                        <input type="text" name="headersLocal[<?php echo esc_attr($key); ?>]" value="<?php echo stripslashes(esc_attr($val)); ?>" />
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Numbers -->
                    <div class="dpt-lang-accordion">
                        <div class="accordion-header" onclick="toggleAccordion(this)">Numbers in your language</div>
                        <div class="accordion-content">
                            <div class="dpt-lang-inputs narrow">
                                <?php foreach ($numbers as $key => $val): ?>
                                    <div class="input-group">
                                        <label><?php echo esc_html($key); ?></label>
                                        <input type="text" maxlength="1" name="numbersLocal[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($val); ?>" style="width: 60px;" />
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Time Values -->
                    <div class="dpt-lang-accordion">
                        <div class="accordion-header" onclick="toggleAccordion(this)">Time related values</div>
                        <div class="accordion-content">
                            <div class="dpt-lang-inputs">
                                <?php foreach ($times as $key => $val): ?>
                                    <div class="input-group">
                                        <label><?php echo esc_html(ucfirst($key)); ?></label>
                                        <input type="text" name="timesLocal[<?php echo esc_attr($key); ?>]" value="<?php echo stripslashes(esc_attr($val)); ?>" />
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <p>&nbsp;</p>
                <div class="saveButton">
                    <?php submit_button('Save changes', 'primary', 'languageSettings'); ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleAccordion(header) {
    header.parentElement.classList.toggle('active');
}

function applyPreset(name, prayers, months, headers) {
    if (confirm('Apply ' + name + ' preset? This will overwrite your current prayer names, months and table headings.')) {
        for (var key in prayers) {
            var input = document.querySelector('input[name="prayersLocal[' + key + ']"]');
            if (input) input.value = prayers[key];
        }
        for (var key in months) {
            var input = document.querySelector('input[name="monthsLocal[' + key + ']"]');
            if (input) input.value = months[key];
        }
        for (var key in headers) {
            var input = document.querySelector('input[name="headersLocal[' + key + ']"]');
            if (input) input.value = headers[key];
        }
    }
}
</script>