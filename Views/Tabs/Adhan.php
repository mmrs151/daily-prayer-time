<h3>Adhan settings</h3>
<p class="adhan-copy">
The idea is, the mosque will provide/ask the Musallis to use a tablet in their home to load the digital display.
That will play the pre-recorded adhan on a time set on this page.
Musallis can also click on the donation link, and event/stream link from their display.
Unlike the costly and not-so-user-friendly radio system, this will be a more interactive and cost-effective solution inshaaAllah.
</br>
You can modify the default Adhan by uploading your own adhan and update the Adhan Urls. 
</br>
Ramadan Fajr Adhan will play at fajr start time.
</p>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8 col-xs-12">
            <form name="adhanSettings" method="post" class="form-group">
            <?php echo wp_nonce_field( 'adhanSettings'); ?>
                <table class="table">
                    <tr>
                        <td>Activate Beep:</td>
                        <td>
                            <input type="checkbox" name="activateBeep" value="beep" <?php if(get_option("activateBeep") === 'beep'){ echo 'checked'; } ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>Activate Adhan:</td>
                        <td>
                            <input type="checkbox" name="activateAdhan" value="adhan" <?php if(get_option("activateAdhan") === 'adhan'){ echo 'checked'; } ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>Fajr Adhan URL</td>
                        <td><input type="text" placeholder="url or leave empty to use default Adhan" name="fajrAdhanUrl" size="50" value=<?php echo esc_html(get_option("fajrAdhanUrl") )?>></td>
                    </tr>

                    <tr>
                        <td>Other Adhan URL</td>
                        <td><input type="text" placeholder="url or leave empty to use default Adhan" name="otherAdhanUrl" size="50" value=<?php echo esc_html(get_option("otherAdhanUrl") )?>></td>
                    </tr>
                    <tr>
                        <td>Fajr Adhan before</td>
                        <td><input type="number" name="fajrAdhanBefore" min="0" max="30" value="<?php echo esc_html( get_option('fajrAdhanBefore') )?>"> mins</td>
                    </tr>

                    <tr>
                        <td>Zuhr Adhan before</td>
                        <td><input type="number" name="zuhrAdhanBefore" min="0" max="30" value="<?php echo esc_html( get_option('zuhrAdhanBefore') )?>"> mins</td>
                    </tr>

                    <tr>
                        <td>Asr Adhan before</td>
                        <td><input type="number" name="asrAdhanBefore" min="0" max="30" value="<?php echo esc_html( get_option('asrAdhanBefore') )?>"> mins</td>
                    </tr>

                    <tr>
                        <td>Isha Adhan before</td>
                        <td><input type="number" name="ishaAdhanBefore" min="0" max="30" value="<?php echo esc_html( get_option('ishaAdhanBefore') )?>"> mins</td>
                    </tr>
                    <tr><td colspan="2"><p class="adhan-copy"><i>*0 for default</i></p></tr>
                </table>
                <?php submit_button('Save changes', 'primary', 'adhanSettings'); ?>
            </form>
        </div>
    </div>
</div>
