<?php

namespace OpenTribes\Core\Interactor;

use OpenTribes\Core\Repository\City as CityRepository;
use OpenTribes\Core\Repository\User as UserRepository;
use OpenTribes\Core\Repository\Map as MapRepository;
use OpenTribes\Core\Request\CreateCity as CreateCityRequest;
use OpenTribes\Core\Response\CreateUser as CreateCityResponse;

/**
 * Description of CreateCity
 *
 * @author BlackScorp<witalimik@web.de>
 */
class CreateCity {

    private $cityRepository;
    private $userRepository;
    private $mapRepository;

    function __construct(CityRepository $cityRepository, MapRepository $mapRepository, UserRepository $userRepository) {
        $this->cityRepository = $cityRepository;
        $this->userRepository = $userRepository;
        $this->mapRepository  = $mapRepository;
    }

    public function process(CreateCityRequest $request, CreateCityResponse $response) {
        $owner = $this->userRepository->findOneByUsername($request->getUsername());
        $x     = $request->getX();
        $y     = $request->getY();
        if (!$this->mapRepository->tileIsAccessible($y, $x))
            return false;

        if ($this->cityRepository->cityExistsAt($y, $x))
            return false;
        $id   = $this->cityRepository->getUniqueId();
        $name = $owner->getUsername();
        $city = $this->cityRepository->create($id, $name, $owner, $request->getX(), $request->getY());
        $this->cityRepository->add($city);
        return true;
    }

}