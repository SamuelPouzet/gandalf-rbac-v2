<?php

namespace Rbac\Form;

use Application\Entity\Article;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\FileInput;

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
    public function __construct($name = null, $strategy="create", array $options = [])
    {
        parent::__construct($name, $options);

        $this->setAttribute('enctype', 'multipart/form-data');

        $this->addElements($strategy);
        $this->addInputFilters();
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
                'class'=>'form-control',
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
                'class'=>'form-control',
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
                'class'=>'form-control',
            ],
            'options' => [
                'label' => 'Name',
            ],
        ]);

        $this->add([
            'type'  =>File::class,
            'name' => 'avatar',
            'attributes' => [
                'id' => 'img',
                'class'=> 'custom-file-input',
            ],
            'options' => [
                'label' => 'Avatar',
                'labelAttributes' => [
                    'class'=> 'custom-file-label',
                ]
            ],
        ]);

        $this->get('avatar')->setLabelAttributes(['class'=> 'custom-file-label']);

        $this->add([
            'type' => Text::class,
            'name' => 'firstname',
            'attributes' => [
                'id' => 'firstname',
                'class'=>'form-control',
            ],
            'options' => [
                'label' => 'Prénom',
            ],
        ]);

        if($strategy == 'create'){
            $this->add([
                'type' => Password::class,
                'name' => 'password',
                'attributes' => [
                    'id' => 'password',
                    'class'=>'form-control',
                ],
                'options' => [
                    'label' => 'Mot de passe',
                ],
            ]);
        }


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
            'type'  => Submit::class,
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
    private function addInputFilters(): void
    {
        // Create input filter
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'type'     => FileInput::class,
            'name'     => 'avatar',  // Element's name.
            'required' => true,    // Whether the field is required.
            'validators' => [
                ['name'    => 'FileUploadFile'],
                [
                    'name'    => 'FileMimeType',
                    'options' => [
                        'mimeType'  => ['image/jpeg', 'image/png']
                    ]
                ],
                ['name'    => 'FileIsImage'],
                [
                    'name'    => 'FileImageSize',
                    'options' => [
                        'minWidth'  => 128,
                        'minHeight' => 128,
                        'maxWidth'  => 4096,
                        'maxHeight' => 4096
                    ]
                ],
            ],
            'filters'  => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        //@Todo set in local config
                        'target' => '/',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                    ]
                ]
            ],
        ]);
    }
}