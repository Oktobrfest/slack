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

    ### -> pull req: This didn't need to get done this way (a controller), but it made it simple. I hard coded the Msg in YAML, but likely this should be set dynamially depending on the requirements of the stakeholders.
    public function __construct(Msg $Msg, ParameterBagInterface $params, EventDispatcherInterface $eventDispatcher)
    {
        $this->Msg = $Msg;        
        $this->from = $params->get('from');
        $this->to = $params->get('to');
        $this->subject = $params->get('subject');
    }

    ###-> I considered passing in the variables differently, the way I did it makes it less flexible and easily changeable through code. I also could have instantiated the Email differently, or chosen to not use it at all, although the requirements seem to steer in that direction. Could have also use a try/catch here. 
    #[Route('/slacker', name: 'send_slack_email')]
    public function sendMsg(): Response
    {
        $emailMsg = new Email();
           
        $emailMsg->from($this->from)
            ->to($this->to)
            ->subject($this->subject)
            ->text('“Birthdays were invented by Hallmark to sell cards.”');

        $this->Msg->send($emailMsg);

        return new Response('Email sent out!');
    }
}
