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
        $r = min(255, max(0, abs(round($this->r))));
        $g = min(255, max(0, abs(round($this->g))));
        $b = min(255, max(0, abs(round($this->b))));

        return "rgb($r, $g, $b)";
    }

    public function isLight(): bool
    {
        $brightness = ($this->r * 0.2126) + ($this->g * 0.7152) + ($this->b * 0.0722);

        return $brightness > 128;
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
        return $this->asRYB()->calculateAngle($color);
    }

    public function digitalDistance(Color $color): float
    {
        //return $this->asLAB()->digitalDistance($color);
        list($r1, $g1, $b1) = $this->asArray();
        list($r2, $g2, $b2) = $color->asRGB()->asArray();

        return sqrt(
            pow(($r2 - $r1), 2) +
                pow(($g2 - $g1), 2) +
                pow(($b2 - $b1), 2)
        );
    }

    public function findColorAtDistance(float $distance): RGB
    {
        return $this->asLAB()->findColorAtDistance($distance)->asRGB();

        $originalColor = $this->asRGB();
        $tolerance = 0.1;
        $maxIterations = 10000;

        for ($i = 0; $i < $maxIterations; $i++) {
            $rAdjustment = rand(-$distance, $distance);
            $gAdjustment = rand(-$distance, $distance);
            $bAdjustment = rand(-$distance, $distance);

            $newR = max(0, min(255, $originalColor->r + $rAdjustment));
            $newG = max(0, min(255, $originalColor->g + $gAdjustment));
            $newB = max(0, min(255, $originalColor->b + $bAdjustment));

            $newColor = new RGB($newR, $newG, $newB);
            $newDistance = $originalColor->digitalDistance($newColor);

            if (abs($newDistance - $distance) <= $tolerance) {
                return $newColor;
            }
        }

        return $originalColor;
    }

    public function visibleDifference(Color $color): float
    {
        return $this->asLAB()->visibleDifference($color);
    }

    public function findColorByAngle(float $angle): self
    {
        return $this->asRYB()->findColorByAngle($angle)->asRGB();
    }

    public function findColorAtDifference(float $difference): self
    {
        return $this->asLAB()->findColorAtDifference($difference)->asRGB();
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

        $fx = $x > 0.008856 ? pow($x, 1 / 3) : (7.787 * $x) + (16 / 116);
        $fy = $y > 0.008856 ? pow($y, 1 / 3) : (7.787 * $y) + (16 / 116);
        $fz = $z > 0.008856 ? pow($z, 1 / 3) : (7.787 * $z) + (16 / 116);

        $l = (116 * $fy) - 16;
        $a = 500 * ($fx - $fy);
        $b = 200 * ($fy - $fz);

        return new LAB(
            round($l, 2),
            round($a, 2),
            round($b, 2)
        );
    }

    public function asXYZ(): array
    {
        $r = $this->r / 255;
        $g = $this->g / 255;
        $b = $this->b / 255;

        $r = $r > 0.04045 ? pow(($r + 0.055) / 1.055, 2.4) : $r / 12.92;
        $g = $g > 0.04045 ? pow(($g + 0.055) / 1.055, 2.4) : $g / 12.92;
        $b = $b > 0.04045 ? pow(($b + 0.055) / 1.055, 2.4) : $b / 12.92;

        $x = $r * 0.4124564 + $g * 0.3575761 + $b * 0.1804375;
        $y = $r * 0.2126729 + $g * 0.7151522 + $b * 0.0721750;
        $z = $r * 0.0193339 + $g * 0.1191920 + $b * 0.9503041;

        $x /= 0.95047;
        $y /= 1.00000;
        $z /= 1.08883;

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
        list($r,$g, $b) = $this->asArray();

        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $h = 0;
        $s = 0;
        $l = ($max + $min) / 2;

        if ($max != $min) {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

            switch ($max) {
                case $r:
                    $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
                    break;
                case $g:
                    $h = ($b - $r) / $d + 2;
                    break;
                case $b:
                    $h = ($r - $g) / $d + 4;
                    break;
            }

            $h *= 60;
        }

        $s *= 100;
        $l *= 100;

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

    function asRYB(): RYB
    {
        list($r, $g, $b) = $this->asArray();

        $w = min($r, $g, $b);
        $r -= $w;
        $g -= $w;
        $b -= $w;

        $mg = max($r, $g, $b);

        $y = min($r, $g);
        $r -= $y;
        $g -= $y;

        if ($b && $g) {
            $b /= 2.0;
            $g /= 2.0;
        }

        $y += $g;
        $b += $g;

        $my = max($r, $y, $b);
        if ($my) {
            $n = $mg / $my;
            $r *= $n;
            $y *= $n;
            $b *= $n;
        }

        $r += $w;
        $y += $w;
        $b += $w;

        return new RYB($r, $y, $b);
    }
}
