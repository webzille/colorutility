<?php

namespace Webzille\ColorUtility;

use Webzille\ColorUtility\Calculations\CieDelta;
use Webzille\ColorUtility\Colors\CylindricalLAB;
use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\HSL;
use Webzille\ColorUtility\Colors\HSLA;
use Webzille\ColorUtility\Colors\LAB;
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RGBA;
use Webzille\ColorUtility\Colors\HSV;
use Webzille\ColorUtility\Colors\RYB;

abstract class Color {

    protected string $colorSpace;

    function __construct()
    {
        $this->colorSpace = Colors::$defaultColorSpace;
    }

    function __toString(): string
    {
        return $this->asString();
    }

    abstract function asArray();

    abstract function asString();

    abstract function isLight();

    public function isDark(): bool
    {
        return !$this->isLight();
    }

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

    public function asCylindrical(): CylindricalLAB
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

    public function asHSV(): HSV
    {
        return $this;
    }

    public function asRYB(): RYB
    {
        return $this;
    }

    public function getAlpha(): float|string
    {
        return 1.0;
    }

    public function setSpace(string $space = ''): self
    {
        $space = empty($space) && !is_callable($space) ? $this->colorSpace : $space;
        $space = in_array($space, Colors::$spaces) ? $space : $this->colorSpace;

        $this->colorSpace = $space;
        return $this;
    }

    protected function getModels(): array
    {
        return [
                      HSLA::class => [$this, 'asHSLA'],
                       HSL::class => [$this, 'asHSL'],
                      RGBA::class => [$this, 'asRGBA'],
                       RGB::class => [$this, 'asRGB'],
                       HEX::class => [$this, 'asHEX'],
                       RYB::class => [$this, 'asRYB'],
                       HSV::class => [$this, 'asHSV'],
                       LAB::class => [$this, 'asLAB'],
            CylindricalLAB::class => [$this, 'asCylindrical']
        ];
    }

    private function convert(string $model, float $alpha = 1.0): self
    {
        $models = $this->getModels();

        if (array_key_exists($model, $models)) {
            return call_user_func($models[$model], $alpha);
        }
    }

    public function as(string $model = ''): self
    {
        return $this->convert($model);
    }

    public function backTo(Color $to): self
    {
        $alpha = $to->getAlpha() ?? null;
        return $this->convert(get_class($to), $alpha);
    }

    public function viewColor(string $label = null): string
    {
        $isWebSafe = in_array(get_class($this), Colors::$websafe);
        $webSafeColor = $isWebSafe ? $this : $this->asRGB();
        $fontColor = $webSafeColor->isLight() ? $webSafeColor->black() : $webSafeColor->white();
        $label = trim("$label $webSafeColor");

        return "<span style=\"padding-inline: 3rem; background-color: $webSafeColor; color: $fontColor;\">$label</span>\n";
    }

    public function digitalDistance(Color $color): float
    {
        return (new CieDelta)->E76($this, $color);
    }

    public function visibleDifference(Color $color): float
    {
        return (new CieDelta)->E2000($this, $color);
    }

    public function calculateAngle(Color $color): float
    {
        return $this->as($this->colorSpace)->calculateAngle($color);
    }

    public function currentAngle(): float
    {
        return $this->calculateAngle(new RGB(255, 0, 0));
    }

    public function findColorByDistance(float $distance): self
    {
        return $this->asLAB()->findColorByDistance($distance)->backTo($this);
    }

    public function findColorByDifference(float $difference): self
    {
        return $this->asLAB()->findColorByDifference($difference)->backTo($this);
    }

    public function findColorByAngle(float $angle): self
    {
        return $this->as($this->colorSpace)->findColorByAngle($angle)->backTo($this);
    }

    public function adjustShade(float $shade, float $dampingFactor = 1): self
    {
        return $this->as($this->colorSpace)->adjustShade($shade, $dampingFactor)->backTo($this);
    }

    public function linearDeviance(float $percent): self
    {
        return $this->as($this->colorSpace)->linearDeviance($percent)->backTo($this);
    }

    public function angularDeviance(float $percent): self
    {
        $percent = ($percent > 200) ? ($percent - 200) / 100 : $percent / 100;

        return $this->findColorByAngle(180 * $percent);
    }

    public function complementary(): self
    {
        // Rotate the base color by 180 degrees (180°)
        return $this->findColorByAngle(180);
    }

    public function tetradic(): array
    {
        // Rotate the base color by 90 degrees (90°) for one color and 270 degrees (270°) for another
        return [
            $this->findColorByAngle(90),
            $this->findColorByAngle(180),
            $this->findColorByAngle(270)
        ];
    }

    public function splitComplementary(): array
    {
        // Rotate the base color by 150 degrees (150°) and 210 degrees (210°)
        return [
            $this->findColorByAngle(150),
            $this->findColorByAngle(210)
        ];
    }

    public function triadic(): array
    {
        // Rotate the base color by 120 degrees (120°) and 240 degrees (240°)
        return [
            $this->findColorByAngle(120),
            $this->findColorByAngle(240)
        ];
    }

    public function analogous(): array
    {
        // Rotate the base color by 30 degrees (30°) in both directions
        return [
            $this->findColorByAngle(30),
            $this->findColorByAngle(-30)
        ];
    }

    public function monochromaticTones(): array
    {
        // Generate a few different tones of the same color
        $monochromaticTones = [];

        for($tone = -25; $tone <= 25; $tone += 5) {
            $monochromaticTones[$tone] = $this->findColorByAngle($tone);
        }

        return $monochromaticTones;
    }

    public function monochromaticShades(): array
    {
        // Generate shades of the base color by adjusting the lightness
        $monochromaticShades = [];

        for($shade = 0; $shade <= 200; $shade += 10) {
            $monochromaticShades[$shade] = $this->adjustShade($shade);
        }

        return $monochromaticShades;
    }
}
