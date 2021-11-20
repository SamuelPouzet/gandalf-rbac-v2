<?php

namespace Rbac\Manager;

use Doctrine\ORM\EntityManager;
use Laminas\Crypt\Password\Bcrypt;
use Rbac\Entity\Role;
use Rbac\Entity\User;

/**
 *
 */
class UserManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $data
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(array $data): User
    {
        $user = new User();

        $user->setName($data['name']);
        $user->setAvatar($data['avatar']['name']);
        $user->setFirstname($data['firstname']);
        $user->setDateCreate(new \DateTimeImmutable());
        $user->setEmail($data['email']);
        $user->setLogin($data['login']);
        $user->setStatus($data['status']);

        self::setPassword($user, $data['password']);

        if(!$data['roles']){
            $role = $this->entityManager->getRepository(Role::class)->findOneBy(['name'=>'role.user']);
            $user->addRole($role);
        }else{
            foreach ($data['roles'] as $role){
                $user->addRole($role);
            }
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param array $data
     * @param User $user
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(array $data, User $user): User
    {

        $user->setName($data['name']);
        if($data['avatar']){
            $user->setAvatar($data['avatar']['name']);
        }

        $user->setFirstname($data['firstname']);
        $user->setDateCreate(new \DateTimeImmutable());
        $user->setEmail($data['email']);
        $user->setLogin($data['login']);
        $user->setStatus($data['status']);


        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param User $user
     * @param array $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePassword(array $data, User $user)
    {
        self::setPassword($user, $data['password']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     * @param string $password
     * @return User
     */
    public static function setPassword(User $user, string $password)
    {
        $bCrypt = new Bcrypt();
        $hash = $bCrypt->create($password);

        $user->setPassword($hash);

        return $user;
    }


}