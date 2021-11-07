<?php

namespace Rbac\Form;

use Application\Entity\Article;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\FileInput;
use Rbac\Entity\User;

/**
 * UserForm
 */
class UserForm extends Form
{

    /**
     * @param null $name
     * @param string $strategy
     * @param array $options
     */
    public function __construct($name = null, $strategy = "create", array $options = [])
    {
        parent::__construct($name, $options);

        $this->setAttribute('enctype', 'multipart/form-data');

        $this->addElements($strategy);
        $this->addInputFilters($strategy);
    }

    /**
     * addElements
     * @param string $strategy
     */
    protected function addElements(string $strategy): void
    {
        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'attributes' => [
                'id' => 'content',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Email',
            ],
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'login',
            'attributes' => [
                'id' => 'login',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Login',
            ],
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'name',
            'attributes' => [
                'id' => 'name',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Name',
            ],
        ]);

        $this->add([
            'type' => File::class,
            'name' => 'avatar',
            'attributes' => [
                'id' => 'img',
            ],
            'options' => [
                'label' => 'Avatar',
            ],
        ]);

        //$this->get('avatar')->setLabelAttributes(['class'=> 'custom-file-label']);

        $this->add([
            'type' => Text::class,
            'name' => 'firstname',
            'attributes' => [
                'id' => 'firstname',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Prénom',
            ],
        ]);

        if ($strategy == 'create') {
            $this->add([
                'type' => Password::class,
                'name' => 'password',
                'attributes' => [
                    'id' => 'password',
                    'class' => 'form-control',
                ],
                'options' => [
                    'label' => 'Mot de passe',
                ],
            ]);
        }

        $this->add([
            'type' => Select::class,
            'name' => 'status',
            'attributes' => [
                'id' => 'status',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Status',
                'value_options'=>[
                    User::USER_NOT_ACTVATED => 'non activé',
                    User::USER_ACTVATED => 'Activé',
                    User::USER_INACTIVE => 'Désactivé',
                    User::USER_RETIRED => 'Supprimé',
                ],
            ],
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
                'value' => 'Change',
                'id' => 'submit',
            ],
        ]);

        $this->get('submit')->setValue('Enregistrer');
    }


    /**
     *addInputFilters
     */
    private function addInputFilters($strategy): void
    {
        // Create input filter
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'type' => FileInput::class,
            'name' => 'avatar',  // Element's name.
            'required' => $strategy == 'create',    // Whether the field is required.
            'validators' => [
                ['name' => 'FileUploadFile'],
                [
                    'name' => 'FileMimeType',
                    'options' => [
                        'mimeType' => ['image/jpeg', 'image/png']
                    ]
                ],
                ['name' => 'FileIsImage'],
                [
                    'name' => 'FileImageSize',
                    'options' => [
                        'minWidth' => 128,
                        'minHeight' => 128,
                        'maxWidth' => 4096,
                        'maxHeight' => 4096
                    ]
                ],
            ],
            'filters' => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target' => User::IMAGE_PATH,
                        'useUploadName' => false,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                    ]
                ]
            ],
        ]);
    }
}