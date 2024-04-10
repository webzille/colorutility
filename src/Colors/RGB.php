<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;

class RGB extends Color
{

    private float $r;

    private float $g;

    private float $b;

    function __construct($r, $g, $b)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    public function asArray(): array
    {
        return [$this->r, $this->g, $this->b];
    }

    public function asString(): string
    {
        return "rgb({$this->r}, {$this->g}, {$this->b})";
    }

    public function isLight(): bool
    {
        $brightness = ($this->r * 0.2126) + ($this->g * 0.7152) + ($this->b * 0.0722);

        return ceil($brightness) > 110;
    }

    public function white(): self
    {
        return new RGB(255, 255, 255);
    }

    public function black(): self
    {
        return new RGB(0, 0, 0);
    }

    public function calculateAngle(Color $color): float
    {
        return $this->asHSV()->calculateAngle($color);
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
        return $this->asHSV()->findColorByAngle($angle)->asRGB();
    }

    public function findColorAtDifference(float $difference): self
    {
        return $this->asLAB()->findColorAtDifference($difference)->asRGB();
    }

    public function findColorAtDistance(float $distance): self
    {
        return $this->asLAB()->findColorAtDistance($distance)->asRGB();
    }

    public function findColorByShade(int $shade): self
    {
        return $this->asLAB()->findColorByShade($shade)->asRGB();
    }

    public function linearDeviance(float $percent): self
    {
        return $this->asLAB()->linearDeviance($percent)->asRGB();
    }

    public function angularDeviance(float $percent): self
    {
        return $this->asLAB()->angularDeviance($percent)->asRGB();
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

    public function asHEX(): HEX
    {
        $r = str_pad(dechex((int) $this->r), 2, '0', STR_PAD_LEFT);
        $g = str_pad(dechex((int) $this->g), 2, '0', STR_PAD_LEFT);
        $b = str_pad(dechex((int) $this->b), 2, '0', STR_PAD_LEFT);

        return new HEX($r . $g . $b);
    }

    public function asLAB(): LAB
    {
        list($x, $y, $z) = $this->asXYZ();

        $x /= 95.047;
        $y /= 100.000;
        $z /= 108.883;

        $x = $x > 0.008856 ? pow($x, 1 / 3) : (7.787 * $x + 16 / 116);
        $y = $y > 0.008856 ? pow($y, 1 / 3) : (7.787 * $y + 16 / 116);
        $z = $z > 0.008856 ? pow($z, 1 / 3) : (7.787 * $z + 16 / 116);

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

    public function asRGBA(float $alpha = 1): RGBA
    {
        return new RGBA($this->r, $this->g, $this->b, $alpha);
    }

    public function asHSL(): HSL
    {
        $r = $this->r / 255.0;
        $g = $this->g / 255.0;
        $b = $this->b / 255.0;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $l = ($max + $min) / 2.0;

        if (abs($max - $min) < 0.00001) {
            return new HSL(0, 0, round($l * 100));
        }

        if ($l < 0.5) {
            $s = ($max - $min) / ($max + $min);
        } else {
            $s = ($max - $min) / (2.0 - $max - $min);
        }

        if ($max === $r) {
            $h = 60.0 * (($g - $b) / ($max - $min));
        } elseif ($max === $g) {
            $h = 60.0 * (($b - $r) / ($max - $min) + 2.0);
        } elseif ($max === $b) {
            $h = 60.0 * (($r - $g) / ($max - $min) + 4.0);
        }

        $h = round($h < 0 ? $h + 360 : ($h >= 360 ? $h - 360 : $h));

        $s = round($s * 100);
        $l = round($l * 100);

        return new HSL($h, $s, $l);
    }

    public function asHSLA(float $alpha = 1): HSLA
    {
        return $this->asHSL()->asHSLA($alpha);
    }

    public function asHSV(): HSV
    {
        $r = $this->r / 255;
        $g = $this->g / 255;
        $b = $this->b / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $v = $max;

        if ($max == $min) {
            $h = 0;
            $s = 0;
        } else {
            $delta = $max - $min;

            $s = $delta / $v;

            $h = 0;
            if ($max == $r) {
                $h = 60 * fmod(($g - $b) / $delta, 6);
            } elseif ($max == $g) {
                $h = 60 * ((($b - $r) / $delta) + 2);
            } else {
                $h = 60 * ((($r - $g) / $delta) + 4);
            }
        }

        $h = fmod($h, 360);

        $s *= 100;
        $v *= 100;

        return new HSV($h, $s, $v);
    }
}
