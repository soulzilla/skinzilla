<?php

namespace App\Enums;

class GunsEnum
{
    const TYPE_USP = 1;
    const TYPE_GLOCK = 2;
    const TYPE_P2000 = 3;
    const TYPE_P250 = 4;
    const TYPE_FIVE_SEVEN = 5;
    const TYPE_TEC9 = 6;
    const TYPE_CZ75AUTO = 7;
    const TYPE_DESERT_EAGLE = 8;
    const TYPE_REVOLVER = 9;
    const TYPE_DUAL_BERETTA = 10;

    const TYPE_NOVA = 11;
    const TYPE_SAWED_OFF = 12;
    const TYPE_MAG7 = 13;
    const TYPE_XM1014 = 14;
    const TYPE_NEGEV = 15;
    const TYPE_M249 = 16;

    const TYPE_MP9 = 17;
    const TYPE_MAC10 = 18;
    const TYPE_MP7 = 19;
    const TYPE_MP5 = 20;
    const TYPE_BIZON = 21;
    const TYPE_UMP = 22;
    const TYPE_P90 = 23;

    const TYPE_M4A1 = 24;
    const TYPE_M4A4 = 25;
    const TYPE_AK47 = 26;
    const TYPE_FAMAS = 27;
    const TYPE_GALIL = 28;
    const TYPE_AUG = 29;
    const TYPE_SG553 = 30;

    const TYPE_AWP = 31;
    const TYPE_SSG = 32;
    const TYPE_SCAR = 33;
    const TYPE_G3SG1 = 34;

    const TYPE_KNIFE = 35;
    const TYPE_GLOVES = 36;

    public static function getGroup($key): array
    {
        $groups = [
            self::TYPE_USP => [self::TYPE_USP, self::TYPE_P2000],
            self::TYPE_P2000 => [self::TYPE_USP, self::TYPE_P2000],
            self::TYPE_FIVE_SEVEN => [self::TYPE_FIVE_SEVEN, self::TYPE_CZ75AUTO],
            self::TYPE_CZ75AUTO => [self::TYPE_FIVE_SEVEN, self::TYPE_CZ75AUTO, self::TYPE_TEC9],
            self::TYPE_TEC9 => [self::TYPE_TEC9, self::TYPE_CZ75AUTO],
            self::TYPE_DESERT_EAGLE => [self::TYPE_DESERT_EAGLE, self::TYPE_REVOLVER],
            self::TYPE_REVOLVER => [self::TYPE_DESERT_EAGLE, self::TYPE_REVOLVER],
            self::TYPE_MP7 => [self::TYPE_MP7, self::TYPE_MP5],
            self::TYPE_MP5 => [self::TYPE_MP7, self::TYPE_MP5],
            self::TYPE_M4A4 => [self::TYPE_M4A4, self::TYPE_M4A1],
            self::TYPE_M4A1 => [self::TYPE_M4A4, self::TYPE_M4A1],
        ];

        return isset($groups[$key]) ? $groups[$key] : [$key];
    }

    public static function getPreset($key)
    {
        if ($key >= 1 && $key <= 10) {
            return 1;
        }

        if ($key >= 11 && $key <= 16) {
            return 2;
        }

        if ($key >= 17 && $key <= 23) {
            return 3;
        }

        if ($key >= 24 && $key <= 34) {
            return 4;
        }

        return 5;
    }
}
