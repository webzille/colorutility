<?php

namespace Webzille\ColorUtility\Calculations;

use Webzille\ColorUtility\Colors\LAB;

class DeltaE2000 {

    // Lightness weight factor
    private const KL = 1;

    // Chroma weight factor
    private const KC = 1;

    // Hue weight factor
    private const KH = 1;

    // ¯L′
    private function averageLightness($L1, $L2)
    {
        return ($L1 + $L2) / 2;
    }

    // C1 && C2
    private function chroma($a, $b)
    {
        return sqrt(($a ** 2) + ($b ** 2));
    }

    // ¯C
    private function averageChroma($chroma1, $chroma2)
    {
        return ($chroma1 + $chroma2) / 2;
    }

    // G
    private function adjustChroma($averageChroma)
    {
        return .5 * (1 - sqrt( $averageChroma ** 7 / (($averageChroma ** 7) + (25 ** 7))));
    }

    // a′1 && a′2
    private function transformA($a, $adjustChroma)
    {
        return $a * (1 + $adjustChroma);
    }

    // C′1 && C′2
    private function transformChroma($transformedA, $b)
    {
        return sqrt(($transformedA ** 2) + ($b ** 2));
    }

    // ¯C′
    private function averageTransformedChrome($tc1, $tc2)
    {
        return ($tc1 + $tc2) / 2;
    }

    // h′1 && h′2
    private function hueAngle($ta, $b)
    {
        $angle = atan2($b, $ta);
        $angleDegrees = rad2deg($angle);

        return ($angleDegrees >= 0) ? $angleDegrees : $angleDegrees + 360;
    }

    // ¯H′
    private function averageHueAngle($hueAngle1, $hueAngle2, $transformedChroma1, $transformedChroma2)
    {
        $averageHueAngle = ($hueAngle1 + $hueAngle2);

        if ($transformedChroma1 * $transformedChroma2 === 0) {
            return $averageHueAngle;
        }

        if (abs($hueAngle1 - $hueAngle2) <= 180) {
            return $averageHueAngle / 2;
        } else if (abs($hueAngle1 - $hueAngle2) > 180 && $averageHueAngle < 360) {
            return ($averageHueAngle + 360) / 2;
        } else if (abs($hueAngle1 - $hueAngle2) > 180 && $averageHueAngle >= 360) {
            return ($averageHueAngle - 360) / 2;
        }
    }

    // T
    private function correctionFactor($averageHueAngle)
    {
        $averageHueAngle = deg2rad($averageHueAngle);
        return 1 - 0.17 * cos($averageHueAngle - 30) + 0.24
                        * cos(2 * $averageHueAngle) + 0.32
                        * cos(3 * $averageHueAngle + 6) - 0.20
                        * cos(4 * $averageHueAngle - 63);
    }

    // Δh′
    private function hueDifference($hueAngle1, $hueAngle2, $transformedChroma1, $transformedChroma2)
    {
        if ($transformedChroma1 * $transformedChroma2 === 0) {
            return 0;
        }

        $hueDifference = $hueAngle2 - $hueAngle1;

        if (abs($hueDifference) <= 180) {
            return $hueDifference;
        } else if (abs($hueDifference) > 180) {
            return $hueDifference - 360;
        } else if (abs($hueDifference) < -180) {
            return $hueDifference + 360;
        }
    }

    // ΔL′
    private function differenceInLightness($L1, $L2)
    {
        return $L2 - $L1;
    }

    // ΔC′
    private function differenceInChroma($chroma1, $chroma2)
    {
        return $chroma2 - $chroma1;
    }

    // ΔH′
    private function differenceInHue($transformedChroma1, $transformedChroma2, $hueDifference)
    {
        $hueDifference = deg2rad($hueDifference);
        return 2 * sqrt($transformedChroma1 * $transformedChroma2) * sin($hueDifference / 2);
    }

    // SL
    private function lightnessWeightFactor($averageLightness)
    {
        return 1 + (0.015 * ($averageLightness - 50) ** 2) / sqrt(20 + ($averageLightness - 50) ** 2);
    }

    // SC
    private function chromaWeightFactor($averageTransformedChroma)
    {
        return 1 + 0.045 * $averageTransformedChroma;
    }

    // SH
    private function hueWeightFactor($averageTransformedChroma, $correctionFactor)
    {
        return 1 + 0.015 * $averageTransformedChroma * $correctionFactor;
    }

    // Δθ
    private function hueAngleAdjustment($averageHueAngle)
    {
        return 30 * exp(-((($averageHueAngle - 275) / 25) ** 2));
    }

    // RC
    private function chromaCorrectionFactor($averageTransformedChroma)
    {
        return 2 * sqrt(($averageTransformedChroma ** 7) / (($averageTransformedChroma ** 7) + (25 ** 7)));
    }

    // RT
    private function chromaAndHueCorrectionFactor($chromaCorrectionFactor, $hueAngleAdjustment)
    {
        $hueAngleAdjustment = deg2rad($hueAngleAdjustment);
        return -$chromaCorrectionFactor * sin(2 * $hueAngleAdjustment);
    }

    // For reference on the formulas
    // https://hajim.rochester.edu/ece/sites/gsharma/ciede2000/ciede2000noteCRNA.pdf
    // http://www.brucelindbloom.com/index.html?Eqn_DeltaE_CIE2000.html
    public function calculate(LAB $lab1, LAB $lab2)
    {
        list($L1, $a1, $b1) = $lab1->asArray();
        list($L2, $a2, $b2) = $lab2->asArray();

        $averageLightness = $this->averageLightness($L1, $L2);
        $chroma1 = $this->chroma($a1, $b1);
        $chroma2 = $this->chroma($a2, $b2);
        $averageChroma = $this->averageChroma($chroma1, $chroma2);
        $adjustChroma = $this->adjustChroma($averageChroma);
        $transformedA1 = $this->transformA($a1, $adjustChroma);
        $transformedA2 = $this->transformA($a2, $adjustChroma);
        $transformedChroma1 = $this->transformChroma($transformedA1, $b1);
        $transformedChroma2 = $this->transformChroma($transformedA2, $b2);
        $averageTransformedChroma = $this->averageTransformedChrome($transformedChroma1, $transformedChroma2);
        $hueAngle1 = $this->hueAngle($transformedA1, $b1);
        $hueAngle2 = $this->hueAngle($transformedA2, $b2);
        $averageHueAngle = $this->averageHueAngle($hueAngle1, $hueAngle2, $transformedChroma1, $transformedChroma2);
        $correctionFactor = $this->correctionFactor($averageHueAngle);
        $hueDifference = $this->hueDifference($hueAngle1, $hueAngle2, $transformedChroma1, $transformedChroma2);
        $differenceInLightness = $this->differenceInLightness($L1, $L2);
        $differenceInChroma = $this->differenceInChroma($transformedChroma1, $transformedChroma2);
        $differenceInHue = $this->differenceInHue($transformedChroma1, $transformedChroma2, $hueDifference);
        $lightnessWeightFactor = $this->lightnessWeightFactor($averageLightness);
        $chromaWeightFactor = $this->chromaWeightFactor($averageTransformedChroma);
        $hueWeightFactor = $this->hueWeightFactor($averageTransformedChroma, $correctionFactor);
        $hueAngleAdjustment = $this->hueAngleAdjustment($averageHueAngle);
        $chromaCorrectionFactor = $this->chromaCorrectionFactor($averageTransformedChroma);
        $chromaAndHueCorrectionFactor = $this->chromaAndHueCorrectionFactor($chromaCorrectionFactor, $hueAngleAdjustment);

        $normalizedLightness = ($differenceInLightness / (self::KL * $lightnessWeightFactor)) ** 2;
        $normalizedChroma = ($differenceInChroma / (self::KC * $chromaWeightFactor)) ** 2;
        $normalizedHue = ($differenceInHue / (self::KH * $hueWeightFactor)) ** 2;
        $correctedChroma = $chromaAndHueCorrectionFactor * ($differenceInChroma / (self::KC * $chromaWeightFactor));
        $correctedHue = ($differenceInHue / (self::KH * $hueWeightFactor));

        return sqrt($normalizedLightness + $normalizedChroma + $normalizedHue + $correctedChroma * $correctedHue);
    }
}