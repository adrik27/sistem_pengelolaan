<?php

function currency($value, $prefix = 'Rp ')
{
    return $prefix . number_format($value, 0, ',', '.');
}
