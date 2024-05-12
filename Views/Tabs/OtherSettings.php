<h3>Other settings</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8 col-xs-8">
            <form name="otherSettings" method="post" class="form-group">
            <?php echo wp_nonce_field( 'otherSettings'); ?>
                <table class="table">
                    <tr>
                        <td>Jumu'ah</td>
                        <td><input type="time" class="jumuah" name="jumuah1" value='<?php echo  get_option("jumuah1") ?>'></td>
                        <td><input type="time" class="jumuah" name="jumuah2" value='<?php echo  get_option("jumuah2") ?>'></td>
                        <td><input type="time" class="jumuah" name="jumuah3" value='<?php echo  get_option("jumuah3") ?>'></td>
                    </tr>
                    <tr>
                        <td>Screen Timeout during Khutbah</td>
                        <td colspan="3"><input style="width: 50px;" type="text" class="jumuah" name="khutbahDim" size="10" value='<?php echo  get_option("khutbahDim") ?>'></td>
                    </tr>
                    <tr>
                        <td>Set Asr start time for monthly calendar</td>
                        <td colspan="3">
                            <select name="asrSelect" class="form-control">
                                <option value="both" <?php if(get_option("asrSelect") === 'both'){ echo 'selected="selected"'; } ?>>Both</option>
                                <option value="hanafi" <?php if(get_option("asrSelect") === 'hanafi'){ echo 'selected="selected"'; } ?>>Hanafi</option>
                                <option value="standard" <?php if(get_option("asrSelect") === 'standard'){ echo 'selected="selected"'; } ?>>Maliki/Shafi'i/Hanbali</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Iqamah time change threshold:</td>
                        <td colspan="3">
                            <input type="number" name="jamah_changes" min="0" max="59" placeholder="0" value="<?php echo esc_attr(get_option('jamah_changes'))?>">
                            <i>'0' = disabled</i>
                        </td>
                    </tr>
                    <tr>
                        <td>Deactivate Tomorrow:</td>
                        <td colspan="3"><input  type="checkbox" name="tomorrow_time" value="tomorrow" <?php if(get_option("tomorrow_time") === 'tomorrow'){ echo 'checked'; } ?>></td>
                    </tr> 
                    <tr>
                        <td>Display Zawal:</td>
                        <td colspan="3">
                            <input data-toggle="tooltip" title="set minutes to subtract from Zuhr start time" type="number" name="zawal" min="0" max="59" placeholder="0" value="<?php echo esc_attr(get_option('zawal'))?>">
                            <i>'0' = disabled</i>
                        </td>
                    </tr>                 
                </table>
                <?php submit_button('Save changes', 'primary', 'otherSettings'); ?>
            </form>
        </div>
    </div>
</div>
