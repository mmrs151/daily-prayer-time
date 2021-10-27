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
                            <i>0 to disable</i>
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

                    <tr class="ds-slides">
                        <td>Slider #1</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider1" size="30" value="<?php echo esc_html(stripslashes(get_option("slider1")) )?>">
                            <img src="<?php echo esc_html(get_option("slider1") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider1Url" size="30" value=<?php echo esc_html(get_option("slider1Url") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #2</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider2" size="30" value="<?php echo esc_html(stripslashes(get_option("slider2")) )?>">
                            <img src="<?php echo esc_html(get_option("slider2") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider2Url" size="30" value=<?php echo esc_html(get_option("slider2Url") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #3</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider3" size="30" value="<?php echo esc_html(stripslashes(get_option("slider3")) )?>">
                            <img src="<?php echo esc_html(get_option("slider3") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider3Url" size="30" value=<?php echo esc_html(get_option("slider3Url") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #4</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider4" size="30" value="<?php echo esc_html(stripslashes(get_option("slider4")) )?>">
                            <img src="<?php echo esc_html(get_option("slider4") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider4Url" size="30" value=<?php echo esc_html(get_option("slider4Url") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #5</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider5" size="30" value="<?php echo esc_html(stripslashes(get_option("slider5")) )?>">
                            <img src="<?php echo esc_html(get_option("slider5") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider5Url" size="30" value=<?php echo esc_html(get_option("slider5Url") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #6</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider6" size="30" value="<?php echo esc_html(stripslashes(get_option("slider6")) )?>">
                            <img src="<?php echo esc_html(get_option("slider6") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider6Url" size="30" value=<?php echo esc_html(get_option("slider6Url") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #7</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider7" size="30" value="<?php echo esc_html(stripslashes(get_option("slider7")) )?>">
                            <img src="<?php echo esc_html(get_option("slider7") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider7Url" size="30" value=<?php echo esc_html(get_option("slider7Url") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #8</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider8" size="30" value="<?php echo esc_html(stripslashes(get_option("slider8")) )?>">
                            <img src="<?php echo esc_html(get_option("slider8") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider8Url" size="30" value=<?php echo esc_html(get_option("slider8Url") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #9</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider9" size="30" value="<?php echo esc_html(stripslashes(get_option("slider9")) )?>">
                            <img src="<?php echo esc_html(get_option("slider9") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider9Url" size="30" value=<?php echo esc_html(get_option("slider9Url") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #10</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider10" size="30" value="<?php echo esc_html(stripslashes(get_option("slider10")) )?>">
                            <img src="<?php echo esc_html(get_option("slider10") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider10Url" size="30" value=<?php echo esc_html(get_option("slider10Url") )?>>
                        </td>
                    </tr>
                    <tr class="ds-slides">
                        <td>Slider #11</td>
                        <td><input type="text" class="slider-text" placeholder="Any message or image url" name="slider11" size="30" value="<?php echo esc_html(stripslashes(get_option("slider11")) )?>">
                            <img src="<?php echo esc_html(get_option("slider11") )?>" style="max-height: 25px;" class="grow">
                            <br/>
                                <input type="text" class="slider-text" placeholder="[optional] http(s)://  url" name="slider11Url" size="30" value=<?php echo esc_html(get_option("slider11Url") )?>>
                        </td>
                    </tr>
                    <tr>
                        <td class="active-slider">Additional CSS</td>
                        <td><textarea class="slider-text" name="ds-additional-css"><?php echo esc_html(get_option("ds-additional-css") )?></textarea></td>
                    </tr>   
                </table>
                <?php submit_button('Save changes', 'primary', 'digitalScreen'); ?>
            </form>
        </div>
        <div class="col-sm-6 col-xs-12 highlight">
            <h3 class="pt-2"><code>INSTRUCTIONS</code></h3>
            <li><a class="url" href="post-new.php?post_type=page">Create a new page</a></li>
            <li>Select page template <code>Digital Screen Prayer Time</code></li>
            <li>Use shortcode <code>[digital_screen]</code> to display horizontally/Landscape</li>
            <li><code>[digital_screen view='presentation' slides=image,image,...]</code> display slides only, hiding prayer time</li>
            <li><code>[digital_screen view='vertical']</code> to display vertically/Portrait</li>
            <li><code>[digital_screen view='vertical' dim=10]</code> to dim vertically screen for 10 mins when prayer starts</li>
            <li><code>[digital_screen view='vertical' dim=10 scroll='any text']</code> to override scrolling message</li>
            <li><code>[digital_screen view='vertical' dim=10 blink='any text']</code> to override blinking alert message</li>
            <li><code>[digital_screen view='vertical' blink='any text' blnk_link='https://valid.url' scroll='any text' scroll_link='https://valid.url']</code> Allows mobile user to click on the text and possibly pay donation</li>
        </div>
    </div>
</div>

