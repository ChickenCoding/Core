<?php

namespace OpenTribes\Core\Mock\Service;

use OpenTribes\Core\Service\LocationCalculator as LocationCalculatorInterface;
use OpenTribes\Core\Value\Direction;

/**
 * Description of LocationCalculator
 *
 * @author BlackScorp<witalimik@web.de>
 */
class LocationCalculator implements LocationCalculatorInterface
{

    private $x = 0;
    private $y = 0;
    private $centerX = 0;
    private $centerY = 0;
    private $countCities = 0;


    public function setCenterPosition($y, $x)
    {
        $this->centerX = $x;
        $this->centerY = $y;
    }

    public function setCountCities($countCities)
    {
        $this->countCities = $countCities;
    }


    public function calculate(Direction $direction)
    {


        $direction = $direction->getValue();
        if ($direction === Direction::ANY) {
            $direction = $this->countCities % 4;
        }

        $angleStart = $direction*90;
        $angleEnd = ($direction+1)*90;
        $randomAngle = mt_rand($angleStart,$angleEnd);
        var_dump($angleStart,$angleEnd,$direction);
        $phi = deg2rad($randomAngle);
        $radius = -max(5,min($this->countCities,200));
        $x = $this->centerX + ~~($radius * cos($phi));
        $y = $this->centerY + ~~($radius * sin($phi));


        $this->x = $x;
        $this->y = $y;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

}
