<?php

namespace Webzille\ColorUtility;

use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\RGBA;

class SetColor {

    public static function fromString(string $colorString): Color|bool
    {
        $colorString = strtolower($colorString);

        foreach (Colors::$settable as $color => $colorClass) {
            if (strpos($colorString, strtolower($color)) !== false) {
                $colorValues = self::getColors($colorString);
                
                return new $colorClass(...$colorValues);
            }
        }

        if (preg_match(Colors::$hexPattern, $colorString, $matches)) {
            $hexColor = isset($matches[2]) ? $matches[2] : $matches[0];
            
            return new HEX($hexColor);
        }

        if (isset(Colors::$namedColors[$colorString])) {
            $colorValue = explode(',', Colors::$namedColors[$colorString]['rgb'] ?? Colors::$namedColors[$colorString]['rgba']);

            $colorClass = count($colorValue) === 3 ? RGB::class : RGBA::class;
            
            return new $colorClass(...$colorValue);
        }

        return false;
    }

    private static function getColors(string $colorString): array
    {
        preg_match_all(Colors::$valuePattern, $colorString, $matches);
        
        return $matches[0];
    }

    public static function random(): RGB
    {
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        
        return new RGB($r, $g, $b);
    }
}
