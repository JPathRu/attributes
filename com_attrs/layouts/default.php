<?php defined('_JEXEC') or die;

$value = $displayData['value'];
if (is_array($value)) {
    $value = implode(', ', $value);
}

echo $value;
