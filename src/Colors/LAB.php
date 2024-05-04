<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;

class LAB extends Color
{

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
        parent::__construct();
        
        $L = round($this->L);
        $a = round($this->a);
        $b = round($this->b);

        return "LAB({$L}, {$a}, {$b})";
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
        list($L1, $a1, $b1) = $this->asArray();
        list($L2, $a2, $b2) = $color->asLAB()->asArray();

        $dotProduct = $a1 * $a2 + $b1 * $b2 + $L1 * $L2;

        $magnitude1 = sqrt($a1 ** 2 + $b1 ** 2 + $L1 ** 2);
        $magnitude2 = sqrt($a2 ** 2 + $b2 ** 2 + $L2 ** 2);

        $angleRadians = acos($dotProduct / ($magnitude1 * $magnitude2));

        return rad2deg($angleRadians);
    }

    public function findColorByAngle(float $angle): self
    {
        $angleRadians = deg2rad($angle);

        $newA = $this->a * cos($angleRadians) - $this->b * sin($angleRadians);
        $newB = $this->a * sin($angleRadians) + $this->b * cos($angleRadians);

        return new LAB($this->L, $newA, $newB);
    }

    public function findColorByDistance(float $distance, float $tolerance = 0.1, int $maxIterations = 10000): self
    {
        list($L1, $a1, $b1) = $this->asArray();

        $minScale = 0.1;

        for ($i = 0; $i < $maxIterations; $i++) {
            $adjustment = [
                'L' => mt_rand(-100, 100) / 100,
                'a' => mt_rand(-100, 100) / 100,
                'b' => mt_rand(-100, 100) / 100,
            ];

            $newL = max(0, min(100, $L1 + $adjustment['L']));
            $newA = max(-128, min(127, $a1 + $adjustment['a']));
            $newB = max(-128, min(127, $b1 + $adjustment['b']));

            $newColor = new LAB($newL, $newA, $newB);
            $newDistance = $this->digitalDistance($newColor);
            $distanceDiff = abs($newDistance - $distance);
            $scaleFactor = $minScale + ($distance - $minScale) * ($distanceDiff / $distance);

            if ($distanceDiff <= $tolerance) {
                return $newColor;
            }

            $L1 = max(0, min(100, $L1 + $adjustment['L'] * $scaleFactor));
            $a1 = max(-128, min(127, $a1 + $adjustment['a'] * $scaleFactor));
            $b1 = max(-128, min(127, $b1 + $adjustment['b'] * $scaleFactor));
        }
        
        return new LAB($L1, $a1, $b1);
    }

    public function findColorByDifference(float $difference, float $tolerance = 0.1, int $maxIterations = 10000): self
    {
        list($L1, $a1, $b1) = $this->asArray();

        $minScale = 0.1;

        $difference = ($difference > 100) ? $difference - 100 : $difference;

        for ($i = 0; $i < $maxIterations; $i++) {
            $adjustment = [
                'L' => mt_rand(-100, 100) / 100,
                'a' => mt_rand(-100, 100) / 100,
                'b' => mt_rand(-100, 100) / 100,
            ];

            $newL = max(0, min(100, $L1 + $adjustment['L']));
            $newA = max(-128, min(127, $a1 + $adjustment['a']));
            $newB = max(-128, min(127, $b1 + $adjustment['b']));

            $newColor = new LAB($newL, $newA, $newB);
            $newDistance = $this->visibleDifference($newColor);

            $distanceDiff = abs($newDistance - $difference);

            $scaleFactor = $minScale + ($difference - $minScale) * ($distanceDiff / $difference);

            if ($distanceDiff <= $tolerance) {
                return $newColor;
            }

            $L1 = max(0, min(100, $L1 + $adjustment['L'] * $scaleFactor));
            $a1 = max(-128, min(127, $a1 + $adjustment['a'] * $scaleFactor));
            $b1 = max(-128, min(127, $b1 + $adjustment['b'] * $scaleFactor));
        }
        
        return new LAB($L1, $a1, $b1);
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

        return new LAB($this->L, max(-128, min(127, $newA)), max(-128, min(127, $newB)));
    }

    public function adjustShade(float $shade, float $dampingFactor = 1.0): self
    {
        $newL = min(140, max(0, $this->L * ($shade / 100) * $dampingFactor));
        return new LAB($newL, $this->a, $this->b);
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

        $r = ($r > 0.0031308) ? ((1.055 * ($r ** (1 / 2.4))) - 0.055) : ($r * 12.92);
        $g = ($g > 0.0031308) ? ((1.055 * ($g ** (1 / 2.4))) - 0.055) : ($g * 12.92);
        $b = ($b > 0.0031308) ? ((1.055 * ($b ** (1 / 2.4))) - 0.055) : ($b * 12.92);

        return new RGB(
            (max(0, min(255, $r * 255)) * 100) / 100,
            (max(0, min(255, $g * 255)) * 100) / 100,
            (max(0, min(255, $b * 255)) * 100) / 100
        );
    }

    public function asXYZ(): array
    {
        $y = ($this->L + 16) / 116;
        $x = $this->a / 500 + $y;
        $z = $y - $this->b / 200;

        $x3 = $x ** 3;
        $y3 = $y ** 3;
        $z3 = $z ** 3;

        $x = ($x3 > 0.008856) ? $x3 : ($x - 16 / 116) / 7.787;
        $y = ($y3 > 0.008856) ? $y3 : ($y - 16 / 116) / 7.787;
        $z = ($z3 > 0.008856) ? $z3 : ($z - 16 / 116) / 7.787;

        $x *= 0.95047;
        $y *= 1.00000;
        $z *= 1.08883;

        return [$x, $y, $z];
    }

    public function asRGBA(float $alpha = 1): RGBA
    {
        return $this->asRGB()->asRGBA($alpha);
    }

    public function asHEX(): HEX
    {
        return $this->asRGB()->asHEX();
    }

    public function asCylindrical(): CylindricalLAB
    {
        $hue = atan2($this->b, $this->a);
        if ($hue < 0) {
            $hue += 2 * M_PI;
        }

        $chroma = sqrt($this->a ** 2 + $this->b ** 2);

        return new CylindricalLAB($this->L, $chroma, $hue);
    }

    public function asHSL(): HSL
    {
        return $this->asRGB()->asHSL();
    }

    public function asHSLA(float $alpha = 1): HSLA
    {
        return $this->asRGB()->asHSLA($alpha);
    }

    public function asHSV(): HSV
    {
        return $this->asRGB()->asHSV();
    }

    public function asRYB(): RYB
    {
        return $this->asRGB()->asRYB();
    }
}
