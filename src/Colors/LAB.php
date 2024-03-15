<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Calculations\DeltaE2000;
use Webzille\ColorUtility\Color;
use Webzille\ColorUtility\Colors\CylindricalLAB;
use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\HSL;
use Webzille\ColorUtility\Colors\HSLA;
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RGBA;

class LAB extends Color {

    private float $L;

    private float $a;

    private float $b;

    function __construct($L, $a, $b)
    {
        $this->L = $L;
        $this->a = $a;
        $this->b = $b;
    }

    public function asArray(): array
    {
        return [$this->L, $this->a, $this->b];
    }

    public function asString(): string
    {
        return "{$this->L}, {$this->a}, {$this->b}";
    }

    public function isLight(): bool
    {
        return $this->L > 50;
    }

    public function white(): self
    {
        return new LAB(100, 0, 0);
    }

    public function black(): self
    {
        return new LAB(0, 0, 0);
    }

    public function calculateAngle(Color $color): float
    {
        list($z1, $x1, $y1) = $this->asArray();
        list($z2, $x2, $y2) = $color->asLAB()->asArray();

        $dotProduct = $x1 * $x2 + $y1 * $y2 + $z1 * $z2;

        $magnitude1 = sqrt($x1 ** 2 + $y1 ** 2 + $z1 ** 2);
        $magnitude2 = sqrt($x2 ** 2 + $y2 ** 2 + $z2 ** 2);

        $angleRadians = acos($dotProduct / ($magnitude1 * $magnitude2));

        return rad2deg($angleRadians);
    }

    public function findColorByAngle($angle): self
    {
        $angleRad = deg2rad($angle);

        $deltaA = cos($angleRad);
        $deltaB = sin($angleRad);

        $newA = $this->a * $deltaA - $this->b * $deltaB;
        $newB = $this->a * $deltaB + $this->b * $deltaA;

        return new LAB($this->L, $newA, $newB);
    }

    public function digitalDistance(Color $color): float
    {
        list($L1, $a1, $b1) = $this->asArray();
        list($L2, $a2, $b2) = $color->asLAB()->asArray();

        return sqrt((($L2 - $L1) ** 2) + (($a2 - $a1) ** 2) + (($b2 - $b1) ** 2));
    }

    public function findColorAtDistance(float $distance): LAB
    {
        $tolerance = 0.5;

        $bestDistance = PHP_FLOAT_MAX;
        $bestColor = clone $this;

        $currentStepSize = 128;

        for ($i = 0; $i < 5000; $i++)
        {
            $midpoint = clone $bestColor;
            
            $LAdjustment = rand((int) -$currentStepSize, (int) $currentStepSize);
            $aAdjustment = rand((int) -$currentStepSize, (int) $currentStepSize);
            $bAdjustment = rand((int) -$currentStepSize, (int) $currentStepSize);
            
            $midpoint->L = max(0, min(100, $midpoint->L + $LAdjustment));
            $midpoint->a = max(-128, min(127, $midpoint->a + $aAdjustment));
            $midpoint->b = max(-128, min(127, $midpoint->b + $bAdjustment));

            $digitalDistance = $this->digitalDistance($midpoint);
            
            if (abs($digitalDistance - $distance) < $tolerance) {
                return $midpoint;
            }

            if (abs($digitalDistance - $distance) < abs($bestDistance - $distance)) {
                $bestDistance = $digitalDistance;
                $bestColor = clone $midpoint;
            }

            $stepAdjustment = 2 + ($digitalDistance - $distance) / 950;
            $currentStepSize *= $stepAdjustment;

            $currentStepSize = max(1, min(128, $currentStepSize));
        }

        return $bestColor;
    }

    public function visibleDifference(Color $color): float
    {
        return (new DeltaE2000)->calculate($this, $color->asLAB());
    }

    public function findColorAtDifference(float $difference): self
    {
        $tolerance = 0.5;

        $bestDeltaE = PHP_FLOAT_MAX;
        $bestColor = clone $this;

        $currentStepSize = 128;

        for ($i = 0; $i < 5000; $i++)
        {
            $midpoint = clone $bestColor;
            
            $aAdjustment = rand((int) -$currentStepSize, (int) $currentStepSize);
            $bAdjustment = rand((int) -$currentStepSize, (int) $currentStepSize);
            
            $midpoint->a = max(-128, min(127, $midpoint->a + $aAdjustment));
            $midpoint->b = max(-128, min(127, $midpoint->b + $bAdjustment));

            $deltaE = $this->visibleDifference($midpoint);
            
            if (abs($deltaE - $difference) < $tolerance) {
                return $midpoint;
            }

            if (abs($deltaE - $difference) < abs($bestDeltaE - $difference)) {
                $bestDeltaE = $deltaE;
                $bestColor = clone $midpoint;
            }

            $stepAdjustment = 2 + ($deltaE - $difference) / 950;
            $currentStepSize *= $stepAdjustment;

            $currentStepSize = max(1, min(128, $currentStepSize));
        }

        return $bestColor;
    }

    public function linearDeviance(float $percent): self
    {
        if ($percent === 0) {
            return clone $this;
        }

        $percent = ($percent > 100) ? $percent - 100 : $percent;

        $lightnessScale = 0.6667;
        $positiveA = $this->a >= 0;
        $positiveB = $this->b >= 0;
        $adjustedPercent = $percent / 100;

        $coefficientA = ($positiveA) ? 128 : 127;
        $coefficientB = ($positiveB) ? 128 : 127;

        $newL = max($this->L, min(100, $this->L * $lightnessScale + $percent * $lightnessScale));
        $rangeA = ((abs($this->a) + $coefficientA) * $adjustedPercent);
        $rangeB = ((abs($this->b) + $coefficientB) * $adjustedPercent);

        $newA = ($positiveA) ? $this->a - $rangeA : $this->a + $rangeA;
        $newB = ($positiveB) ? $this->b - $rangeB : $this->b + $rangeB;

        return new LAB($newL, max(-128, min(127, $newA)), max(-128, min(127, $newB)));
    }

    public function angularDeviance(float $percent): self
    {
        $percent = ($percent > 200) ? ($percent - 200) / 100 : $percent / 100;

        return $this->findColorByAngle(180 * $percent);
    }

    public function findColorByShade(int $shade): self
    {
        return new LAB($shade, $this->a, $this->b);
    }

    public function getLightness(): float
    {
        return $this->L;
    }

    public function getA(): float
    {
        return $this->a;
    }

    public function getB(): float
    {
        return $this->b;
    }

    public function asRGB(): RGB
    {
        list($x, $y, $z) = $this->asXYZ();

        $r = $x *  3.2406 + $y * -1.5372 + $z * -0.4986;
        $g = $x * -0.9689 + $y *  1.8758 + $z *  0.0415;
        $b = $x *  0.0557 + $y * -0.2040 + $z *  1.0570;

        $r = $r > 0.0031308 ? 1.055 * pow($r, 1 / 2.4) - 0.055 : 12.92 * $r;
        $g = $g > 0.0031308 ? 1.055 * pow($g, 1 / 2.4) - 0.055 : 12.92 * $g;
        $b = $b > 0.0031308 ? 1.055 * pow($b, 1 / 2.4) - 0.055 : 12.92 * $b;

        $r = round(max(0, min(255, $r * 255)));
        $g = round(max(0, min(255, $g * 255)));
        $b = round(max(0, min(255, $b * 255)));

        return new RGB($r, $g, $b);
    }

    public function asRGBA(float $alpha = 1): RGBA
    {
        return $this->asRGB()->asRGBA($alpha);
    }

    public function asXYZ(): array
    {
        $y = ($this->L + 16) / 116;
        $x = $this->a / 500 + $y;
        $z = $y - $this->b / 200;

        $x = 0.95047 * (($x ** 3) > 0.008856 ? $x ** 3 : ($x - 16 / 116) / 7.787);
        $y = 1.00000 * (($y ** 3) > 0.008856 ? $y ** 3 : ($y - 16 / 116) / 7.787);
        $z = 1.08883 * (($z ** 3) > 0.008856 ? $z ** 3 : ($z - 16 / 116) / 7.787);

        return [$x, $y, $z];
    }

    public function xyzToLAB($xyz): self
    {
        list($x, $y, $z) = $xyz;

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

    public function asHEX(): HEX
    {
        return $this->asRGB()->asHEX();
    }

    public function asCylindrical(): CylindricalLAB
    {
        $h = atan2($this->b, $this->a);
        if ($h < 0) {
            $h += 2 * M_PI;
        }

        $c = sqrt($this->a ** 2 + $this->b ** 2);

        return new CylindricalLAB($this->L, $c, $h);
    }

    public function asHSL(): HSL
    {
        return $this->asRGB()->asHSL();
    }

    public function asHSLA(float $alpha = 1): HSLA
    {
        return $this->asRGB()->asHSLA($alpha);
    }
}