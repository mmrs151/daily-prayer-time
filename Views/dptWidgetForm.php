<?php

require_once('TimetablePrinter.php');
$timetablePrinter = new TimetablePrinter();
?>
<div xmlns="http://www.w3.org/1999/html">
    <span>
    </br></br>

        Title Before Date <input
            name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>"
            type="text"
            value="<?php echo esc_html($instance["title"]) ?>"
            /></br></br>

        Select Azan/Iqamah settings
        <select name="<?php echo esc_attr($this->get_field_name( 'azanIqamah' )); ?>">
            <option value="" <?php if($instance["azanIqamah"] === ''){ echo 'selected="selected"'; } ?>>Default</option>
            <option value="jamahOnly" <?php if($instance["azanIqamah"] === 'jamahOnly'){ echo 'selected="selected"'; } ?>>Iqamah Only</option>
            <option value="azanOnly" <?php if($instance["azanIqamah"] === 'azanOnly'){ echo 'selected="selected"'; } ?>>Azan Only</option>
        </select></br></br>

        <input
            type="checkbox"
            name="<?php echo esc_attr($this->get_field_name( 'hanafiAsr' )); ?>"
            value="hanafiAsr"
            <?php if($instance["hanafiAsr"] === 'hanafiAsr'){ echo 'checked="checked"'; } ?>
        /> Display Asr start time according to Hanafi school</br></br>

        <input
            type="checkbox"
            name="<?php echo esc_attr($this->get_field_name( 'choice' )); ?>"
            value="horizontal"
            <?php if($instance["choice"] === 'horizontal'){ echo 'checked="checked"'; } ?>
        /> Display prayer time horizontally</br></br>

        <input
            type="checkbox"
            name="<?php echo esc_attr($this->get_field_name( 'hideTimeRemaining' )); ?>"
            value="hideTimeRemaining"
            <?php if($instance["hideTimeRemaining"] === 'hideTimeRemaining'){ echo 'checked="checked"'; } ?>
        /> Hide time remaining for next IQAMAH</br></br>

        <?php if ( $timetablePrinter->isRamadan() ) { ?>
        <input
            type="checkbox"
            name="<?php echo esc_attr($this->get_field_name( 'hideRamadan' )); ?>"
            value="hideRamadan"
            <?php if($instance["hideRamadan"] === 'hideRamadan'){ echo 'checked="checked"'; } ?>
        /> Hide Ramadan time</br></br>
        <?php } ?>

        Announcement
        <select name="<?php echo esc_attr($this->get_field_name( 'announcementDay' )); ?>">
            <?php
            $days =  array('everyday','friday','saturday','sunday','monday','tuesday','wednesday','thursday');

            foreach ($days as $day) { ?>
                <option value="<?php echo $day?>" <?php if($instance["announcementDay"] === $day){ echo 'selected="selected"'; } ?>><?php echo ucfirst($day); ?></option>
            <?php }
            ?>
        </select>
        </br>

        <textarea rows="4" cols="30" maxlength="140"
            name="<?php echo esc_attr($this->get_field_name( 'announcement' )); ?>"
            placeholder="Display announcement on your given day or everyday"
            ><?php echo esc_html($instance['announcement']) ?></textarea></br></br>
    </span>
</div>
