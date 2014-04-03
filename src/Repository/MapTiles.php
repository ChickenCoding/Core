<?php

namespace OpenTribes\Core\Repository;

use OpenTribes\Core\Entity\Map as MapEntity;



/**
 *
 * @author Witali
 */
interface MapTiles {

    /**
     * @return MapEntity
     */
    public function getMap();

    public function add(MapEntity $map);
}