<?php

namespace Rbac\Adapter;

use Doctrine\ORM\EntityManager;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Crypt\Password\Bcrypt;
use Rbac\Element\Result;
use Rbac\Entity\User;

class UserAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $authMethod;

    /**
     * @param EntityManager $entityManager
     * @param string $authMethod
     */
    public function __construct(EntityManager $entityManager, string $authMethod)
    {
        $this->entityManager = $entityManager;
        $this->authMethod = $authMethod;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return UserAdapter
     */
    public function setLogin(string $login): UserAdapter
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return UserAdapter
     */
    public function setPassword(string $password): UserAdapter
    {
        $this->password = $password;
        return $this;
    }

    public function authenticate()
    {

        $result = new Result();
        try {
            $users = $this->entityManager->getRepository(User::class)->findBy([
                $this->authMethod => $this->login,
            ]);
        } catch (\Exception $e) {
            $result
                ->setCode(Result::CONF_ERROR)
                ->setMessage($e->getMessage());
            return $result;
        }

        $count = count($users);

        if( $count != 1 ){
            if ($count > 1) {
                $result
                    ->setCode(Result::FAILURE_IDENTITY_AMBIGUOUS)
                    ->setMessage('more thant one user found with that identifier, did you configure a non unique column?');
                return $result;
            }

            if ($count == 0) {
                $result
                    ->setCode(Result::USER_NOT_FOUND)
                    ->setMessage('No user found with this identifier');
                return $result;
            }
        }

        $user = $users[0];
        $bcrypt = new Bcrypt();

        if($bcrypt->verify($this->password, $user->getPassword())){
            $result
                ->setCode(Result::ACCESS_GRANTED)
                ->setUser($user)
                ->setMessage('');
            return $result;
        }

        $result
            ->setCode(Result::PASSWORD_REJECTED)
            ->setMessage('WRONG PASSWORD !!!!');
        return $result;
    }


}