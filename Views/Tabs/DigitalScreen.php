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
?>
<style>
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
</style>

<h3>Masjid/Mobile screen settings</h3>
<div class="container-fluid">
    <form class="form-group" name="digitalScreen" method="post">
    <div class="row ds-templates">
        <table class="table">
            <tr>
                <td class="align-middle">
                <?php $template = plugins_url('../../Assets/images/EICT.png', __FILE__)?>
                    <a href="<?php echo $template ?>" target="_new">
                        <img src="<?php echo $template ?>" width="200px">
                    </a>
                    <br/>or use shortcode <code>template='eict'</code>
                </td>
                <td>
                    <?php $template = plugins_url('../../Assets/images/masjid-e-usman.jpeg', __FILE__)?>
                    <a href="<?php echo $template ?>" target="_new">
                        <img src="<?php echo $template ?>" width="200px">
                    </a>
                    <br/>or use shortcode <code>template='usman'</code>
                </td>
                <td><img src="<?php echo plugins_url('../../Assets/images/new-template.png', __FILE__)?>" width="200px"></td>
            </tr>
            <tr>
                <td style="width: 33%;"><input type="radio" name="ds-template" value="eict" <?php if(get_option("dsTemplate") === 'eict'){ echo 'checked'; } ?>><strong>Edgware Islamic Cultural Trust</strong></td>
                <td><input type="radio" name="ds-template" value="usman" <?php if(get_option("dsTemplate") === 'usman'){ echo 'checked'; } ?>><strong>Masjid-E-Usman</strong></td>
                <td><input type="radio" name="ds-template" value="" disabled><a href="mailto:mmrs151@gmail.com?subject=Add my design to your plugin" target="_new">Add Your Design</a></td>
            </tr>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <?php echo wp_nonce_field( 'digitalScreen'); ?>
                <table class="table">
                    <tr>
                        <td class="active-slider">Select Template</td>
                        <td><input class="templateChbox" type="checkbox" id="template-chbox" name="template-chbox" value="template" <?php if(get_option("template-chbox") === 'template'){ echo 'checked'; } ?>></td>
                    </tr>
                    <tr>
                        <td class="active-slider">Fading Messages </br><i><sub>seperated by full stop.</sub></i></td>
                        <td>
                            <textarea name="ds-fading-msg" cols="30"><?php echo esc_html(stripslashes(get_option("ds-fading-msg")) )?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="active-slider">Custom Site Logo</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="ds-logo" size="30" value=<?php echo esc_html(get_option("ds-logo") )?>></td>
                    </tr>
                    <tr>
                        <td class="active-slider">Scrolling Text</td>
                        <td><input type="text" class="" name="ds-scroll-text" size="30" value="<?php echo esc_html(stripslashes(get_option("ds-scroll-text")) )?>"></td>
                    </tr>
                    <tr>
                        <td class="active-slider">Scrolling Speed</td>
                        <td><input type="number" min="10" max="100" class="" name="ds-scroll-speed" size="30" value="<?php echo esc_html(get_option("ds-scroll-speed") )?>"></td>
                    </tr>
                    <tr>
                        <td class="active-slider">Blink Text</td>
                        <td><input type="text" class="slider-text" name="ds-blink-text" size="30" value="<?php echo esc_html(stripslashes(get_option("ds-blink-text")) )?>"></td>
                    </tr>
                    <tr>
                        <td class="active-slider">Display Quran verse</td>
                        <td><input class="oneChbox" type="checkbox" id="quran-chbox" name="quran-chbox" value="displayQuran" <?php if(get_option("quran-chbox") === 'displayQuran'){ echo 'checked'; } ?>></td>
                    </tr>
                    <tr>
                        <td class="active-slider">Activate Slider</td>
                        <td><input class="oneChbox" type="checkbox" id="slider-chbox" name="slider-chbox" value="slider" <?php if(get_option("slider-chbox") === 'slider'){ echo 'checked'; } ?>></td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Re-display Next Prayer</td>
                        <td><input type="number" class="slider-text" placeholder=" after number of slides" name="nextPrayerSlide" min="0" value=<?php echo esc_html(get_option("nextPrayerSlide") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Transition Effect</td>
                        <td>
                            <label class="radio-inline">
                                <input type="radio" name="transitionEffect" value="slide" <?php if(get_option("transitionEffect") === 'slide'){ echo 'checked'; } ?>>Slide
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="transitionEffect" value="carousel-fade" <?php if(get_option("transitionEffect") === 'carousel-fade'){ echo 'checked'; } ?>>Fade
                            </label>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Transition Speed</td>
                        <td><input type="number" min="0" class="slider-text" name="transitionSpeed" placeholder="5" value=<?php echo esc_html(get_option("transitionSpeed")/1000 ) ?>> seconds </td>
                    </tr>
                </table>
                
                <div class="dpt-sliders-container">
                    <h4>Sliders <small>(click + Add Another to add more, max 7)</small></h4>
                    
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
                
                <table class="table" style="margin-top: 20px;">
                    <tr>
                        <td class="active-slider">Additional CSS</td>
                        <td><textarea name="ds-additional-css" cols="30"><?php echo esc_html(get_option("ds-additional-css") )?></textarea></td>
                    </tr>   
                </table>
                <?php submit_button('Save changes', 'primary', 'digitalScreen'); ?>
        </div>
        <div class="col-sm-6 col-xs-12" style="background-color: #eeeeee;">
            <h3 class="pt-2"><code>INSTRUCTIONS</code></h3>
            <li><a class="url" href="post-new.php?post_type=page">Create a new page</a></li>
            <li>Select page template <code>Digital Screen Prayer Time</code></li>
            <li>Use shortcode <code>[digital_screen]</code> to display in Monitor</li>
            <li><code>[digital_screen view='vertical']</code> for Mobile diaplay</li>
            <li><code>[digital_screen view='presentation']</code> display slides only, hiding prayer time</li>
            <li><code>[digital_screen slides="image1Url,image2Url,...image11Url"]</code> Override slides</li>
            <li><code>[digital_screen view='vertical' dim=10]</code> to dim vertically screen for 10 mins when prayer starts</li>
            <li><code>[digital_screen view='vertical' dim=10 scroll='any text']</code> to override scrolling message</li>
            <li><code>[digital_screen view='vertical' dim=10 blink='any text']</code> to override blinking alert message</li>
            <li><code>[digital_screen view='vertical' blink='any text' blnk_link='https://valid.url' scroll='any text' scroll_link='https://valid.url']</code> Allows mobile user to click on the text and possibly pay donation</li>
        </div>
    </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
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
        
        // Create WordPress media frame
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
});
</script>
<?php 
// Enqueue WordPress media uploader scripts
wp_enqueue_media();
?>