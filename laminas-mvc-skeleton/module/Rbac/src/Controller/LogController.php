<?php

namespace Rbac\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Rbac\Form\LogForm;
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
    public function LoginAction(): ViewModel
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

}