<?php

/**
 * Geographic Component
 *
 * This component is used to get the range of latitude and longitude from a given point and distance.
 *
 *
 */
App::uses('HttpSocket', 'Network/Http');

/**
 * Geographic Component
 *
 * PHP 5.0 / CakePHP 2.0
 *
 * 
 * 
 *
 * @author        Mithun Das (mithundas79)
 * @copyright     Copyright 2014, Mithun Das (mithundas79)
 * @package       app.Controller.Component
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class GeographicComponent extends Component {

    /**
     * Calculates the latitude and longitude coordinates for a search perimeter's north, east, south, and west bounds (bearings of 0 degree, 90 degree, 180 degree, and -90 degree or 270 degree respectively.
     * Then search the subset of records that have locations which falls within this bounding-box *
     * @access private
     * @since 1.0 (2014-03-06)
     * @param  decimal $lat         -> takes the inputed latitude
     * @param  decimal $lon         -> takes the inputed longitude
     * @param  int $bearing         -> takes the bearing in degree viz, 0, 90, 180, 270
     * @param  int $distance        -> takes the limiting distance to bound the area
     * @param  char $units          -> takes the unit that the calculation shall be performed with
     * @return array $destination_array An array of latitude and longitude
     */
    private function __TrackDestination($lat, $lon, $bearing, $distance, $units) {
        $radius = strcasecmp($units, "km") ? 3963.19 : 6378.137;
        $rLat = deg2rad($lat);
        $rLon = deg2rad($lon);
        $rBearing = deg2rad($bearing);
        $rAngDist = $distance / $radius;

        $rLatB = asin(sin($rLat) * cos($rAngDist) +
                cos($rLat) * sin($rAngDist) * cos($rBearing));

        $rLonB = $rLon + atan2(sin($rBearing) * sin($rAngDist) * cos($rLat), cos($rAngDist) - sin($rLat) * sin($rLatB));

        $destination_array = array("lat" => rad2deg($rLatB), "lon" => rad2deg($rLonB));

        return $destination_array;
    }

    /**
     * This is a Wrapper function called repeatedly with different bearings to determine the search bounds *
     * @access public
     * @since 1.0 (2014-03-06)
     * @param decimal $lat         -> takes the inputed latitude
     * @param decimal $lon         -> takes the visitinputed longitude
     * @param int $distance        -> takes the limiting distance to bound the area
     * @param char $units          -> takes the unit that the calculation shall be performed with (km/miles)
     * @return array $boundary_array An array of boundaries
     */
    public function CreateBoundary($lat, $lon, $distance, $units) {
        $boundary_array = array("N" => $this->__TrackDestination($lat, $lon, 0, $distance, $units),
            "E" => $this->__TrackDestination($lat, $lon, 90, $distance, $units),
            "S" => $this->__TrackDestination($lat, $lon, 180, $distance, $units),
            "W" => $this->__TrackDestination($lat, $lon, 270, $distance, $units));
        return $boundary_array;
    }

}

?>
