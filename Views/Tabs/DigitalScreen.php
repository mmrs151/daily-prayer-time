<?php
/**
 * Digital Screen Settings Admin Page
 */

class DigitalScreenSettings {
    
    const MAX_SLIDERS = 7;
    const TOTAL_SLIDER_FIELDS = 11;
    
    public function render(): void {
        $this->renderStyles();
        $this->renderFormOpen();
        $this->renderGeneralSettings();
        $this->renderSliderSettings();
        $this->renderTemplateSettings();
        $this->renderAdvancedSettings();
        $this->renderInstructions();
        $this->renderFormClose();
        $this->renderScripts();
    }
    
    private function renderStyles(): void {
        ?>
        <style>
            .dpt-settings-wrapper { max-width: 1200px; margin: 0 auto; }
            .dpt-section { margin-bottom: 12px; border-radius: 6px; overflow: hidden; }
            .dpt-section-header { 
                background: #2271b1; color: #fff; padding: 14px 18px; cursor: pointer; 
                display: flex; justify-content: space-between; align-items: center;
                font-weight: 600; transition: background 0.2s;
            }
            .dpt-section-header:hover { background: #1d5a8a; }
            .dpt-section-header::after { content: '▼'; font-size: 11px; transition: transform 0.3s; }
            .dpt-section.active .dpt-section-header::after { transform: rotate(180deg); }
            .dpt-section-content { display: none; padding: 20px; border: 1px solid #ddd; border-top: none; }
            .dpt-section.active .dpt-section-content { display: block; }
            .dpt-section.hidden { display: none; }
            
            .dpt-form-row { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 15px; }
            .dpt-form-group { flex: 1; min-width: 200px; }
            .dpt-form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #444; }
            .dpt-form-group input, .dpt-form-group textarea { 
                width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
            }
            .dpt-form-group textarea { 
                resize: vertical; 
                min-height: unset;
                height: auto;
            }
            .dpt-form-group input:focus, .dpt-form-group textarea:focus { 
                outline: 2px solid #2271b1; border-color: #2271b1; 
            }
            .dpt-form-group-wide { flex: 2; }
            .dpt-hint { font-weight: normal; color: #666; font-size: 12px; }
            
            .dpt-option-group { display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; }
            .dpt-option-group label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
            .dpt-option-group .dpt-separator { color: #999; font-weight: bold; margin: 0 10px; }
            
            .dpt-slider-card {
                border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 12px;
                background: #f9f9f9;
            }
            .dpt-slider-card h4 { margin: 0 0 12px 0; color: #2271b1; }
            .dpt-slider-input-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
            .dpt-slider-input-row input { flex: 1; }
            .dpt-slider-url-row { display: flex; gap: 15px; margin-top: 8px; }
            .dpt-slider-url-row span { color: #666; font-size: 12px; min-width: 80px; }
            .dpt-slider-optional { color: #666; margin-bottom: 15px; }
            .dpt-media-btn { 
                padding: 8px 14px; cursor: pointer; border: 1px solid #ccc; 
                background: #fff; border-radius: 4px; white-space: nowrap;
            }
            .dpt-media-btn:hover { background: #e0e0e0; border-color: #2271b1; }
            .dpt-add-btn { 
                display: inline-block; padding: 10px 20px; cursor: pointer;
                background: #2271b1; color: #fff; border: none; border-radius: 4px;
            }
            .dpt-add-btn:hover { background: #1d5a8a; }
            .dpt-add-btn-hidden { display: none; }
            
            .dpt-template-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; text-align: center; }
            .dpt-template-card { border: 2px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; }
            .dpt-template-card:hover { border-color: #2271b1; }
            .dpt-template-card.selected { border-color: #2271b1; background: #e7f1ff; }
            .dpt-template-card img { max-width: 100%; border-radius: 4px; }
            .dpt-template-card.dpt-template-placeholder { opacity: 0.8; border-style: dashed; }
            .dpt-placeholder-img { 
                height: 100px; background: #e0e0e0; border-radius: 4px; margin-bottom: 8px;
                display: flex; align-items: center; justify-content: center;
                color: #888; font-size: 13px; border: 2px dashed #bbb;
            }
            .dpt-template-note { margin-top: 15px; color: #888; font-size: 12px; }
            
            .dpt-instructions { background: #f0f0f1; padding: 15px; border-radius: 8px; }
            .dpt-instructions h3 { margin: 0 0 12px 0; color: #2271b1; }
            .dpt-instructions code { background: #fff; padding: 2px 5px; border-radius: 3px; }
            .dpt-divider { border: none; border-top: 1px solid #ddd; margin: 20px 0; }

            .dpt-form-row-wide { margin-top: 20px; }
            .dpt-image-preview { max-height: 30px; margin-left: 10px; }
            .dpt-image-preview:hover { 
                position: fixed; 
                top: 50%; left: 50%; 
                transform: translate(-50%, -50%); 
                max-height: 80vh; 
                max-width: 80vw; 
                z-index: 9999; 
                background: #fff; 
                padding: 10px;
                box-shadow: 0 0 20px rgba(0,0,0,0.5);
            }
        </style>
        <?php
    }
    
    private function renderFormOpen(): void {
        ?>
        <h3>Masjid/Mobile Screen Settings</h3>
        <div class="dpt-settings-wrapper">
            <form class="form-group" name="digitalScreen" method="post">
            <?php echo wp_nonce_field('digitalScreen'); ?>
            <div id="dpt-sections">
        <?php
    }
    
    private function renderGeneralSettings(): void {
        $sliderActive = get_option('slider-chbox') === 'slider';
        $templateActive = get_option('template-chbox') === 'template';
        ?>
        <div class="dpt-section active">
            <div class="dpt-section-header" onclick="toggleDptSection(this)">⚙️ General Settings</div>
            <div class="dpt-section-content">
                <div class="dpt-option-group">
                    <label>
                        <input type="radio" name="displayMode" value="default" 
                            <?php echo (!$sliderActive && !$templateActive) ? 'checked' : ''; ?>>
                        Default Layout
                    </label>
                    <label>
                        <input type="radio" name="displayMode" value="template" 
                            <?php echo $templateActive ? 'checked' : ''; ?>>
                        Activate Template
                    </label>
                    <span class="dpt-separator">|</span>
                    <label>
                        <input type="radio" name="displayModeAlt" value="quran" 
                            <?php echo get_option('quran-chbox') === 'displayQuran' ? 'checked' : ''; ?>>
                        Display Quran Verse
                    </label>
                    <label>
                        <input type="radio" name="displayModeAlt" value="slider" 
                            <?php echo $sliderActive ? 'checked' : ''; ?>>
                        Activate Slider
                    </label>
                </div>
                <input type="hidden" name="quran-chbox" id="quran-chbox-hidden" value="<?php echo esc_attr(get_option('quran-chbox') ?? ''); ?>">
                <input type="hidden" name="slider-chbox" id="slider-chbox-hidden" value="<?php echo esc_attr(get_option('slider-chbox') ?? ''); ?>">
                <input type="hidden" name="template-chbox" id="template-chbox-hidden" value="<?php echo esc_attr(get_option('template-chbox') ?? ''); ?>">
                
                <div class="dpt-form-row">
                    <div class="dpt-form-group dpt-form-group-wide">
                        <label>Fading Messages <span class="dpt-hint">(separated by full stop)</span></label>
                        <textarea name="ds-fading-msg" rows="3"><?php echo esc_textarea(get_option('ds-fading-msg') ?? ''); ?></textarea>
                    </div>
                    <div class="dpt-form-group">
                        <label>Custom Site Logo URL</label>
                        <input type="text" name="ds-logo" placeholder="https://..." 
                            value="<?php echo esc_attr(get_option('ds-logo') ?? ''); ?>">
                    </div>
                </div>
                
                <hr class="dpt-divider">
                
                <div class="dpt-form-row">
                    <div class="dpt-form-group">
                        <label>Scrolling Text</label>
                        <input type="text" name="ds-scroll-text" 
                            value="<?php echo esc_attr(get_option('ds-scroll-text') ?? ''); ?>">
                    </div>
                    <div class="dpt-form-group">
                        <label>Scrolling Speed (1-100)</label>
                        <input type="number" name="ds-scroll-speed" min="10" max="100" 
                            value="<?php echo esc_attr(get_option('ds-scroll-speed') ?? 30); ?>">
                    </div>
                    <div class="dpt-form-group">
                        <label>Blink Text</label>
                        <input type="text" name="ds-blink-text" 
                            value="<?php echo esc_attr(get_option('ds-blink-text') ?? ''); ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function renderSliderSettings(): void {
        $displayCount = $this->getDisplayCount();
        $sliderActive = get_option('slider-chbox') === 'slider';
        
        ?>
        <div class="dpt-section <?php echo $sliderActive ? 'active' : ''; ?>" id="dpt-slider-section">
            <div class="dpt-section-header" onclick="toggleDptSection(this)">🖼️ Slider Settings</div>
            <div class="dpt-section-content">
                <div class="dpt-form-row">
                    <div class="dpt-form-group">
                        <label>Re-display Next Prayer After # Slides</label>
                        <input type="number" name="nextPrayerSlide" min="0" 
                            value="<?php echo esc_attr(get_option('nextPrayerSlide') ?? 0); ?>">
                    </div>
                    <div class="dpt-form-group">
                        <label>Transition Effect</label>
                        <div class="dpt-slider-url-row">
                            <label><input type="radio" name="transitionEffect" value="slide" <?php echo get_option('transitionEffect') === 'slide' ? 'checked' : ''; ?>> Slide</label>
                            <label><input type="radio" name="transitionEffect" value="carousel-fade" <?php echo get_option('transitionEffect') === 'carousel-fade' ? 'checked' : ''; ?>> Fade</label>
                        </div>
                    </div>
                    <div class="dpt-form-group">
                        <label>Transition Speed (seconds)</label>
                        <input type="number" name="transitionSpeed" min="0" placeholder="5" 
                            value="<?php echo esc_attr((get_option('transitionSpeed') ?? 5000) / 1000); ?>">
                    </div>
                </div>
                
                <hr class="dpt-divider">
                
                <p class="dpt-slider-optional">
                    Click <strong>"🖼️ Select from Media"</strong> to choose images. Maximum <?php echo self::MAX_SLIDERS; ?> sliders.
                </p>
                
                <?php for ($i = 1; $i <= self::TOTAL_SLIDER_FIELDS; $i++): ?>
                <div class="dpt-slider-card <?php echo $i > $displayCount ? 'hidden' : ''; ?>" data-slider="<?php echo $i; ?>">
                    <h4>Slider #<?php echo $i; ?></h4>
                    <div class="dpt-slider-input-row">
                        <button type="button" class="dpt-media-btn" data-input="slider<?php echo $i; ?>">🖼️ Select from Media</button>
                        <input type="text" placeholder="Image URL or message" name="slider<?php echo $i; ?>" 
                            value="<?php echo esc_attr(get_option("slider$i") ?? ''); ?>">
                        <?php echo $this->displayImage(get_option("slider$i")); ?>
                    </div>
                    <div class="dpt-slider-input-row">
                        <span class="dpt-slider-url-label">Optional link:</span>
                        <input type="text" placeholder="http(s):// url" name="slider<?php echo $i; ?>Url" 
                            value="<?php echo esc_attr(get_option("slider{$i}Url") ?? ''); ?>">
                    </div>
                </div>
                <?php endfor; ?>
                
                <button type="button" class="dpt-add-btn" id="dpt-add-slider"
                    <?php echo $displayCount >= self::MAX_SLIDERS ? 'class="dpt-add-btn dpt-add-btn-hidden"' : 'class="dpt-add-btn"'; ?>>
                    + Add Another Slider
                </button>
            </div>
        </div>
        <?php
    }
    
    private function renderTemplateSettings(): void {
        $savedTemplate = get_option('dsTemplate') ?? '';
        $templateActive = get_option('template-chbox') === 'template';
        ?>
        <div class="dpt-section <?php echo $templateActive ? 'active' : 'hidden'; ?>" id="dpt-template-section">
            <div class="dpt-section-header" onclick="toggleDptSection(this)">🎨 Template Selection</div>
            <div class="dpt-section-content">
                <p class="dpt-slider-optional">
                    Select a template design for your digital screen. This will replace the default layout.
                </p>
                <div class="dpt-template-grid">
                    <label class="dpt-template-card <?php echo $savedTemplate === 'eict' ? 'selected' : ''; ?>">
                        <img src="<?php echo plugins_url('../../Assets/images/EICT.png', __FILE__); ?>" alt="EICT">
                        <div><input type="radio" name="ds-template" value="eict" <?php echo $savedTemplate === 'eict' ? 'checked' : ''; ?>> <strong>Edgware ICT</strong></div>
                    </label>
                    <label class="dpt-template-card <?php echo $savedTemplate === 'usman' ? 'selected' : ''; ?>">
                        <img src="<?php echo plugins_url('../../Assets/images/masjid-e-usman.jpeg', __FILE__); ?>" alt="Usman">
                        <div><input type="radio" name="ds-template" value="usman" <?php echo $savedTemplate === 'usman' ? 'checked' : ''; ?>> <strong>Masjid-E-Usman</strong></div>
                    </label>
                    <label class="dpt-template-card dpt-template-placeholder">
                        <div class="dpt-placeholder-img">Add your template here</div>
                        <div><input type="radio" disabled> <strong>Add your template here</strong></div>
                        <small><a href="mailto:mmrs151@gmail.com?subject=Custom Template Design">Let's discuss</a></small>
                    </label>
                </div>
                <p class="dpt-template-note">
                    To return to the default layout, uncheck "Activate Template" above.
                </p>
            </div>
        </div>
        <?php
    }
    
    private function renderAdvancedSettings(): void {
        ?>
        <div class="dpt-section">
            <div class="dpt-section-header" onclick="toggleDptSection(this)">🔧 Advanced</div>
            <div class="dpt-section-content">
                <div class="dpt-form-group">
                    <label>Additional CSS <span class="dpt-hint">(advanced users only)</span></label>
                    <textarea name="ds-additional-css" rows="3" placeholder=".dpt-class { ... }"><?php echo get_option('ds-additional-css'); ?></textarea>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function renderInstructions(): void {
        ?>
        <div class="dpt-section">
            <div class="dpt-section-header" onclick="toggleDptSection(this)">❓ How to Use</div>
            <div class="dpt-section-content">
                <div class="dpt-instructions">
                    <h3>Quick Start Guide</h3>
                    <ol>
                        <li><a href="<?php echo admin_url('post-new.php?post_type=page'); ?>">Create a new page</a></li>
                        <li>Select page template: <code>Digital Screen Prayer Time</code></li>
                        <li>Add shortcode: <code>[digital_screen]</code></li>
                    </ol>
                    <h3>Shortcode Options</h3>
                    <ul>
                        <li><code>[digital_screen view='vertical']</code> - Mobile display</li>
                        <li><code>[digital_screen view='presentation']</code> - Slides only</li>
                        <li><code>[digital_screen dim=10]</code> - Dim after 10 mins</li>
                        <li><code>[digital_screen scroll='text']</code> - Override scroll</li>
                        <li><code>[digital_screen blink='text']</code> - Override blink</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function renderFormClose(): void {
        ?>
            </div>
            <div class="dpt-form-row-wide">
                <?php submit_button('Save All Settings', 'primary', 'digitalScreen'); ?>
            </div>
            </form>
        </div>
        <?php
    }
    
    private function renderScripts(): void {
        wp_enqueue_media();
        ?>
        <script>
        function toggleDptSection(header) {
            header.parentElement.classList.toggle('active');
        }
        
        jQuery(document).ready(function($) {
            // Display mode selection - group 1 (default/template)
            $('input[name="displayMode"]').on('change', function() {
                const value = $(this).val();
                const templateSection = $('#dpt-template-section');
                const templateInput = $('#template-chbox-hidden');
                
                if (value === 'template') {
                    templateInput.val('template');
                    templateSection.removeClass('hidden').addClass('active');
                } else if (value === 'default') {
                    templateInput.val('');
                    templateSection.removeClass('active').addClass('hidden');
                }
            });
            
            // Display mode selection - group 2 (quran/slider)
            $('input[name="displayModeAlt"]').on('change', function() {
                const value = $(this).val();
                const sliderSection = $('#dpt-slider-section');
                const quranInput = $('#quran-chbox-hidden');
                const sliderInput = $('#slider-chbox-hidden');
                
                if (value === 'slider') {
                    quranInput.val('');
                    sliderInput.val('slider');
                    sliderSection.removeClass('hidden').addClass('active');
                } else if (value === 'quran') {
                    sliderInput.val('');
                    quranInput.val('displayQuran');
                    sliderSection.removeClass('active').addClass('hidden');
                }
            });
            
            // Initialize section visibility on load
            const displayMode = $('input[name="displayMode"]:checked').val();
            const displayModeAlt = $('input[name="displayModeAlt"]:checked').val();
            
            if (displayMode === 'template') {
                $('#dpt-template-section').removeClass('hidden').addClass('active');
            } else {
                $('#dpt-template-section').addClass('hidden').removeClass('active');
            }
            
            if (displayModeAlt === 'slider') {
                $('#dpt-slider-section').removeClass('hidden').addClass('active');
            } else {
                $('#dpt-slider-section').addClass('hidden').removeClass('active');
            }
            
            // Sync hidden inputs with checked radio on page load
            const checkedValue = $('input[name="displayMode"]:checked').val();
            const checkedValueAlt = $('input[name="displayModeAlt"]:checked').val();
            
            if (checkedValue === 'template') {
                $('#template-chbox-hidden').val('template');
            } else if (checkedValueAlt === 'quran') {
                $('#quran-chbox-hidden').val('displayQuran');
            } else if (checkedValueAlt === 'slider') {
                $('#slider-chbox-hidden').val('slider');
            }
            
            // Add slider button
            $('#dpt-add-slider').on('click', function() {
                const hidden = $('.dpt-slider-card.hidden').first();
                if (hidden.length) {
                    hidden.removeClass('hidden');
                    if ($('.dpt-slider-card:not(.hidden)').length >= 7) {
                        $(this).hide();
                    }
                }
            });
            
            // Media gallery
            $(document).on('click', '.dpt-media-btn', function(e) {
                e.preventDefault();
                const inputName = $(this).data('input');
                const frame = wp.media({
                    title: 'Select Image for Slider',
                    button: { text: 'Use this image' },
                    multiple: false
                });
                
                frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();
                    $('input[name="' + inputName + '"]').val(attachment.url).trigger('change');
                });
                
                frame.open();
            });
        });
        </script>
        <?php
    }
    
    private function getDisplayCount(): int {
        $count = 0;
        for ($i = 1; $i <= self::TOTAL_SLIDER_FIELDS; $i++) {
            if (!empty(get_option("slider$i"))) {
                $count = max($count, $i);
            }
        }
        return max(1, min($count, self::MAX_SLIDERS));
    }
    
    private function displayImage(string $url = ''): string {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return '<img src="' . esc_url($url) . '" class="dpt-image-preview">';
        }
        return '';
    }
}

// Render the settings
$settings = new DigitalScreenSettings();
$settings->render();