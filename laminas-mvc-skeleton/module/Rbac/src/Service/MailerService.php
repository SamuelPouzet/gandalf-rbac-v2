<?php

namespace Rbac\Service;

use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\View\Helper\Url;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplateMapResolver;
use Rbac\Entity\UserToken;

/**
 *
 */
class MailerService
{

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Url
     */
    protected $urlHelper;

    /**
     * @var TemplateMapResolver
     */
    protected $templateMapResolver;

    /**
     * @param Message $message
     * @param array $config
     */
    public function __construct(array $config, Url $urlHelper, TemplateMapResolver $templateMapResolver)
    {
        $this->message = new Message();
        $this->config = $config;
        $this->urlHelper = $urlHelper;
        $this->templateMapResolver = $templateMapResolver;
    }

    /**
     * inactive getBodyMessage
     * @todo use renderer to have a template file for mail body
     * @return string
     */
    public function getBodyMessage(): string
    {

        $model = new ViewModel();
        $renderer = new PhpRenderer();
        $name = 'mailtemplate/activation';

        $model->setTemplate($name);

        if (!$this->templateMapResolver->has($name)) {
            die('not found');
        } elseif (!file_exists($this->templateMapResolver->get($name))) {
            var_dump($this->templateMapResolver->get($name));
            die('template not found');
        }

        $data = $renderer->render($model);

        var_dump($this->templateMapResolver->get('activation'));
        die;
    }

    /**
     * @param UserToken $token
     * @param string $action
     * @throws \Exception
     */
    public function createMessage(UserToken $token, string $action = 'activate')
    {

        $this->message->setEncoding('UTF-8');
        $this->message->addFrom('noreply@sampouzet.fr', 'webmaster Sam');
        $this->message->addTo($token->getUser()->getEmail());
        $this->message->setSubject('validation de votre compte sur le site Sam POUZET');
        switch ($action) {
            case 'activate':
                $this->message->setBody($this->generateActivationBody($token));
                break;
            case 'reinitialize':
                $this->message->setBody($this->generateinitializationBody($token));
                break;
            default:
                throw new \Exception('action not found');
        }


        $this->send();
    }

    /**
     * @param UserToken $token
     * @return string
     */
    private function generateActivationBody(UserToken $token): string
    {
        $login = $token->getUser()->getLogin();
        $token = $token->getToken();
        $url = $this->urlHelper;
        $destination = $this->config['data']['host'] . $url('activate', ['token' => $token]);

        $body =
            <<<TEXT
Bonjour $login

Vous vous êtes inscrits sur le site de sam POUZET
Pour finaliser votre inscription, il est nécessaire de cliquer sur le lien suivant
$destination

Si vous n'avez pas validé l'inscription après 48 heures, vous ne pourrez plus utiliser ce lien.

TEXT;

        return $body;
    }

    /**
     * @param UserToken $token
     * @return string
     */
    private function generateinitializationBody(UserToken $token): string
    {
        $login = $token->getUser()->getLogin();
        $token = $token->getToken();
        $url = $this->urlHelper;
        $destination = $this->config['data']['host'] . $url('password-recovery', ['token' => $token]);

        $body =
            <<<TEXT
Bonjour $login

Vous avez demandé la réinitialisation du mot de passe sur le site de sam POUZET
Pour ce faire, il est nécessaire de cliquer sur le lien suivant
$destination

Si vous n'avez pas demandé cette opération, vous n'avez qu'à ignorer ce message.

TEXT;

        return $body;
    }

    /**
     * send
     * used to send mail
     */
    private function send()
    {
        $transport = new Smtp();

        $options = new SmtpOptions($this->config['config']);
        $transport->setOptions($options);
        $transport->send($this->message);
    }

}