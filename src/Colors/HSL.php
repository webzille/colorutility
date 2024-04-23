<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;

class HSL extends Color
{

    private float $h;

    private float $s;

    private float $l;

    function __construct(float $h, float $s, float $l)
    {
        $this->h = $h;

        $this->s = $s;

        $this->l = $l;
    }

    public function asArray(): array
    {
        return [$this->h, $this->s, $this->l];
    }

    public function asString(): string
    {
        return "hsl({$this->h}, {$this->s}%, {$this->l}%)";
    }

    public function isLight(): bool
    {
        return $this->asRGB()->isLight();
    }

    public function white(): self
    {
        return new HSL(0, 0, 100);
    }

    public function black(): self
    {
        return new HSL(0, 0, 0);
    }

    public function calculateAngle(Color $color): float
    {
        return $this->asRYB()->calculateAngle($color);
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
        return $this->asRYB()->findColorByAngle($angle)->asHSL();
    }

    public function findColorAtDifference(float $difference): self
    {
        return $this->asLAB()->findColorAtDifference($difference)->asHSL();
    }

    public function findColorAtDistance(float $distance): self
    {
        return $this->asLAB()->findColorAtDistance($distance)->asHSL();
    }

    public function findColorByShade(int $shade): self
    {
        return $this->asLAB()->findColorByShade($shade)->asHSL();
    }

    public function linearDeviance(float $percent): self
    {
        return $this->asLAB()->linearDeviance($percent)->asHSL();
    }

    public function angularDeviance(float $percent): self
    {
        return $this->asLAB()->angularDeviance($percent)->asHSL();
    }

    public function getHue(): float
    {
        return $this->h;
    }

    public function getSaturation(): float
    {
        return $this->s;
    }

    public function getLightness(): float
    {
        return $this->l;
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
        list($h, $s, $l) = $this->asArray();

        $h = $this->h / 360;
        $s = $this->s / 100;
        $l = $this->l / 100;

        if ($s === 0) {
            $r = $g = $b = $l;
        } else {
            $q = ($l < 0.5) ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;

            $r = $this->hueToRGB($p, $q, $h + 1/3);
            $g = $this->hueToRGB($p, $q, $h);
            $b = $this->hueToRGB($p, $q, $h - 1/3);
        }

        return new RGB($r * 255, $g * 255, $b * 255);
    }

    private function hueToRGB($p, $q, $t)
    {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;

        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;

        return $p;
    }

    public function asHSLA(float $alpha = 1): HSLA
    {
        list($h, $s, $l) = $this->asArray();

        return new HSLA($h, $s, $l, $alpha);
    }

    public function asHSV(): HSV
    {
        $s = $this->s / 100;
        $l = $this->l / 100;

        $v = $l + $s * min($l, 1 - $l);
        $s = $v == 0 ? 0 : 2 * (1 - $l / $v);

        $s *= 100;
        $v *= 100;

        return new HSV($this->h, $s, $v);
    }

    public function asRYB(): RYB
    {
        return $this->asRGB()->asRYB();
    }
}
