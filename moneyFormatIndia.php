<?php
function moneyFormatIndia($num)
{
    // 🔹 FIX: handle -0 / 0 / 0.00
    if ((float)$num == 0) {
        return '0';
    }

    $isNegative = false;
    if ($num < 0) {
        $isNegative = true;
        $num = abs($num);
    }

    $numStr = (string)$num;
    $parts = explode('.', $numStr);
    $intPart = $parts[0];
    $decPart = isset($parts[1]) ? '.' . $parts[1] : '';

    $len = strlen($intPart);
    if ($len <= 3) {
        $formatted = $intPart;
    } else {
        $lastThree = substr($intPart, -3);
        $rest = substr($intPart, 0, -3);
        $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest);
        $formatted = $rest . "," . $lastThree;
    }

    return ($isNegative ? '-' : '') . $formatted . $decPart;
}
?>