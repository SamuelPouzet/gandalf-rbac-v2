<?php

namespace Rbac\Controller;

use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Rbac\Entity\User;
use Rbac\Form\PasswordChangeForm;
use Rbac\Form\UserForm;
use Rbac\Manager\UserManager;

class UserController extends AbstractActionController
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @param EntityManager $entityManager
     * @param UserManager $userManager
     */
    public function __construct(EntityManager $entityManager, UserManager $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function listAction(): ViewModel
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return new ViewModel([
           'users'=>$users,
        ]);
    }

    public function showAction(): ViewModel
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if(!$user){
            $this->getResponse()->setStatusCode(404);
        }

        return new ViewModel([
            'user'=>$user,
        ]);
    }

    public function updateAction(): ViewModel
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if(!$user){
            $this->getResponse()->setStatusCode(404);
        }

        $form = new UserForm('user-form', 'update');
        $form->addStatus();

        if($this->getRequest()->isPost()){
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);
            if($form->isValid()){
                $data = $form->getData();
                $this->userManager->update($data, $user);
                $this->redirect()->toRoute('user');
            }
        }

        $form->bind($user);
        return new ViewModel([
            'user'=>$user,
            'form'=>$form,
        ]);
    }

    public function addAction(): ViewModel
    {

        $form = new UserForm('user-form', 'create');
        $form->addStatus();

        if($this->getRequest()->isPost()){
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);
            if($form->isValid()){
                $data = $form->getData();
                $this->userManager->add($data);
                $this->redirect()->toRoute('user');
            }
        }

        return new ViewModel([
            'form'=>$form,
        ]);
    }

    public function passwordAction(): ViewModel
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if(!$user){
            $this->getResponse()->setStatusCode(404);
        }

        $form = new PasswordChangeForm('password-form');

        if($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()){
                $data = $form->getData();
                $this->userManager->updatePassword($data, $user);
                $this->redirect()->toRoute('user');
            }
        }

        $form->bind($user);
        return new ViewModel([
            'user'=>$user,
            'form'=>$form,
        ]);
    }

}