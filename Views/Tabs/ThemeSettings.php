<h2>Theme settings</h2>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <form name="themeSettings" method="post">
            <?php echo wp_nonce_field( 'themeSettings'); ?>
                <div style="width: 100%; height: 20px; border-bottom: 1px solid black; text-align: center; margin-bottom: 30px;">
                    <span style="font-size: 20px; background-color: #F3F5F6; padding: 10px;">
                        Daily Prayer time table
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
                        <td>Font color</td>
                        <td><input type="text" name="fontColor" value="<?php echo esc_attr(get_option('fontColor')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Table heading</td>
                        <td><input type="text" name="tableHeading" value="<?php echo esc_attr(get_option('tableHeading')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Table heading font</td>
                        <td><input type="text" name="tableHeadingFont" value="<?php echo esc_attr(get_option('tableHeadingFont')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Prayer name background</td>
                        <td><input type="text" name="prayerName" value="<?php echo esc_attr(get_option('prayerName')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Prayer name font</td>
                        <td><input type="text" name="prayerNameFont" value="<?php echo esc_attr(get_option('prayerNameFont')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Alternate row</td>
                        <td><input type="text" name="evenRow" value="<?php echo esc_attr(get_option('evenRow')) ?>" class="color-field"></td>
                    </tr>
                    <tr><td><hr></td><td><strong>DIGITAL SCREEN</strong></td></tr>
                    <tr>
                        <td>Background color</td>
                        <td><input type="text" name="tableBackground" value="<?php echo esc_attr(get_option('tableBackground')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Highlight next prayer</td>
                        <td><input type="text" name="highlight" value="<?php echo esc_attr(get_option('highlight')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Announcement/Blink Background</td>
                        <td><input type="text" name="notificationBackground" value="<?php echo esc_attr(get_option('notificationBackground')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Announcement/Blink Font</td>
                        <td><input type="text" name="notificationFont" value="<?php echo esc_attr(get_option('notificationFont')) ?>" class="color-field"></td>
                    </tr>

                    <tr>
                        <td style="width: 70%">Prayer name background</td>
                        <td><input type="text" name="digitalScreenPrayerName" value="<?php echo esc_attr(get_option('digitalScreenPrayerName')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Prayer begins background</td>
                        <td><input type="text" name="digitalScreenLightRed" value="<?php echo esc_attr(get_option('digitalScreenLightRed')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Iqamah background</td>
                        <td><input type="text" name="digitalScreenRed" value="<?php echo esc_attr(get_option('digitalScreenRed')) ?>" class="color-field"></td>
                    </tr>
                    <tr>
                        <td>Next prayer background</td>
                        <td><input type="text" name="digitalScreenGreen" value="<?php echo esc_attr(get_option('digitalScreenGreen')) ?>" class="color-field"></td>
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
