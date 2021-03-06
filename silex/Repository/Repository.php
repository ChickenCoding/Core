<?php

namespace OpenTribes\Core\Silex\Repository;

/**
 * Description of Repository
 *
 * @author BlackScorp<witalimik@web.de>
 */
abstract class Repository
{

    private $added = array();
    private $modified = array();
    private $deleted = array();

    /**
     * @param integer $id
     */
    protected function reassign($id)
    {
        if (isset($this->added[$id])) {
            unset($this->added[$id]);
        }
        if (isset($this->modified[$id])) {
            unset($this->modified[$id]);
        }
        if (isset($this->deleted[$id])) {
            unset($this->deleted[$id]);
        }
    }

    /**
     * @param integer $id
     */
    protected function markDeleted($id)
    {
        $this->reassign($id);
        $this->deleted[$id] = $id;
    }

    /**
     * @param integer $id
     */
    protected function markModified($id)
    {
        $this->reassign($id);
        $this->modified[$id] = $id;
    }

    /**
     * @param integer $id
     */
    protected function markAdded($id)
    {
        $this->reassign($id);
        $this->added[$id] = $id;
    }

    /**
     * @return integer[]
     */
    protected function getAdded()
    {
        return $this->added;
    }

    /**
     * @return integer[]
     */
    protected function getModified()
    {
        return $this->modified;
    }

    /**
     * @return integer[]
     */
    protected function getDeleted()
    {
        return $this->deleted;
    }

}
