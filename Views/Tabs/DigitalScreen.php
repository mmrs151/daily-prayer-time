<?php
function displayImage($slide){
    if (filter_var($slide, FILTER_VALIDATE_URL)) {
        return  '<img src="' . esc_html($slide ) . '" style="max-height: 30px;" class="grow">';
    }
    return '';
}
$maxSliders = 7;
$existingSliders = 0;
for ($i = 1; $i <= 11; $i++) {
    if (!empty(get_option("slider$i"))) {
        $existingSliders = max($existingSliders, $i);
    }
}
$displayCount = max(1, min($existingSliders, $maxSliders));
$isSliderActive = get_option("slider-chbox") === 'slider';
?>
<style>
.dpt-ds-accordion { margin-bottom: 10px; }
.dpt-ds-accordion .accordion-header { 
    background: #2271b1; color: #fff; padding: 12px 15px; cursor: pointer; 
    border-radius: 4px; display: flex; justify-content: space-between; align-items: center;
}
.dpt-ds-accordion .accordion-header:hover { background: #1d5a8a; }
.dpt-ds-accordion .accordion-header:after { content: '▼'; font-size: 10px; transition: transform 0.3s; }
.dpt-ds-accordion.active .accordion-header:after { transform: rotate(180deg); }
.dpt-ds-accordion .accordion-content { display: none; padding: 15px; border: 1px solid #ddd; border-top: none; }
.dpt-ds-accordion.active .accordion-content { display: block; }
.dpt-ds-accordion.dpt-hidden { display: none; }

.dpt-ds-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px; }
.dpt-ds-field { display: flex; flex-direction: column; gap: 5px; }
.dpt-ds-field label { font-weight: 600; font-size: 13px; color: #444; }
.dpt-ds-field input, .dpt-ds-field textarea { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
.dpt-ds-field input:focus, .dpt-ds-field textarea:focus { outline: 2px solid #2271b1; border-color: #2271b1; }
.dpt-ds-field .hint { font-weight: normal; color: #666; }

.dpt-template-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; text-align: center; }
.dpt-template-card { border: 2px solid #ddd; border-radius: 8px; padding: 10px; cursor: pointer; transition: all 0.2s; }
.dpt-template-card:hover { border-color: #2271b1; }
.dpt-template-card.selected { border-color: #2271b1; background: #e7f1ff; }
.dpt-template-card img { max-width: 100%; border-radius: 4px; }
.dpt-template-card input { margin-top: 8px; }

.dpt-slider-section {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background: #f9f9f9;
}
.dpt-slider-section h4 {
    margin: 0 0 10px 0;
    color: #2271b1;
    font-weight: 600;
}
.dpt-slider-input-group {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}
.dpt-slider-input-group input[type="text"] {
    flex: 1;
}
.dpt-slider-input-group img {
    max-height: 30px;
    margin-left: 10px;
    vertical-align: middle;
}
.dpt-media-btn { 
    padding: 8px 12px; cursor: pointer; border: 1px solid #ccc; 
    background: #fff; border-radius: 4px; font-size: 14px;
    white-space: nowrap;
}
.dpt-media-btn:hover { background: #e0e0e0; border-color: #2271b1; }
.dpt-add-slider-btn {
    display: inline-block; padding: 10px 20px; cursor: pointer;
    background: #2271b1; color: #fff; border: none; border-radius: 4px;
    font-size: 14px; margin-top: 10px;
}
.dpt-add-slider-btn:hover { background: #1d5a8a; }
.dpt-add-slider-btn:disabled { background: #ccc; cursor: not-allowed; }
.dpt-slider-hidden { display: none; }
.dpt-sliders-container { margin-top: 20px; }

.dpt-ds-checkbox { display: flex; align-items: center; gap: 8px; }
.dpt-ds-checkbox input { width: auto; }
.dpt-ds-checkbox label { font-weight: normal; }

.dpt-ds-row { display: flex; gap: 20px; flex-wrap: wrap; }
.dpt-ds-row .dpt-ds-field { flex: 1; min-width: 200px; }

.instructions-box { background: #f0f0f1; padding: 15px; border-radius: 8px; }
.instructions-box h3 { margin-top: 0; color: #2271b1; font-size: 16px; }
.instructions-box code { background: #fff; padding: 2px 5px; border-radius: 3px; font-size: 12px; }
.instructions-box li { margin-bottom: 8px; font-size: 13px; }

.dpt-exclusive-group { display: flex; gap: 20px; margin-bottom: 15px; flex-wrap: wrap; }
.dpt-exclusive-group label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.dpt-exclusive-group input { width: auto; }
</style>

<h3>Masjid/Mobile Screen Settings</h3>
<div class="container-fluid">
    <form class="form-group" name="digitalScreen" method="post">
    <?php echo wp_nonce_field( 'digitalScreen'); ?>
    
    <div id="dpt-ds-accordions">
        
        <!-- General Settings + Messages (merged, top, expanded) -->
        <div class="dpt-ds-accordion active">
            <div class="accordion-header" onclick="toggleDsAccordion(this)">⚙️ General Settings</div>
            <div class="accordion-content">
                <div class="dpt-exclusive-group">
                    <label>
                        <input type="radio" name="displayMode" value="quran" <?php echo (get_option("quran-chbox") === 'displayQuran') ? 'checked' : ''; ?> onchange="updateDisplayMode(this.value)">
                        Display Quran Verse
                    </label>
                    <label>
                        <input type="radio" name="displayMode" value="slider" <?php echo (get_option("slider-chbox") === 'slider') ? 'checked' : ''; ?> onchange="updateDisplayMode(this.value)">
                        Activate Slider
                    </label>
                </div>
                <!-- Hidden inputs to store actual option values -->
                <input type="hidden" name="quran-chbox" id="quran-chbox-hidden" value="<?php echo esc_attr(get_option("quran-chbox")); ?>">
                <input type="hidden" name="slider-chbox" id="slider-chbox-hidden" value="<?php echo esc_attr(get_option("slider-chbox")); ?>">
                <div class="dpt-ds-field" style="margin-top: 15px;">
                    <label>Fading Messages <span class="hint">(separated by full stop)</span></label>
                    <textarea name="ds-fading-msg" rows="3"><?php echo esc_html(stripslashes(get_option("ds-fading-msg")) )?></textarea>
                </div>
                <div class="dpt-ds-field" style="margin-top: 15px;">
                    <label>Custom Site Logo URL</label>
                    <input type="text" name="ds-logo" placeholder="https://..." value="<?php echo esc_html(get_option("ds-logo")); ?>">
                </div>
                <hr style="margin: 20px 0;">
                <div class="dpt-ds-row">
                    <div class="dpt-ds-field">
                        <label>Scrolling Text</label>
                        <input type="text" name="ds-scroll-text" value="<?php echo esc_html(stripslashes(get_option("ds-scroll-text"))); ?>">
                    </div>
                    <div class="dpt-ds-field">
                        <label>Scrolling Speed (1-100)</label>
                        <input type="number" name="ds-scroll-speed" min="10" max="100" value="<?php echo esc_html(get_option("ds-scroll-speed")); ?>">
                    </div>
                </div>
                <div class="dpt-ds-field" style="margin-top: 15px;">
                    <label>Blink Text</label>
                    <input type="text" name="ds-blink-text" value="<?php echo esc_html(stripslashes(get_option("ds-blink-text"))); ?>">
                </div>
            </div>
        </div>

        <!-- Slider Settings (conditional) -->
        <div class="dpt-ds-accordion <?php echo $isSliderActive ? 'active' : ''; ?>" id="dpt-slider-accordion">
            <div class="accordion-header" onclick="toggleDsAccordion(this)">🖼️ Slider Settings</div>
            <div class="accordion-content">
                <div class="dpt-ds-row" style="margin-bottom: 20px;">
                    <div class="dpt-ds-field">
                        <label>Re-display Next Prayer After # Slides</label>
                        <input type="number" name="nextPrayerSlide" min="0" value="<?php echo esc_html(get_option("nextPrayerSlide")); ?>">
                    </div>
                    <div class="dpt-ds-field">
                        <label>Transition Effect</label>
                        <div style="display: flex; gap: 15px; margin-top: 5px;">
                            <label><input type="radio" name="transitionEffect" value="slide" <?php if(get_option("transitionEffect") === 'slide'){ echo 'checked'; } ?>> Slide</label>
                            <label><input type="radio" name="transitionEffect" value="carousel-fade" <?php if(get_option("transitionEffect") === 'carousel-fade'){ echo 'checked'; } ?>> Fade</label>
                        </div>
                    </div>
                    <div class="dpt-ds-field">
                        <label>Transition Speed (seconds)</label>
                        <input type="number" name="transitionSpeed" min="0" placeholder="5" value="<?php echo esc_html(get_option("transitionSpeed") / 1000); ?>">
                    </div>
                </div>
                
                <hr style="margin: 20px 0;">
                
                <p style="margin-bottom: 15px; color: #666;">Click <strong>"🖼️ Select from Media"</strong> to choose images from your gallery. Maximum 7 sliders.</p>
                <div class="dpt-sliders-container">
                    <?php for ($i = 1; $i <= 11; $i++): ?>
                    <div class="dpt-slider-section<?php echo ($i > $displayCount) ? ' dpt-slider-hidden' : ''; ?>" data-slider="<?php echo $i; ?>">
                        <h4>Slider #<?php echo $i; ?></h4>
                        <div class="dpt-slider-input-group">
                            <button type="button" class="dpt-media-btn" data-input="slider<?php echo $i; ?>">🖼️ Select from Media</button>
                            <input type="text" class="slider-text" placeholder="Image URL or message" name="slider<?php echo $i; ?>" value="<?php echo esc_html(stripslashes(get_option("slider$i")) )?>">
                            <?php echo displayImage(get_option("slider$i")); ?>
                        </div>
                        <div class="dpt-slider-input-group">
                            <span style="color: #666; font-size: 12px;">Optional link:</span>
                            <input type="text" class="slider-text" placeholder="http(s):// url" name="slider<?php echo $i; ?>Url" value="<?php echo esc_html(get_option("slider{$i}Url")); ?>">
                        </div>
                    </div>
                    <?php endfor; ?>
                    
                    <button type="button" class="dpt-add-slider-btn" id="dpt-add-slider"<?php echo ($displayCount >= 7) ? ' style="display:none;"' : ''; ?>>+ Add Another Slider</button>
                </div>
            </div>
        </div>

        <!-- Template Selection (before Advanced) -->
        <div class="dpt-ds-accordion">
            <div class="accordion-header" onclick="toggleDsAccordion(this)">🎨 Template Selection (Optional)</div>
            <div class="accordion-content">
                <div class="dpt-template-grid">
                    <label class="dpt-template-card <?php echo (get_option("dsTemplate") === 'eict') ? 'selected' : ''; ?>">
                        <img src="<?php echo plugins_url('../../Assets/images/EICT.png', __FILE__)?>" alt="EICT">
                        <div><input type="radio" name="ds-template" value="eict" <?php if(get_option("dsTemplate") === 'eict'){ echo 'checked'; } ?>> <strong>Edgware ICT</strong></div>
                    </label>
                    <label class="dpt-template-card <?php echo (get_option("dsTemplate") === 'usman') ? 'selected' : ''; ?>">
                        <img src="<?php echo plugins_url('../../Assets/images/masjid-e-usman.jpeg', __FILE__)?>" alt="Usman">
                        <div><input type="radio" name="ds-template" value="usman" <?php if(get_option("dsTemplate") === 'usman'){ echo 'checked'; } ?>> <strong>Masjid-E-Usman</strong></div>
                    </label>
                    <label class="dpt-template-card" style="opacity: 0.6;">
                        <div style="padding: 40px 10px;">Coming Soon</div>
                        <div><input type="radio" disabled> <strong>Your Design</strong></div>
                        <small><a href="mailto:mmrs151@gmail.com">Request quote</a></small>
                    </label>
                </div>
            </div>
        </div>

        <!-- Advanced -->
        <div class="dpt-ds-accordion">
            <div class="accordion-header" onclick="toggleDsAccordion(this)">🔧 Advanced</div>
            <div class="accordion-content">
                <div class="dpt-ds-field">
                    <label>Additional CSS <span class="hint">(advanced users only)</span></label>
                    <textarea name="ds-additional-css" rows="5" placeholder=".dpt-class { ... }"><?php echo esc_html(get_option("ds-additional-css")); ?></textarea>
                </div>
            </div>
        </div>

        <!-- How to Use (last) -->
        <div class="dpt-ds-accordion">
            <div class="accordion-header" onclick="toggleDsAccordion(this)">❓ How to Use</div>
            <div class="accordion-content">
                <div class="instructions-box">
                    <h3>Quick Start Guide</h3>
                    <ol>
                        <li><a href="<?php echo admin_url('post-new.php?post_type=page'); ?>">Create a new page</a></li>
                        <li>Select page template: <code>Digital Screen Prayer Time</code></li>
                        <li>Add shortcode: <code>[digital_screen]</code></li>
                    </ol>
                    <h3>Shortcode Options</h3>
                    <ul>
                        <li><code>[digital_screen view='vertical']</code> - Mobile display</li>
                        <li><code>[digital_screen view='presentation']</code> - Slides only (no prayer times)</li>
                        <li><code>[digital_screen slides='url1,url2']</code> - Override slider images</li>
                        <li><code>[digital_screen dim=10]</code> - Dim after 10 mins</li>
                        <li><code>[digital_screen scroll='Your text']</code> - Override scroll message</li>
                        <li><code>[digital_screen blink='Alert text']</code> - Override blink text</li>
                        <li><code>[digital_screen scroll_link='https://...']</code> - Make scroll text clickable</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <div style="margin-top: 20px;">
        <?php submit_button('Save All Settings', 'primary', 'digitalScreen'); ?>
    </div>
    </form>
</div>

<script>
function toggleDsAccordion(header) {
    header.parentElement.classList.toggle('active');
}

jQuery(document).ready(function($) {
    // Handle exclusive display mode selection
    window.updateDisplayMode = function(value) {
        if (value === 'slider') {
            $('#quran-chbox-hidden').val('');
            $('#slider-chbox-hidden').val('slider');
            $('#dpt-slider-accordion').removeClass('dpt-hidden');
            $('#dpt-slider-accordion').addClass('active');
        } else if (value === 'quran') {
            $('#slider-chbox-hidden').val('');
            $('#quran-chbox-hidden').val('displayQuran');
            $('#dpt-slider-accordion').removeClass('active');
            $('#dpt-slider-accordion').addClass('dpt-hidden');
        }
    };
    
    // Add Another Slider button
    $('#dpt-add-slider').on('click', function() {
        var $hidden = $('.dpt-slider-section.dpt-slider-hidden').first();
        if ($hidden.length) {
            $hidden.removeClass('dpt-slider-hidden');
            if ($('.dpt-slider-section:not(.dpt-slider-hidden)').length >= 7) {
                $(this).hide();
            }
        }
    });
    
    // Media gallery button
    $(document).on('click', '.dpt-media-btn', function(e) {
        e.preventDefault();
        var btn = $(this);
        var inputName = btn.data('input');
        
        var frame = wp.media({
            title: 'Select Image for Slider',
            button: { text: 'Use this image' },
            multiple: false
        });
        
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('input[name="' + inputName + '"]').val(attachment.url).trigger('change');
        });
        
        frame.open();
    });

    // Template card selection
    $('.dpt-template-card').on('click', function() {
        $(this).find('input[type="radio"]').prop('checked', true);
        $('.dpt-template-card').removeClass('selected');
        $(this).addClass('selected');
    });
});
</script>
<?php 
wp_enqueue_media();
?>