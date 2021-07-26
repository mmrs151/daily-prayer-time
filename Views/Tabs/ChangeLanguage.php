<?php
require_once(__DIR__ . '/../TimetablePrinter.php');
$timetable = new TimetablePrinter();

?>
<h3>Change Language</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <form name="languageSettings" method="post">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th colspan="6" class="text-center bg-primary">Display prayer name in your language</th>
                    </tr>
                    <tr>
                        <?php $names = $timetable->getLocalPrayerNames();
                        foreach ($names as $key => $val) { ?>
                            <th class="text-center bg-info"><?php echo ucfirst(esc_attr($key)) ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php $prayers = $timetable->getLocalPrayerNames(true);
                        foreach ($prayers as $key => $val) { ?>
                            <td><input style="width:215px;" type="text" name="prayersLocal[<?php echo esc_attr($key); ?>]" value="<?php echo stripslashes(esc_attr($val)); ?>" /></td>
                        <?php } ?>
                    </tr>
                </table>
                <p>&nbsp;</p>
                <table class="table table-striped table-bordered">
                    <?php $months = $timetable->getLocalMonths(); ?>
                    <tr>
                        <th colspan=<?php echo  count($months); ?> class="text-center bg-primary">Translate month name in your own language</th>
                    </tr>
                    <tr>
                        <?php
                        foreach ($months as $key => $val) { ?>
                            <th class="text-center bg-info"><?php echo ucfirst(esc_html($key)); ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php $months = $timetable->getLocalMonths();
                        foreach ($months as $key => $val) { ?>
                            <td class="months"><input style="width:100px;" type="text" name="monthsLocal[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($val); ?>" /></td>
                        <?php } ?>
                    </tr>
                </table>
                <p>&nbsp;</p>
                <table class="table table-striped table-bordered">
                    <tr>
                        <th colspan="9" class="text-center bg-primary">Other table headings in your language</th>
                    </tr>
                    <tr>
                        <?php $headers = $timetable->getLocalHeaders();
                        foreach ($headers as $key => $val) { ?>
                            <th class="text-center bg-info"><?php echo ucfirst(esc_html($key)) ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php foreach ($headers as $key => $val) { ?>
                            <td><input style="width:155px;" class='other' type="text" name="headersLocal[<?php echo esc_attr($key); ?>]" value="<?php echo stripslashes(esc_attr($val)); ?>" /></td>
                        <?php } ?>
                    </tr>
                </table>
                <p>&nbsp;</p>
                <table class="table table-striped table-bordered">
                    <tr>
                        <th colspan="10" class="text-center bg-primary">Numbers in your language</th>
                    </tr>
                    <tr>
                        <?php $numbers = $timetable->getLocalNumbers();
                        foreach ($numbers as $key => $val) { ?>
                            <th class="text-center bg-info"><?php echo esc_html($key) ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php foreach ($numbers as $key => $val) { ?>
                            <td><input style="width:120px;" type="text" maxlength="1" size="1" name="numbersLocal[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($val); ?>" /></td>
                        <?php } ?>
                    </tr>
                </table>
                <p>&nbsp;</p>
                <table class="table table-striped table-bordered">
                    <tr>
                        <th colspan="10" class="text-center bg-primary">Time related values</th>
                    </tr>
                    <tr>
                        <?php $names = $timetable->getLocalTimes();
                        foreach ($names as $key => $val) { ?>
                            <th class="text-center bg-info"><?php echo ucfirst(esc_html($key)) ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php foreach ($names as $key => $val) { ?>
                            <td><input style="width:210px;" type="text" name="timesLocal[<?php echo esc_attr($key); ?>]" value="<?php echo stripslashes(esc_attr($val)); ?>" /></td>
                        <?php } ?>
                    </tr>
                </table>
                <div class="saveButton">
                    <?php
                    submit_button('Save changes', 'primary', 'languageSettings');
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>