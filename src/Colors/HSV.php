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
        return $this->asRGB()->isLight();
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

    public function currentAngle(): float
    {
        return $this->calculateAngle(new HSV(0, 100, 100));
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

    public function findColorByDifference(float $difference): self
    {
        return $this->asLAB()->findColorByDifference($difference)->asHSV();
    }

    public function findColorByDistance(float $distance): self
    {
        return $this->asLAB()->findColorByDistance($distance)->asHSV();
    }

    public function adjustShade(float $shade, $dampingFactor = 1.0): self
    {
        return $this->asLAB()->adjustShade($shade)->asHSV();
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

        if ($h < 0) {
            $h += 360;
        }

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
        $h = fmod($this->h, 360);
        if ($h < 0) {
            $h += 360;
        }

        $hh = round($h, 2);

        $s = $this->s / 100;
        $v = $this->v / 100;

        $l = ($v * (2 - $s)) / 2;

        $s = ($l < 0.5) ? ($s * $v) / ($l * 2) : ($s * $v) / (2 - $l * 2);
        $hs = round($s * 100, 2);
        $hl = round($l * 100, 2);

        return new HSL($hh, $hs, $hl);
    }

    public function asRYB(): RYB
    {
        return $this->asRGB()->asRYB();
    }
}
