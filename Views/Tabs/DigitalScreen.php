<?php
//var_dump(get_option("slider1Url")); exit;
?>
<h3>Digital screen settings</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <form class="form-group" name="digitalScreen" method="post">
                <table class="table">
                    <tr>
                        <td class="active-slider">Site Logo</td>
                        <td><input type="text" class="slider-text" placeholder="any valid image url" name="ds-logo" size="60" value=<?= get_option("ds-logo") ?>></td>
                    </tr>
                    <tr>
                        <td class="active-slider">Activate Slider</td>
                        <td><input type="checkbox" id="slider-chbox" name="slider-chbox" value="slider" <?php if(get_option("slider-chbox") === 'slider'){ echo 'checked'; } ?>></td>
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
                        <td><input type="number" class="slider-text" name="transitionSpeed" placeholder="5" value=<?= get_option("transitionSpeed")/1000 ?>> seconds </td>
                    </tr>

                    <tr class="ds-slides">
                        <td>Slider #1</td>
                        <td><input type="text" class="slider-text" placeholder="any valid image url" name="slider1Url" size="60" value=<?= get_option("slider1Url") ?>>
                            <img src="<?= get_option("slider1Url") ?>" style="max-height: 25px;" class="grow">
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #2</td>
                        <td><input type="text" class="slider-text" placeholder="any valid image url" name="slider2Url" size="60" value=<?= get_option("slider2Url") ?>>
                            <img src="<?= get_option("slider2Url") ?>" style="max-height: 25px;" class="grow">
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #3</td>
                        <td><input type="text" class="slider-text" placeholder="any valid image url" name="slider3Url" size="60" value=<?= get_option("slider3Url") ?>>
                            <img src="<?= get_option("slider3Url") ?>" style="max-height: 25px;" class="grow">
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #4</td>
                        <td><input type="text" class="slider-text" placeholder="any valid image url" name="slider4Url" size="60" value=<?= get_option("slider4Url") ?>>
                            <img src="<?= get_option("slider4Url") ?>" style="max-height: 25px;" class="grow">
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #5</td>
                        <td><input type="text" class="slider-text" placeholder="any valid image url" name="slider5Url" size="60" value=<?= get_option("slider5Url") ?>>
                            <img src="<?= get_option("slider5Url") ?>" style="max-height: 25px;" class="grow">
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #6</td>
                        <td><input type="text" class="slider-text" placeholder="any valid image url" name="slider6Url" size="60" value=<?= get_option("slider6Url") ?>>
                            <img src="<?= get_option("slider6Url") ?>" style="max-height: 25px;" class="grow">
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save changes', 'primary', 'digitalScreen'); ?>
            </form>
        </div>
        <div class="col-sm-6 col-xs-12 highlight">
            <h3 class="pt-2"><code>INSTRUCTIONS</code></h3>
            <li>Get a small computer, possibly a raspberry Pi</li>
            <li>Install wordpress locally - to avoid your site being hacked and redirected(x)</li>
            <li>Supported Theme:</li>
                <li style="margin-left:2em"><a class="url" href="https://en-gb.wordpress.org/themes/blankslate/" target="_blank">Blank Slate theme <i class="fa fa-external-link" aria-hidden="true"></i></a></li>
                <li style="margin-left:2em"><a class="url" href="https://wordpress.org/themes/page-builder-framework/" target="_blank">Page-builder-framework theme<i class="fa fa-external-link" aria-hidden="true"></i></a></li>
            <li>Alternatively - create a subdomain on your site, i.e screen.yourmasjid.com</li>
                <li style="margin-left:2em">Install wordpress to use that theme </li>
                <li style="margin-left:2em">Only allow whitelisted IP addresses to avoid hack</li>
            <li><a class="url" href="post-new.php?post_type=page">Create a new page</a></li>
            <li>Select page template <code>Digital Screen Prayer Time</code></li>
            <li>Use shortcode <code>[digital_screen]</code> to display horizontally/Landscape</li>
            <li><code>[digital_screen view='vertical']</code> to display vertically/Portrait</li>
            <li><code>[digital_screen view='vertical' dim=10]</code> to dim vertically screen for 10 mins when prayer starts</li>
            <li><code>[digital_screen view='vertical' dim=10 scroll='any text']</code> to override scrolling message</li>
            <li><code>[digital_screen view='vertical' dim=10 blink='any text']</code> to override blinking alert message</li>
        </div>
    </div>
</div>

