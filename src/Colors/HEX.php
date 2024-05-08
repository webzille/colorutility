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
        parent::__construct();
        
        $this->HEX = $HEX;

        $this->alpha = '';

        switch (strlen($HEX)) {
            case 2:
                $this->r = $HEX[0] . $HEX[0];
                $this->g = $HEX[1] . $HEX[1];
                $this->b = $HEX[1] . $HEX[1];
                break;
            case 3:
                $this->r = $HEX[0] . $HEX[0];
                $this->g = $HEX[1] . $HEX[1];
                $this->b = $HEX[2] . $HEX[2];
                break;
            case 4:
                $this->r = $HEX[0] . $HEX[0];
                $this->g = $HEX[1] . $HEX[1];
                $this->b = $HEX[2] . $HEX[2];
                $this->alpha = $HEX[3] . $HEX[3];
                break;
            case 6:
                $this->r = $HEX[0] . $HEX[1];
                $this->g = $HEX[2] . $HEX[3];
                $this->b = $HEX[4] . $HEX[5];
                break;
            case 7:
                $this->r = $HEX[0] . $HEX[1];
                $this->g = $HEX[2] . $HEX[3];
                $this->b = $HEX[4] . $HEX[5];
                $this->alpha = $HEX[6] . $HEX[6];
                break;
            case 8:
                $this->r = $HEX[0] . $HEX[1];
                $this->g = $HEX[2] . $HEX[3];
                $this->b = $HEX[4] . $HEX[5];
                $this->alpha = $HEX[6] . $HEX[7];
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

    public function asRGBA(float|string $alpha = ''): RGBA
    {
        $this->alpha = $this->alpha === '' ? 'ff' : $this->alpha;
        $alpha = $alpha === '' ? $this->alpha : $alpha;
        $alpha = is_string($alpha) ? hexdec($alpha) / 255 : $alpha;

        $r = hexdec($this->r);
        $g = hexdec($this->g);
        $b = hexdec($this->b);

        return new RGBA($r, $g, $b, $alpha);
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

    public function asHSLA(float|string $alpha = ''): HSLA
    {
        $this->alpha = $this->alpha === '' ? 'ff' : $this->alpha;
        $alpha = $alpha === '' ? $this->alpha : $alpha;
        $alpha = is_string($alpha) ? hexdec($alpha) / 255 : $alpha;

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
