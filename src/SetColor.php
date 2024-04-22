<?php

namespace Webzille\ColorUtility;

use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\HSL;
use Webzille\ColorUtility\Colors\HSLA;
use Webzille\ColorUtility\Colors\RGBA;

class SetColor {

    private static array $colors = [
        'rgba'  => RGBA::class,
        'rgb'   => RGB::class,
        'hsla'  => HSLA::class,
        'hsl'   => HSL::class,
    ];

    private static string $namedColors = "NamedColorsLookupTable.php";

    public static function fromString(string $colorString)
    {
        $colorString = strtolower($colorString);

        foreach (self::$colors as $color => $colorClass) {
            if (strpos($colorString, strtolower($color)) !== false) {
                $colorValues = self::getColors($colorString);
                
                return new $colorClass(...$colorValues);
            }
        }

        if (preg_match('/^(#)?([0-9a-fA-F]{2,4}|[0-9a-fA-F]{6,8})$/', $colorString, $matches)) {
            $hexColor = isset($matches[2]) ? $matches[2] : $matches[0];
            
            return new HEX($hexColor);
        }

        $namedColors = require self::$namedColors;

        if (isset($namedColors[$colorString])) {
            $colorValue = explode(',', $namedColors[$colorString]['rgb'] ?? $namedColors[$colorString]['rgba']);

            $colorClass = count($colorValue) === 3 ? RGB::class : RGBA::class;
            
            return new $colorClass(...$colorValue);
        }

        return false;
    }

    private static function getColors($colorString) {
        preg_match_all('/(?:\.\d+|\d+(?:\.\d+)?)/', $colorString, $matches);
        
        return $matches[0];
    }
}
