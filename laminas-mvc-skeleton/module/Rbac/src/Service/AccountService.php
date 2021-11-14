<?php

namespace Rbac\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Authentication\Storage\Session;
use Rbac\Entity\Role;
use Rbac\Entity\User;
use Rbac\Manager\TokenManager;
use Rbac\Manager\UserManager;

/**
 * AccountService
 */
class AccountService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var SessionService
     */
    protected $session;

    /**
     * @var TokenManager
     */
    protected $tokenManager;

    /**
     * @var MailerService
     */
    protected $mailerService;


    /**
     * @param EntityManager $entityManager
     * @param SessionService $session
     * @param TokenManager $tokenManager
     * @param MailerService $mailerService
     */
    public function __construct(EntityManager $entityManager, SessionService $session, TokenManager $tokenManager, MailerService $mailerService)
    {
        $this->entityManager = $entityManager;
        $this->tokenManager = $tokenManager;
        $this->mailerService = $mailerService;
        $this->session = $session;
    }

    /**
     * @return bool
     */
    public function hasIdentity(): bool
    {
        return ! $this->session->isEmpty();
    }

    /**
     * @return bool
     */
    public function getIdentity(): bool
    {
        return $this->session->read();
    }

    /**
     * @return User|null
     */
    public function getInstance(): ?User
    {
        if(!$this->hasIdentity()){
            return null;
        }

        if($this->user){
            return $this->user;
        }

        $this->user = $this->entityManager->getRepository(User::class)->find($this->getIdentity());
        return $this->user;

    }


    /**
     * @param array $data
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(array $data)
    {
        $checkMail = $this->entityManager->getRepository(User::class)->findOneBy([
            'email'=>$data['email'],
        ]);
        if($checkMail){
            throw new \Exception('mail already provided');
        }

        $user = new User();

        $user->setName($data['name']);
        $user->setAvatar($data['avatar']['name']);
        $user->setFirstname($data['firstname']);
        $user->setDateCreate(new \DateTimeImmutable());
        $user->setEmail($data['email']);
        $user->setLogin($data['login']);
        $user->setStatus(User::USER_NOT_ACTVATED);

        UserManager::setPassword($user, $data['password']);

        $role = $this->entityManager->getRepository(Role::class)->findOneBy(['name'=>'role.user']);

        $user->addRole($role);

        $this->entityManager->persist($user);

        //activationTokenGeneration
        $token = $this->tokenManager->createToken($user);
        $this->entityManager->persist($token);

        $this->mailerService->createMessage($token);

        $this->entityManager->flush();

        return $user;
    }

}