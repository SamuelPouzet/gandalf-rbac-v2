<?php

namespace Rbac\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Rbac\Form\LogForm;
use Rbac\Form\UserExternalForm;
use Rbac\Form\UserForm;
use Rbac\Service\AccountService;
use Rbac\Service\AuthService;

class LogController extends AbstractActionController
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService, AccountService $accountService)
    {
        $this->authService = $authService;
        $this->accountService = $accountService;
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

    /**
     * @return ViewModel
     */
    public function forbiddenAction(): ViewModel
    {
        return new ViewModel();
    }

    public function signinAction() : ViewModel
    {
        $form = new UserForm();
        $form->addCaptcha();

        if($this->getRequest()->isPost()){
            $data = array_merge(
                $this->params()->fromPost(),
                $this->params()->fromFiles()
            );
            $form->setData($data);
            if($form->isValid()){
                $this->accountService->create( $form->getData() );
            }
        }

        return new ViewModel([
            'form'=>$form,
        ]);

    }

    public function activateAction() : ViewModel
    {

        $token = $this->params()->fromRoute('token', null);

        if(!$token){
            $this->getResponse()->setStatusCode(404);
        }

        $response = $this->accountService->activateByToken($token);


        return new ViewModel([
            'response'=>$response,
        ]);

    }

}