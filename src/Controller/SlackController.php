<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Msg;
use Symfony\Component\Mime\Email;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SlackController extends AbstractController
{
    private $Msg;
    protected $to;
    public $from;
    public $subject; 

    public function __construct(Msg $Msg, ParameterBagInterface $params, EventDispatcherInterface $eventDispatcher)
    {
        $this->Msg = $Msg;        
        $this->from = $params->get('from');
        $this->to = $params->get('to');
        $this->subject = $params->get('subject');
    }

    #[Route('/slacker', name: 'send_slack_email')]
    public function sendMsg(): Response
    {
        $emailMsg = new Email();
           
        $emailMsg->from('daveemail1@protonmail.com')
            ->to('recipient@email.com')
            ->subject('Ron Swanson')
            ->text('“Birthdays were invented by Hallmark to sell cards.”');

        $this->Msg->send($emailMsg);

        return new Response('Email sent out!');
    }
}
