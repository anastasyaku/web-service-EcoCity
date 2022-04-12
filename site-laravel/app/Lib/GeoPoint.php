<?php

namespace App\Lib;

use App\DumpsterMark;

class GeoPoint {
    private $latitude, $longitude;

    function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Возвращает квадрат растояния между этой геоточкой и мусоркой `dumpster`.
     *
     * @return float
     */
    private function squaredDistanceBetween(DumpsterMark $other) {
        return pow($this->latitude - $other->latitude, 2) + pow($this->longitude - $other->longitude, 2);
    }

    /**
     * Находит мусорку, которая ближе всего к этой геоточке.
     *
     * @return \App\Dumpster объект ближайшей мусорки.
     */
    function findClosestDumpster() {
        $closest_point = null;
        $min_squared_distance = 10000;
        $dumpsters = DumpsterMark::all();
        foreach ($dumpsters as $point) {
            $squared_distance = $this->squaredDistanceBetween($point);
            if ($squared_distance < $min_squared_distance) {
                $closest_point = $point;
                $min_squared_distance = $squared_distance;
            }
        }
        return $closest_point;
    }
}
