<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;
use Webzille\ColorUtility\Colors\CylindricalLAB;
use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\HSLA;
use Webzille\ColorUtility\Colors\LAB;
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RGBA;

class HSL extends Color {

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
        return $this->l > 50;
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
        return $this->asRGB()->asLAB()->calculateAngle($color);
    }

    public function digitalDistance(Color $color): float
    {
        return $this->asRGB()->asLAB()->digitalDistance($color);
    }

    public function visibleDifference(Color $color): float
    {
        return $this->asRGB()->asLAB()->visibleDifference($color);
    }

    public function findColorByAngle(float $angle): self
    {
        return $this->asRGB()->asLAB()->findColorByAngle($angle)->asHSL();
    }

    public function findColorAtDifference(float $difference): self
    {
        return $this->asRGB()->asLAB()->findColorAtDifference($difference)->asHSL();
    }

    public function findColorAtDistance(float $distance): self
    {
        return $this->asRGB()->asLAB()->findColorAtDistance($distance)->asHSL();
    }

    public function findColorByShade(int $shade): self
    {
        return $this->asRGB()->asLAB()->findColorByShade($shade)->asHSL();
    }

    public function linearDeviance(float $percent): self
    {
        return $this->asRGB()->asLAB()->linearDeviance($percent)->asHSL();
    }

    public function angularDeviance(float $percent): self
    {
        return $this->asRGB()->asLAB()->angularDeviance($percent)->asHSL();
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
        return $this->asRGB()->asLAB()->asCylindrical();
    }

    public function asRGBA(float $alpha = 1): RGBA
    {
        return $this->asRGB()->asRGBA($alpha);
    }

    public function asRGB(): RGB
    {
        $hue = $this-> h /360;
        $saturation = $this->s / 100;
        $lightness = $this->l/ 100;

        $chroma = (1 - abs(2 * $lightness - 1)) * $saturation;
        $hueSector = $hue * 6;
        $x = $chroma * (1 - abs($hueSector % 2 - 1));

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

    public function asHSLA(float $alpha = 1): HSLA
    {
        list($h, $s, $l) = $this->asArray();

        return new HSLA($h, $s, $l, $alpha);
    }
}