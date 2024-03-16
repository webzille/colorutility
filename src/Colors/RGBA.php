<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;
use Webzille\ColorUtility\Colors\CylindricalLAB;
use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\HSL;
use Webzille\ColorUtility\Colors\HSLA;
use Webzille\ColorUtility\Colors\LAB;
use Webzille\ColorUtility\Colors\RGB;

class RGBA extends Color {

    private float $r;

    private float $g;

    private float $b;

    private float $alpha;

    function __construct($r, $g, $b, $alpha = 1)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
        $this->alpha = $alpha;
    }

    public function asArray(): array
    {
        return [$this->r, $this->g, $this->b, $this->alpha];
    }

    public function asString(): string
    {
        return "rgba({$this->r}, {$this->g}, {$this->b}, {$this->alpha})";
    }

    public function isLight(): bool
    {
        $brightness = ($this->r * 0.2126) + ($this->g * 0.7152) + ($this->b * 0.0722);
        
        return ceil($brightness) > 110;
    }

    public function white(): self
    {
        return new RGBA(255, 255, 255, 1);
    }

    public function black(): self
    {
        return new RGBA(0, 0, 0, 1);
    }

    public function calculateAngle(Color $color): float
    {
        return $this->asLAB()->calculateAngle($color);
    }

    public function digitalDistance(Color $color): float
    {
        return $this->asLAB()->digitalDistance($color);
    }

    public function visibleDifference(Color $color): float
    {
        return $this->asLAB()->visibleDifference($color);
    }

    public function findColorByAngle(float $angle): self
    {
        return $this->asLAB()->findColorByAngle($angle)->asRGBA($this->alpha);
    }

    public function findColorAtDifference(float $difference): self
    {
        return $this->asLAB()->findColorAtDifference($difference)->asRGBA($this->alpha);
    }

    public function findColorAtDistance(float $distance): RGBA
    {
        return $this->asLAB()->findColorAtDistance($distance)->asRGBA();
    }

    public function findColorByShade(int $shade): self
    {
        return $this->asLAB()->findColorByShade($shade)->asRGBA($this->alpha);
    }

    public function linearDeviance(float $percent): self
    {
        return $this->asLAB()->linearDeviance($percent)->asRGBA($this->alpha);
    }

    public function angularDeviance(float $percent): self
    {
        return $this->asLAB()->angularDeviance($percent)->asRGBA($this->alpha);
    }

    public function getRed(): float
    {
        return $this->r;
    }

    public function getGreen(): float
    {
        return $this->g;
    }

    public function getBlue(): float
    {
        return $this->b;
    }

    public function getAlpha(): float
    {
        return $this->alpha;
    }

    public function asRGB(): RGB
    {
        return new RGB($this->r, $this->g, $this->b);
    }

    public function asHEX(): HEX
    {
        $r = str_pad(dechex($this->r), 2, '0', STR_PAD_LEFT);
        $g = str_pad(dechex($this->g), 2, '0', STR_PAD_LEFT);
        $b = str_pad(dechex($this->b), 2, '0', STR_PAD_LEFT);

        return new HEX($r . $g . $b);
    }

    public function asLAB(): LAB
    {
        list($x, $y, $z) = $this->asXYZ();

        $x /= 95.047;
        $y /= 100.000;
        $z /= 108.883;

        $x = $x > 0.008856 ? pow($x, 1/3) : (7.787 * $x + 16 / 116);
        $y = $y > 0.008856 ? pow($y, 1/3) : (7.787 * $y + 16 / 116);
        $z = $z > 0.008856 ? pow($z, 1/3) : (7.787 * $z + 16 / 116);

        $L = max(0, 116 * $y - 16);
        $a = 500 * ($x - $y);
        $b = 200 * ($y - $z);

        return new LAB($L, $a, $b);
    }

    public function asXYZ(): array
    {
        $r = $this->r / 255;
        $g = $this->g / 255;
        $b = $this->b / 255;

        $r = (($r > 0.04045) ? pow(($r + 0.055) / 1.055, 2.4) : $r / 12.92) * 100;
        $g = (($g > 0.04045) ? pow(($g + 0.055) / 1.055, 2.4) : $g / 12.92) * 100;
        $b = (($b > 0.04045) ? pow(($b + 0.055) / 1.055, 2.4) : $b / 12.92) * 100;

        $x = $r * 0.4124564 + $g * 0.3575761 + $b * 0.1804375;
        $y = $r * 0.2126729 + $g * 0.7151522 + $b * 0.0721750;
        $z = $r * 0.0193339 + $g * 0.1191920 + $b * 0.9503041;

        return [$x, $y, $z];
    }

    public function asCylindrical(): CylindricalLAB
    {
        return $this->asLAB()->asCylindrical();
    }

    public function asHSL(): HSL
    {
        list($r, $g, $b) = $this->asArray();
        
        $r = $r / 255.0;
        $g = $g / 255.0;
        $b = $b / 255.0;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $lum = ($max + $min) / 2.0;
        $hue = 0.0;
        $sat = 0.0;

        if ($max != $min) {
            $c = $max - $min;

            $sat = $c / (1.0 - abs(2.0 * $lum - 1.0));

            switch ($max) {
                case $r:
                    $hue = ($g - $b) / $c;
                    break;
                case $g:
                    $hue = ($b - $r) / $c + 2.0;
                    break;
                case $b:
                    $hue = ($r - $g) / $c + 4.0;
                    break;
            }

            $hue = ($hue < 0.0 ? $hue + 6.0 : $hue);
        }

        $hue = round($hue * 60.0);
        $sat = round($sat * 100.0);
        $lum = round($lum * 100.0);

        return new HSL($hue, $sat, $lum);
    }

    public function asHSLA(float $alpha = null): HSLA
    {
        $alpha = ($alpha === null) ? $this->alpha : $alpha;
        
        return $this->asHSL()->asHSLA($alpha);
    }
}
