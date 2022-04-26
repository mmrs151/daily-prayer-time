<?php 
$hijri = new HijriDate();

?>
<h3>Hijri date settings</h3>
<h5>Today is: <i class="text-primary"><?php echo $hijri->getToday();?></i></h5>

<div class="container-fluid">
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
                        <td>Display in Arabic:</td>
                        <td>
                            <input type="checkbox" name="hijri-arabic-chbox" value="hijri-arabic" <?php if(get_option("hijri-arabic-chbox") === 'hijri-arabic'){ echo 'checked'; } ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>Adjust day:</td>
                        <td>
                            <input type="number" name="hijri-adjust" min="-2" max="2" value="<?php echo esc_attr(get_option('hijri-adjust'))?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Imsaq threshold:</td>
                        <td>
                            <input type="number" name="imsaq" min="0" max="59" placeholder="15" value="<?php echo esc_attr(get_option('imsaq'))?>">
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save changes', 'primary', 'hijriSettings'); ?>
            </form>
        </div>
    </div>
</div>

