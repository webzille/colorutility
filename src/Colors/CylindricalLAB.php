<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;
use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\HSL;
use Webzille\ColorUtility\Colors\HSLA;
use Webzille\ColorUtility\Colors\LAB;
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RGBA;

class CylindricalLAB extends Color
{
    public float $L;

    public float $c;

    public float $h;

    public function __construct(float $L, float $c, float $h)
    {
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

    public function findColorByAngle(float $angle): self
    {
        return $this->asLAB()->findColorByAngle($angle)->asCylindrical();
    }

    public function findColorAtDifference(float $difference): self
    {
        return $this->asLAB()->findColorAtDifference($difference)->asCylindrical();
    }

    public function findColorAtDistance(float $distance): self
    {
        return $this->asLAB()->findColorAtDistance($distance)->asCylindrical();
    }

    public function findColorByShade(int $shade): self
    {
        return $this->asLAB()->findColorByShade($shade)->asCylindrical();
    }

    public function digitalDistance(Color $color): float
    {
        return $this->asLAB()->digitalDistance($color);
    }

    public function visibleDifference($color): float
    {
        return $this->asLAB()->visibleDifference($color);
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
        $a = $this->c * cos($this->h);
        $b = $this->c * sin($this->h);

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
}