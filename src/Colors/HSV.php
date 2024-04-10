<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;

class HSV extends Color
{

    private float $h;

    private float $s;

    private float $v;

    function __construct(?float $h, float $s, float $v)
    {
        $this->h = $h;

        $this->s = $s;

        $this->v = $v;
    }

    public function asArray(): array
    {
        return [$this->h, $this->s, $this->v];
    }

    public function asString(): string
    {
        return "hsv({$this->h}, {$this->s}, {$this->v})";
    }

    public function isLight(): bool
    {
        $brightnessWeight = 0.8;
        $saturationWeight = 0.2;
        $lightnessThreshold = 0.7;

        $lightness = ($this->v * $brightnessWeight) + ((1 - $this->s) * $saturationWeight);

        return $lightness >= $lightnessThreshold;
    }

    public function white(): self
    {
        return new HSV(0, 0, 100);
    }

    public function black(): self
    {
        return new HSV(0, 0, 0);
    }

    public function calculateAngle(Color $color): float
    {
        $color = $color->asHSV();

        $angle = abs($this->h - $color->getHue());
        if ($angle > 180) {
            $angle = 360 - $angle;
        }

        return $angle;
    }

    public function digitalDistance(Color $color): float
    {
        $color = $color->asHSV();

        $hueDiff = abs($this->h - $color->getHue());
        if ($hueDiff > 180) {
            $hueDiff = 360 - $hueDiff;
        }

        $hueDistance = min($hueDiff, 360 - $hueDiff);
        $saturationDistance = abs($this->s - $color->getSaturation());
        $valueDistance = abs($this->v - $color->getValue());

        $totalDistance = ($hueDistance + $saturationDistance + $valueDistance) / 6;

        return $totalDistance;
    }

    public function visibleDifference(Color $color): float
    {
        return $this->asLab()->visibleDifference($color);
    }

    public function findColorByAngle(float $angle): self
    {
        $newHue = $this->h + $angle;

        $newHue = fmod(abs($newHue), 360);

        if ($angle < 0) {
            $newHue = 360 - $newHue;
        }

        return new HSV($newHue, $this->s, $this->v);
    }

    public function findColorAtDifference(float $difference): self
    {
        return $this->asLAB()->findColorAtDifference($difference)->asHSV();
    }

    public function findColorAtDistance(float $distance): self
    {
        $saturationWeight = 1.0;
        $valueWeight = 1.0;

        $distance = min(1, max(0, $distance));

        $randomAngle = rand(0, 360);

        $totalWeight = $saturationWeight + $valueWeight;
        $hueDistance = $distance * $saturationWeight / $totalWeight;
        $saturationDistance = $distance * (1 - $saturationWeight) / 2;
        $valueDistance = $distance * (1 - $valueWeight) / 2;

        $hueShift = $hueDistance * cos(deg2rad($randomAngle));
        $newHue = fmod($this->h + $hueShift, 360);

        $newSaturation = max(0, min(100, $this->s + ($saturationDistance * (rand(0, 1) ? 1 : -1))));
        $newValue = max(0, min(100, $this->v + ($valueDistance * (rand(0, 1) ? 1 : -1))));

        return new HSV($newHue, $newSaturation, $newValue);
    }

    public function findColorByShade(int $shade): self
    {
        return $this->asLAB()->findColorByShade($shade)->asHSV();
    }

    public function linearDeviance(float $percent): self
    {
        return $this->asLAB()->linearDeviance($percent)->asHSV();
    }

    public function angularDeviance(float $percent): self
    {
        $percent = ($percent > 200) ? ($percent - 200) / 100 : $percent / 100;

        return $this->findColorByAngle(180 * $percent);
    }

    public function getHue(): float
    {
        return $this->h;
    }

    public function getSaturation(): float
    {
        return $this->s;
    }

    public function getValue(): float
    {
        return $this->v;
    }

    public function asHEX(): HEX
    {
        return $this->asRGB()->asHEX();
    }

    public function asLAB(): LAB
    {
        return $this->asRGB()->asLAB();
    }

    public function asCylindrical(): CylindricalLAB
    {
        return $this->asLAB()->asCylindrical();
    }

    public function asRGBA(float $alpha = 1): RGBA
    {
        return $this->asRGB()->asRGBA($alpha);
    }

    public function asRGB(): RGB
    {
        $h = $this->h / 360;
        $s = $this->s / 100;
        $v = $this->v / 100;

        $h_i = floor($h * 6);
        $f = $h * 6 - $h_i;
        $p = $v * (1 - $s);
        $q = $v * (1 - $f * $s);
        $t = $v * (1 - (1 - $f) * $s);

        switch ($h_i % 6) {
            case 0: $r = $v; $g = $t; $b = $p; break;
            case 1: $r = $q; $g = $v; $b = $p; break;
            case 2: $r = $p; $g = $v; $b = $t; break;
            case 3: $r = $p; $g = $q; $b = $v; break;
            case 4: $r = $t; $g = $p; $b = $v; break;
            case 5: $r = $v; $g = $p; $b = $q; break;
        }

        $r = ceil($r * 255);
        $g = ceil($g * 255);
        $b = ceil($b * 255);

        return new RGB($r, $g, $b);
    }

    public function asHSLA(float $alpha = 1): HSLA
    {
        return $this->asHSL()->asHSLA($alpha);
    }

    public function asHSL(): HSL
    {
        $s = $this->s / 100;
        $v = $this->v / 100;

        $l = (2 - $s) * $v / 2;
        $s = $l == (0 ? 0 : $v <= 0.5) ? $s * $v / ($l * 2) : $s * $v / (2 - $l * 2);

        return new HSL($this->h, round($s * 100), round($l * 100));
    }
}
