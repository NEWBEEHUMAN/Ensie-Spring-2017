<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 13-8-14
 * Time: 10:16
 */

namespace Ensie\MandrillMailerBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Ensie\MandrillMailerBundle\Mandrill\Template\DataGetter\DataGetterInterface;
use Ensie\MandrillMailerBundle\Mandrill\Template\TemplateConfiguration;
use Ensie\UserBundle\Entity\EnsieUser;
use Hip\MandrillBundle\Dispatcher;
use Hip\MandrillBundle\Message;

class MandrillMailerService {

    /** @var  Dispatcher */
    protected $mandrillMailDispatcher;

    protected $message;

    protected $deliveryAddress;

    function __construct($mandrillMailDispatcher, $deliveryAddress)
    {
        $this->mandrillMailDispatcher = $mandrillMailDispatcher;
        $this->message = new Message();
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @param $emailAddress
     * @param array $data
     */
    public function addReceiver($emailAddress, array $data){
        if($this->deliveryAddress){
            $this->message->addTo($this->deliveryAddress)->addMergeVars($this->deliveryAddress, $data);
        }else{
            $this->message->addTo($emailAddress)->addMergeVars($emailAddress, $data);
        }
    }

    /**
     * @param $templateName
     * @param $sendMail
     * @return array|bool
     */
    public function send($templateName, $sendMail){
        $result = false;
        if($sendMail){
            $result = $this->mandrillMailDispatcher->send($this->message, $templateName);
        }
        //Clear the message and ready to sent another one
        $this->message = new Message();
        return $result;
    }
}