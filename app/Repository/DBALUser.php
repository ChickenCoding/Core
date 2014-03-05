<?php

namespace OpenTribes\Core\Repository;

use OpenTribes\Core\Domain\Entity\User as UserEntity;
use OpenTribes\Core\Domain\Repository\User as UserRepositoryInterface;
use Doctrine\DBAL\Connection;

/**
 * Description of DBALUser
 *
 * @author BlackScorp<witalimik@web.de>
 */
class DBALUser implements UserRepositoryInterface {

    private $db;

    /**
     * @var UserEntity[]
     */
    private $users    = array();
    private $added    = array();
    private $modified = array();
    private $deleted  = array();

    public function __construct(Connection $db) {
        $this->db = $db;
    }

    public function add(UserEntity $user) {
        $this->users[$user->getId()] = $user;
        $this->added[$user->getId()] = $user->getId();
    }

    public function create($id, $username, $password, $email) {
        return new UserEntity($id, $username, $password, $email);
    }

    public function delete(UserEntity $user) {
        unset($this->users[$user->getId()]);
        $this->deleted[$user->getId()] = $user->getId();
    }

    public function findOneByEmail($email) {
        $result = $this->getQueryBuilder()
                ->where('u.email = :email')
                ->setParameter(':email', $email)
                ->execute();
        $row    = $result->fetch(\PDO::FETCH_OBJ);
        if(!$row) return null;
        $entity = $this->rowToEntity($row);
        $this->replace($entity);
        return $entity;
    }

    public function findOneByUsername($username) {

        $result = $this->getQueryBuilder()
                ->where('u.username = :username')
                ->setParameter(':username', $username)
                ->execute();
        
        $row    = $result->fetch(\PDO::FETCH_OBJ);
        if(!$row) return null;
        $entity = $this->rowToEntity($row);
        $this->replace($entity);
        return $entity;
    }

    public function getUniqueId() {
        return 1;
    }

    private function getQueryBuilder() {
        $queryBuilder = $this->db->createQueryBuilder();
        return $queryBuilder->select('u.id', 'u.username', 'u.password', 'u.email', 'u.activationCode')->from('users', 'u');
    }

    public function replace(UserEntity $user) {
        $this->users[$user->getId()]    = $user;
        $this->modified[$user->getId()] = $user->getId();
    }

    private function rowToEntity($row) {
        $user = $this->create($row->id, $row->username, $row->password, $row->email);
        $user->setActivationCode($row->activationCode);
        return $user;
    }

    private function entityToRow(UserEntity $user) {
        return array(
            'id'             => $user->getId(),
            'username'       => $user->getUsername(),
            'email'          => $user->getEmail(),
            'password'       => $user->getPassword(),
            'activationCode' => $user->getActivationCode()
        );
    }

    public function sync() {
        foreach ($this->added as $id) {
            if (isset($this->users[$id])) {
                $user = $this->users[$id];
                $this->db->insert('users', $this->entityToRow($user));
            }
        }
        foreach ($this->modified as $id) {
            if (isset($this->users[$id])) {
                $user = $this->users[$id];
                $this->db->update('users', $this->entityToRow($user), array('id' => $id));
            }
        }
        foreach ($this->deleted as $id) {
            if (isset($this->user[$id])) {
                $user = $this->users[$id];
                $this->db->delete('users', array('id' => $id));
            }
        }
    }

}