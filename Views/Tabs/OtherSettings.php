<h3>Other settings</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <form name="otherSettings" method="post" class="form-group">
                <table class="table">
                    <tr>
                        <td class="active-slider" style="width: 300px;">Jumu'ah</td>
                        <td><input style="width: 300px;" type="text" class="jumuah" name="jumuah" size="60" value='<?= get_option("jumuah") ?>'></td>
                    </tr>
                    <tr>
                        <td>Activate Ramadan timetable</td>
                        <td><input  type="checkbox" name="ramadan-chbox" value="ramadan" <?php if(get_option("ramadan-chbox") === 'ramadan'){ echo 'checked'; } ?>></td>
                    </tr>
                    <tr>
                        <td>Imsaq threshold:</td>
                        <td>
                            <input type="number" name="imsaq" min="0" max="59" placeholder="15" value="<?=get_option('imsaq')?>">
                        </td>
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
                            <input type="number" name="jamah_changes" min="0" max="59" placeholder="0" value="<?=get_option('jamah_changes')?>">
                            <i>'0' to deactivate</i>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save changes', 'primary', 'otherSettings'); ?>
            </form>
        </div>
    </div>
</div>
