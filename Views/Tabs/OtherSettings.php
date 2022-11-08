<h3>Other settings</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <form name="otherSettings" method="post" class="form-group">
                <table class="table">
                    <tr>
                        <td class="active-slider" style="width: 300px;">Jumu'ah</td>
                        <td><input style="width: 300px;" type="text" class="jumuah" name="jumuah" size="60" value='<?php echo  get_option("jumuah") ?>'></td>
                    </tr>
                    <tr>
                        <td>Set Asr start time for monthly calendar</td>
                        <td>
                            <select name="asrSelect" class="form-control">
                                <option value="both" <?php if(get_option("asrSelect") === 'both'){ echo 'selected="selected"'; } ?>>Both</option>
                                <option value="hanafi" <?php if(get_option("asrSelect") === 'hanafi'){ echo 'selected="selected"'; } ?>>Hanafi</option>
                                <option value="standard" <?php if(get_option("asrSelect") === 'standard'){ echo 'selected="selected"'; } ?>>Maliki/Shafi'i/Hanbali</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Iqamah time change threshold:</td>
                        <td>
                            <input type="number" name="jamah_changes" min="0" max="59" placeholder="0" value="<?php echo esc_attr(get_option('jamah_changes'))?>">
                            <i>'0' to deactivate</i>
                        </td>
                    </tr>
                    <tr>
                        <td>Deactivate Tomorrow:</td>
                            <td><input  type="checkbox" name="tomorrow_time" value="tomorrow" <?php if(get_option("tomorrow_time") === 'tomorrow'){ echo 'checked'; } ?>></td>
                    </tr>
                    <tr>
                        <td>Activate Ramadan:</td>
                            <td><input  type="checkbox" name="ramadan_chbox" value="isRamadan" <?php if(get_option("ramadan_chbox") === 'isRamadan'){ echo 'checked'; } ?>></td>
                    </tr>
                </table>
                <?php submit_button('Save changes', 'primary', 'otherSettings'); ?>
            </form>
        </div>
    </div>
</div>
