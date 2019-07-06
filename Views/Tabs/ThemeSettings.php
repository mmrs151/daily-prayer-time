<h2>Theme settings</h2>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <form name="themeSettings" method="post">
                <div style="width: 100%; height: 20px; border-bottom: 1px solid black; text-align: center; margin-bottom: 30px;">
                    <span style="font-size: 20px; background-color: #F3F5F6; padding: 10px;">
                        Prayer time table
                    </span>
                </div>
                <table class='table-sm'>
                    <tr>
                        <td style="width: 70%">Borderless daily prayer time</td>
                        <td><input type="checkbox"
                                    name="hideTableBorder"
                                    value="dptNoBorder" <?php if(get_option("hideTableBorder") === 'dptNoBorder'){ echo 'checked'; } ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>Table background</td>
                        <td><input type="text" name="tableBackground" value="<?php echo get_option('tableBackground') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Font color</td>
                        <td><input type="text" name="fontColor" value="<?php echo get_option('fontColor') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Table heading</td>
                        <td><input type="text" name="tableHeading" value="<?php echo get_option('tableHeading') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Table heading font</td>
                        <td><input type="text" name="tableHeadingFont" value="<?php echo get_option('tableHeadingFont') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Prayer name background</td>
                        <td><input type="text" name="prayerName" value="<?php echo get_option('prayerName') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Prayer name font</td>
                        <td><input type="text" name="prayerNameFont" value="<?php echo get_option('prayerNameFont') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Alternate row</td>
                        <td><input type="text" name="evenRow" value="<?php echo get_option('evenRow') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Highlight</td>
                        <td><input type="text" name="highlight" value="<?php echo get_option('highlight') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Notification/blink background</td>
                        <td><input type="text" name="notificationBackground" value="<?php echo get_option('notificationBackground') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Notification/blink Font</td>
                        <td><input type="text" name="notificationFont" value="<?php echo get_option('notificationFont') ?>" class="color-field"></td>
                    </tr>
                </table>
                <div style="width: 100%; height: 20px; border-bottom: 1px solid black; text-align: center; margin: 30px 0px 35px 0px;">
                    <span style="font-size: 20px; background-color: #F3F5F6; padding: 10px;">
                        Digital Screen backgrounds
                    </span>
                </div>
                <table class="table-sm">
                    <tr>
                        <td style="width: 70%">Prayer name</td>
                        <td><input type="text" name="digitalScreenPrayerName" value="<?php echo get_option('digitalScreenPrayerName') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Prayer begins</td>
                        <td><input type="text" name="digitalScreenLightRed" value="<?php echo get_option('digitalScreenLightRed') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Iqamah</td>
                        <td><input type="text" name="digitalScreenRed" value="<?php echo get_option('digitalScreenRed') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Next prayer section</td>
                        <td><input type="text" name="digitalScreenGreen" value="<?php echo get_option('digitalScreenGreen') ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type='submit' name='themeSettings' id='themeSettings' class='button button-primary' value='Update UI'>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
