<?php
$hd = new HijriDate();
$date = $hd->getDate(date("d"), date("m"), date("Y"), true);
?>
<h3>Hijri date settings</h3>
<div class="container-fluid">
    <div class="row todaySpan">
        <div class="col-sm-6 col-xs-12">
            <span>Today is <?= $date?><span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <form name="hijriSettings" method="post">
                <table class="table">
                    <tr>
                        <td>Display Hijri date:</td>
                        <td>
                            <input type="checkbox" name="hijri-chbox" value="hijri" <?php if(get_option("hijri-chbox") === 'hijri'){ echo 'checked'; } ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>Adjust day:</td>
                        <td>
                            <input type="number" name="hijri-adjust" min="-2" max="2" value="<?=get_option('hijri-adjust')?>">
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save changes', 'primary', 'hijriSettings'); ?>
            </form>
        </div>
    </div>
</div>

