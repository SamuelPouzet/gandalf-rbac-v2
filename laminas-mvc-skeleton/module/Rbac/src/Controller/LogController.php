<?php

namespace Rbac\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Rbac\Form\LogForm;
use Rbac\Form\UserExternalForm;
use Rbac\Form\UserForm;
use Rbac\Service\AuthService;

class LogController extends AbstractActionController
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return ViewModel
     */
    public function loginAction(): ViewModel
    {
        $form = new LogForm();

        if($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()){
                $this->authService->checkUser( $form->getData() );
            }
        }

        return new ViewModel([
            'form'=>$form,
        ]);
    }

    /**
     * @return ViewModel
     */
    public function logoutAction(): ViewModel
    {
        $this->authService->quitUser();

        return new ViewModel();
    }

    public function signinAction() : ViewModel
    {
        $form = new UserForm();
        $form->addCaptcha();

        if($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()){
                $this->authService->checkUser( $form->getData() );
            }
        }

        return new ViewModel([
            'form'=>$form,
        ]);

    }

}