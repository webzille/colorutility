<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;

class CylindricalLAB extends Color
{
    public float $L;

    public float $c;

    public float $h;

    public function __construct(float $L, float $c, float $h)
    {
        parent::__construct();
        
        $this->L = $L;
        $this->c = $c;
        $this->h = $h;
    }

    public function withLightness(float $L): self
    {
        return new self($L, $this->c, $this->h);
    }

    public function asArray(): array
    {
        return [$this->L, $this->c, $this->h];
    }

    public function asString(): string
    {
        return "{$this->L}, {$this->c}, {$this->h}";
    }

    public function isLight(): bool
    {
        return $this->L > 50;
    }

    public function black(): self
    {
        return new CylindricalLAB(0, 0, 0);
    }

    public function white(): self
    {
        return new CylindricalLAB(100, 0, 0);
    }

    public function calculateAngle(Color $angle): float
    {
        return $this->asLAB()->calculateAngle($angle);
    }

    public function currentAngle(): float
    {
        return $this->asLAB()->currentAngle();
    }

    public function findColorByAngle(float $angle): self
    {
        $hueChange = 180 * sin($angle);

        $newHue = fmod(($this->h + $hueChange), 360);

        return new CylindricalLAB($this->L, $this->c, $newHue);
    }

    public function findColorByDifference(float $difference): self
    {
        return $this->asLAB()->findColorByDifference($difference)->asCylindrical();
    }

    public function findColorByDistance(float $distance): self
    {
        return $this->asLAB()->findColorByDistance($distance)->asCylindrical();
    }

    public function adjustShade(float $shade, $dampingFactor = 1.0): self
    {
        return $this->asLAB()->adjustShade($shade)->asCylindrical();
    }

    public function linearDeviance(float $percent): self
    {
        return $this->asLAB()->linearDeviance($percent)->asCylindrical();
    }

    public function angularDeviance(float $percent): self
    {
        return $this->asLAB()->angularDeviance($percent)->asCylindrical();
    }

    public function getLightness(): float
    {
        return $this->L;
    }

    public function getChroma(): float
    {
        return $this->c;
    }

    public function getHue(): float
    {
        return $this->h;
    }

    public function asLAB(): LAB
    {
        $hRad = deg2rad($this->h);

        $a = $this->c * cos($hRad);
        $b = $this->c * sin($hRad);

        return new LAB($this->L, $a, $b);
    }

    public function asHEX(): HEX
    {
        return $this->asLAB()->asHEX();
    }

    public function asRGB(): RGB
    {
        return $this->asLAB()->asRGB();
    }

    public function asRGBA(float $alpha = 1): RGBA
    {
        return $this->asLAB()->asRGBA($alpha);
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
