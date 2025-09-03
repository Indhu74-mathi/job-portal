<?php
function time_ago($datetime) {
    if (!$datetime) return '';
    $ts = is_numeric($datetime) ? (int)$datetime : strtotime($datetime);
    $diff = time() - $ts;
    if ($diff < 60) return 'just now';
    $units = [
        31536000 => 'yr',
        2592000  => 'mo',
        604800   => 'wk',
        86400    => 'day',
        3600     => 'hr',
        60       => 'min'
    ];
    foreach ($units as $secs => $name) {
        if ($diff >= $secs) {
            $val = floor($diff / $secs);
            return $val . ' ' . $name . ($val > 1 && $name !== 'min' ? 's' : '') . ' ago';
        }
    }
    return 'just now';
}
