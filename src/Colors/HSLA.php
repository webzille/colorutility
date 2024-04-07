<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;

class HSLA extends Color {

    private float $h;

    private float $s;

    private float $l;

    private float $alpha;

    function __construct(float $h, float $s, float $l, float $alpha = 1)
    {
        $this->h = $h;

        $this->s = $s;

        $this->l = $l;

        $this->alpha = $alpha;
    }

    public function asArray(): array
    {
        return [$this->h, $this->s, $this->l, $this->alpha];
    }

    public function asString(): string
    {
        return "hsla({$this->h}, {$this->s}%, {$this->l}%, {$this->alpha})";
    }

    public function isLight(): bool
    {
        return $this->alpha < 0.4 ?: $this->l >= 50;
    }

    public function white(): self
    {
        return new HSLA(0, 0, 100, 1);
    }

    public function black(): self
    {
        return new HSLA(0, 0, 0, 1);
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
        return $this->asHSV()->findColorByAngle($angle)->asHSLA();
    }

    public function findColorAtDifference(float $difference): self
    {
        return $this->asLAB()->findColorAtDifference($difference)->asHSLA();
    }

    public function findColorAtDistance(float $distance): self
    {
        return $this->asLAB()->findColorAtDistance($distance)->asHSLA();
    }

    public function findColorByShade(int $shade): self
    {
        return $this->asLAB()->findColorByShade($shade)->asHSLA();
    }

    public function linearDeviance(float $percent): self
    {
        return $this->asLAB()->linearDeviance($percent)->asHSLA();
    }

    public function angularDeviance(float $percent): self
    {
        return $this->asLAB()->angularDeviance($percent)->asHSLA();
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
        return $this->asRGBA()->asHEX();
    }

    public function asLAB(): LAB
    {
        return $this->asRGB()->asLAB();
    }

    public function asCylindrical(): CylindricalLAB
    {
        return $this->asLAB()->asCylindrical();
    }

    public function asRGBA(float $alpha = null): RGBA
    {
        $alpha = ($alpha === null) ? $this->alpha : $alpha;

        return $this->asRGB()->asRGBA($alpha);
    }

    public function asRGB(): RGB
    {
        $hue = $this-> h / 360;
        $saturation = $this->s / 100;
        $lightness = $this->l / 100;

        $chroma = (1 - abs(2 * $lightness - 1)) * $saturation;
        $hueSector = $hue * 6;
        $x = $chroma * (1 - abs((int) $hueSector % 2 - 1));

        $r1 = $g1 = $b1 = 0;
        switch (floor($hueSector)) {
            case 0:
                $r1 = $chroma;
                $g1 = $x;
                break;
            case 1:
                $r1 = $x;
                $g1 = $chroma;
                break;
            case 2:
                $g1 = $chroma;
                $b1 = $x;
                break;
            case 3:
                $g1 = $x;
                $b1 = $chroma;
                break;
            case 4:
                $r1 = $x;
                $b1 = $chroma;
                break;
            case 5:
                $r1 = $chroma;
                $b1 = $x;
                break;
        }

        $m = $lightness - $chroma / 2;
        $r = ($r1 + $m) * 255;
        $g = ($g1 + $m) * 255;
        $b = ($b1 + $m) * 255;

        $r = max(0, min(255, $r));
        $g = max(0, min(255, $g));
        $b = max(0, min(255, $b));

        return new RGB($r, $g, $b);
    }

    public function asHSL(): HSL
    {
        return new HSL($this->h, $this->s, $this->l);
    }

    public function asHSV(): HSV
    {
        // Convert HSL values to 0-1 range if needed
        $h = $this->h / 360;
        $s = $this->s / 100;
        $l = $this->l / 100;

        $q = ($l < 0.5) ? (1 + $s * (2 * $l - 1)) : (1 - $s * (1 - 2 * $l));

        $saturation = $q <= 1 ? ($s / $q) : (2 * $s / (1 - $q));

        $h = $h * 360;

        $saturation *= 100;
        $q *= 100;

        return new HSV($h, $saturation, $q);
    }
}
