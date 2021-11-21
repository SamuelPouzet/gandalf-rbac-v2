<?php

namespace Rbac\Service;

use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\Mime\Part;
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
     * @var PhpRenderer
     */
    protected $viewRenderer;

    /**
     * @param Message $message
     * @param array $config
     */
    public function __construct(array $config, Url $urlHelper, PhpRenderer $viewRenderer)
    {
        $this->message = new Message();
        $this->config = $config;
        $this->urlHelper = $urlHelper;
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * @return string
     */
    protected function getBodyMessage(string $template, array $config): \Laminas\Mime\Message
    {
        // Produce HTML of password reset email
        $bodyHtml = $this->viewRenderer->render(
            $template, $config
        );

        $html = new Part($bodyHtml);
        $html->type = "text/html";

        $body = new \Laminas\Mime\Message();
        $body->addPart($html);

        return $body;
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

        $login = $token->getUser()->getLogin();
        $token = $token->getToken();
        $url = $this->urlHelper;
        switch ($action) {
            case 'activate':
                $template = 'template/mail/activate-user';
                $route = $this->config['data']['host'] . $url('activate', ['token' => $token]);
                $config = [
                    'route' => $route,
                    'login' => $login,
                ];
                break;
            case 'reinitialize':
                $template = 'rbac/template/mail/recovery-password';
                $route = $this->config['data']['host'] . $url('password-recovery', ['token' => $token]);
                $config = [
                    'route' => $route,
                    'login' => $login,
                ];
                break;
            default:
                throw new \Exception('action not found');
        }
        $body = $this->getBodyMessage($template, $config);
        $this->message->setBody($body);

        $this->send();
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