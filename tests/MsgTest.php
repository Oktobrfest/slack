<?php

namespace App\Tests;

use App\Msg;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\SentMessage;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Mime\RawMessage;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class MsgTest extends TestCase
{
    private $logger_M;
    private $client_M;
    private $msg_M;
    private $connection_M;
    private $email_M;
    private $sentMessage_M;
    private $messageBody_M;
    private $RawMessage_M;
    
        protected function setUp(): void
        {
            $this->logger_M = $this->createMock(LoggerInterface::class);
            $this->client_M = $this->createMock(Client::class);
            $url_M = 'http://test_url';
            $key_M = 'testkey555';
            $this->RawMessage_M = new RawMessage('');
            $this->msg_M = new Msg($url_M, $key_M, $this->client_M, $this->logger_M);
            $this->connection_M = $url_M  . $key_M;
            $this->email_M = new Email();
            $this->email_M->from('from_test@test.com')
                        ->to('to@test_send.com')
                        ->text('Test body txt');
            $this->sentMessage_M = $this->createMock(SentMessage::class);
            $this->messageBody_M = $this->email_M->getTextBody();
        }

        // positive verification
        public function testDoSendOnce(): void
        {   /// this test case tests 'behavior verification' of the guzzle dependency(httpclient)
            $this->client_M->expects($this->once())
                ->method('post')
                ->with(
                    $this->connection_M,
                    [RequestOptions::JSON => ['text' => $this->messageBody_M]]
                );

                $this->logger_M->expects($this->never())
                ->method('error');
                
            $this->msg_M->send($this->RawMessage_M);
        }

        // negative verification
  public function testDoSendFailsOnce(): void
    {
        $this->client_M->expects($this->once())
            ->method('post')
            ->willThrowException(new RequestException(
                'Throw a mock error for testing',
                new Request('POST', $this->messageBody_M)
            ));
            
        $this->logger_M->expects($this->once())
            ->method('error')
            ->with($this->messageBody_M);

        $this->msg_M->send($this->RawMessage_M);
    }

    public function testToString(): void
{
    $this->assertEquals('', $this->msg_M->__toString());
}
}