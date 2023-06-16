<?php
namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Psr\Log\LoggerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
class Msg extends AbstractTransport
{
    private $slackConnection;
    private $httpClient;
    private $logger;

    public function __construct(string $slackUrl, string $slackKey, Client $httpClient, LoggerInterface $logger = null, EventDispatcherInterface $eventDispatcher = null)
{
    $this->slackConnection = $slackUrl . $slackKey;
    $this->httpClient = $httpClient;
    parent::__construct($eventDispatcher, $logger);
}

    protected function doSend(SentMessage $message): void
    {
         // Get the message body
        $msgBody = $message->getOriginalMessage()->getTextBody();
        try {

        $this->httpClient->post($this->slackConnection, [
            RequestOptions::JSON => [
                'text' => $msgBody,
            ],
        ]);

    } catch (\Exception $e) {
        $this->logger->error('Failed to send Slack email message');
    };
    }

    ### pull req-> need to figure out how __toString() is used in this class Heirarchy.  
    public function __toString(): string
    {
        return "";
    }
}
