<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;

use Webzille\ColorUtility\Colors\CylindricalLAB;
use Webzille\ColorUtility\Colors\HSL;
use Webzille\ColorUtility\Colors\HSLA;
use Webzille\ColorUtility\Colors\LAB;
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RGBA;

class HEX extends Color {

    private string $HEX;

    private string $r;

    private string $g;

    private string $b;

    private string $alpha;

    function __construct($HEX)
    {
        $this->HEX = $HEX;

        $this->alpha = '';

        switch(strlen($HEX)) {
            case 2:
                $this->r = $HEX[0] . $HEX[0];
                $this->g = $HEX[1] . $HEX[1];
                $this->b = $HEX[0] . $HEX[0];
                break;
            case 3:
                $this->r = $HEX[0] . $HEX[0];
                $this->g = $HEX[1] . $HEX[1];
                $this->b = $HEX[2] . $HEX[2];
                break;
            case 4:
                $this->alpha = $HEX[0] . $HEX[0];
                $this->r = $HEX[1] . $HEX[1];
                $this->g = $HEX[2] . $HEX[2];
                $this->b = $HEX[3] . $HEX[3];
                break;
            case 6:
                $this->r = $HEX[0] . $HEX[1];
                $this->g = $HEX[2] . $HEX[3];
                $this->b = $HEX[4] . $HEX[5];
                break;
            case 8:
                $this->alpha = $HEX[0] . $HEX[1];
                $this->r = $HEX[2] . $HEX[3];
                $this->g = $HEX[4] . $HEX[5];
                $this->b = $HEX[6] . $HEX[7];
                break;
        }
    }

    public function asArray(): array
    {
        return [$this->HEX];
    }

    public function asString(): string
    {
        return "#{$this->HEX}";
    }

    public function isLight(): bool
    {
        return $this->asRGB()->isLight();
    }

    public function white(): self
    {
        return new HEX('ffffff');
    }

    public function black(): self
    {
        return new HEX('000000');
    }

    public function calculateAngle(Color $color): float
    {
        return $this->asLAB()->calculateAngle($color->asLAB());
    }

    public function digitalDistance(Color $color): float
    {
        return $this->asLAB()->digitalDistance($color->asLAB());
    }

    public function visibleDifference(Color $color): float
    {
        return $this->asLAB()->visibleDifference($color->asLAB());
    }

    public function findColorByAngle(float $angle): self
    {
        return $this->asLAB()->findColorByAngle($angle)->asHEX();
    }

    public function findColorAtDifference(float $difference): self
    {
        return $this->asLAB()->findColorAtDifference($difference)->asHEX();
    }

    public function findColorAtDistance(float $distance): self
    {
        return $this->asLAB()->findColorAtDistance($distance)->asHEX();
    }

    public function findColorByShade(int $shade): self
    {
        return $this->asLAB()->findColorByShade($shade)->asHEX();
    }

    public function linearDeviance(float $percent): self
    {
        return $this->asLAB()->linearDeviance($percent)->asHEX();
    }

    public function angularDeviance(float $percent): self
    {
        return $this->asLAB()->angularDeviance($percent)->asHEX();
    }

    public function getHEX(): string
    {
        return $this->HEX;
    }

    public function getRed(): string
    {
        return $this->r;
    }

    public function getGreen(): string
    {
        return $this->g;
    }

    public function getBlue(): string
    {
        return $this->b;
    }

    public function getAlpha(): string
    {
        return $this->alpha;
    }

    public function asRGB(): RGB
    {
        $r = hexdec($this->r);
        $g = hexdec($this->g);
        $b = hexdec($this->b);

        return new RGB($r, $g, $b);
    }

    public function asRGBA(float $alpha = 1): RGBA
    {
        $r = hexdec($this->r);
        $g = hexdec($this->g);
        $b = hexdec($this->b);
        $a = hexdec($this->alpha);

        return new RGBA($r, $g, $b, $a);
    }

    public function asLAB(): LAB
    {
        return $this->asRGB()->asLAB();
    }

    public function asCylindrical(): CylindricalLAB
    {
        return $this->asLAB()->asCylindrical();
    }

    public function asHSL(): HSL
    {
        return $this->asRGB()->asHSL();
    }

    public function asHSLA(float $alpha = null): HSLA
    {
        if ($alpha === '') {
            $alpha = ($this->alpha === null) ? 1 : hexdec($this->alpha);
        }

        return $this->asRGB()->asHSLA($alpha);
    }
}