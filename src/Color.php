<?php

// https://www.cs.rit.edu/~ncs/color/t_convert.html#RGB%20to%20XYZ%20&%20XYZ%20to%20RGB

namespace Webzille\ColorUtility;

use Webzille\ColorUtility\Colors\CylindricalLAB;
use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\HSL;
use Webzille\ColorUtility\Colors\HSLA;
use Webzille\ColorUtility\Colors\LAB;
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RGBA;

abstract class Color {

    protected array $websafe = [
                                RGBA::class,
                                RGB::class,
                                HSLA::class,
                                HSL::class,
                                HEX::class
                            ];

    function __toString(): string
    {
        return $this->asString();
    }

    abstract function asArray();

    abstract function asString();

    abstract function isLight();

    abstract function white();

    abstract function black();

    public function asRGB(): RGB
    {
        return $this;
    }

    public function asRGBA(float $alpha): RGBA
    {
        return $this;
    }

    public function asLAB(): LAB
    {
        return $this;
    }

    public function asHEX(): HEX
    {
        return $this;
    }

    public function asCylindricalLAB(): CylindricalLAB
    {
        return $this;
    }

    public function asHSL(): HSL
    {
        return $this;
    }

    public function asHSLA(float $alpha): HSLA
    {
        return $this;
    }

    public function viewColor(string $label = null): string
    {
        $isWebSafe = in_array(get_class($this), $this->websafe);

        $rgbColor = $isWebSafe ? $this : $this->asRGB();

        $fontColor = $rgbColor->isLight() ? $rgbColor->black() : $rgbColor->white();

        $label = trim("$label $rgbColor");

        return "<span style=\"padding-inline: 3rem; background-color: $rgbColor; color: $fontColor;\">$label</span>\n";
    }

    abstract function calculateAngle(Color $angle);

    abstract function digitalDistance(Color $color);

    abstract function findColorAtDistance(float $distance);

    abstract function visibleDifference(Color $color);

    abstract function findColorByAngle(float $angle);

    abstract function findColorByShade(int $shade);

    abstract function findColorAtDifference(float $difference);

    abstract function linearDeviance(float $percent);

    abstract function angularDeviance(float $percent);

    public function complementaryColors(): array
    {
        // Rotate the base color by 180 degrees (180°)
        return [
            $this->findColorByAngle(180)
        ];
    }

    public function tetradicColors(): array
    {
        // Rotate the base color by 90 degrees (90°) for one color and 270 degrees (270°) for another
        return [
            $this->findColorByAngle(90),
            $this->findColorByAngle(180),
            $this->findColorByAngle(270)
        ];
    }

    public function splitComplementaryColors(): array
    {
        // Rotate the base color by 150 degrees (150°) and 210 degrees (210°)
        return [
            $this->findColorByAngle(150),
            $this->findColorByAngle(210)
        ];
    }

    public function triadicColors(): array
    {
        // Rotate the base color by 120 degrees (120°) and 240 degrees (240°)
        return [
            $this->findColorByAngle(120),
            $this->findColorByAngle(240)
        ];
    }

    public function analogousColors(): array
    {
        // Rotate the base color by small angles such as 30 degrees (30°) and 60 degrees (60°)
        return [
            $this->findColorByAngle(30),
            $this->findColorByAngle(-30)
        ];
    }

    public function monochromaticToneColors(): array
    {
        // Generate a few different tones of the same color
        $monochromaticTones = [];

        for($tone = -15; $tone <= 15; $tone += 5) {
            $monochromaticTones[] = $this->findColorByAngle($tone);
        }

        return $monochromaticTones;
    }

    public function monochromaticShadeColors(): array
    {
        // Generate shades of the base color by adjusting the lightness
        $monochromaticShades = [];

        for($shade = 0; $shade <= 100; $shade += 5) {
            $monochromaticShades[] = $this->findColorByShade($shade);
        }

        return $monochromaticShades;
    }
}
