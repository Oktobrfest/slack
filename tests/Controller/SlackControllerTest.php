<?php
namespace App\Tests\Controller;

use Psr\EventDispatcher\EventDispatcherInterface;
use App\Controller\SlackController;

use App\Msg;
use Symfony\Component\Mime\Email;
use 
Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SlackControllerTest extends WebTestCase
{
    public function testSlackController(): void
    {    
        $params_M = $this->createMock(ParameterBagInterface::class);
        $params_M->method('get')
            ->will($this->returnValueMap([
                ['from', 'daveemail1@protonmail.com'],
                ['to', 'recipient@email.com'],
                ['subject', 'Ron Swanson']
            ]));
        $Msg_M = $this->createMock(Msg::class);
        $Msg_M->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(Email::class));
          $EventDispatch_M = $this->createMock(EventDispatcherInterface::class);
        $slackController = new SlackController($Msg_M, $params_M, $EventDispatch_M);
        $res = $slackController->sendMsg();       
        $this->assertInstanceOf(Response::class, $res);
        $this->assertSame('daveemail1@protonmail.com', $slackController->from);
        $this->assertSame('Ron Swanson', $slackController->subject);
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertEquals('Email sent out!', $res->getContent());
        $this->assertEquals(200, $res->statusCode);
        $this->assertTrue($res->headers->has('Content-Type'));
$this->assertEquals('text/html; charset=UTF-8', $res->headers->get('Content-Type'));
    }
}
