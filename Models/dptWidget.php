<?php

$timeTable = new DailyShortCode();

if (! empty($instance['announcement']) && ! empty($instance['announcementDay']) ) {
    $timeTable->setAnnouncement($instance['announcement'], $instance['announcementDay'] );
}

if (! empty($instance['hideRamadan'])) {
    $timeTable->hideRamadan();
}

if (! empty($instance['hideTimeRemaining'])) {
    $timeTable->hideTimeRemaining();
}

$hijriCheckbox = get_option('hijri-chbox');
if (! empty($hijriCheckbox)) {
    $timeTable->displayHijriDate();
}

if ($instance['azanIqamah'] == 'jamahOnly') {
    $timeTable->setJamahOnly();
}

if ($instance['azanIqamah'] == 'azanOnly') {
    $timeTable->setAzanOnly();
}

if (! empty($instance['hanafiAsr'])) {
    $timeTable->setHanafiAsr();
}

if (! empty($instance['title'])) {
    $timeTable->setTitle($instance['title']);
}

if ($instance['choice'] === 'horizontal') {
    echo wp_kses($timeTable->horizontalTime(), 'post');
} else {
    echo wp_kses($timeTable->verticalTime(), 'post');
}
