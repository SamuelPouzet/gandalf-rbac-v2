<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 01/11/2020
 * Time: 13:47
 */

namespace Rbac\Form;

use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Submit;
use Laminas\Form\Form;

class LogForm extends Form
{

    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);
        $this->addElements();
    }

    protected function addElements()
    {
        // Add "email" field
        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => 'Votre email',
            ],
            'attributes' => [
                'class' => 'form-control',
            ]
        ]);

        // Add "password" field
        $this->add([
            'type' => Password::class,
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'class' => 'form-control',
            ]
        ]);

        // Add "remember_me" field
        $this->add([
            'type' => Checkbox::class,
            'name' => 'remember_me',
            'options' => [
                'label' => 'Remember me',
            ],
            'attributes' => [
                'class' => 'form-check-input',
            ]
        ]);

        // Add "redirect_url" field
        $this->add([
            'type' => Hidden::class,
            'name' => 'redirect_url'
        ]);

        // Add the CSRF field
        $this->add([
            'type' => Csrf::class,
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);

        // Add the Submit button
        $this->add([
            'type' => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'class'=>'btn btn-primary',
                'value' => 'Sign in',
                'id' => 'submit',
            ],
        ]);
    }

}