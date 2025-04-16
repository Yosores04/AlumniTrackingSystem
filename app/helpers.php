<?php

/**
 * Convert hex color to RGB values
 * 
 * @param string $hex Hex color code (with or without #)
 * @return array Array containing R, G, B values
 */
function hex2rgb($hex) 
{
    $hex = str_replace('#', '', $hex);
    
    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    
    return [$r, $g, $b];
}

/**
 * Convert hex color to RGB string
 * 
 * @param string $hex Hex color code (with or without #)
 * @return string Comma-separated RGB values (e.g. "255, 255, 255")
 */
function hex2rgbString($hex) 
{
    $rgb = hex2rgb($hex);
    return implode(', ', $rgb);
}

/**
 * Get CSS rgba() string from hex color and opacity
 * 
 * @param string $hex Hex color code (with or without #)
 * @param float $opacity Opacity value (0-1)
 * @return string Complete rgba() CSS function
 */
function hexToRgba($hex, $opacity = 1) 
{
    $rgb = hex2rgb($hex);
    return 'rgba(' . implode(', ', $rgb) . ', ' . $opacity . ')';
} 