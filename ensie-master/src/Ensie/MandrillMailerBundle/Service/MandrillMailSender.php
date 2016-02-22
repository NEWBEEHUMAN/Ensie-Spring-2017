<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 14-11-14
 * Time: 16:46
 */

namespace Ensie\MandrillMailerBundle\Service;


use Ensie\MandrillMailerBundle\Mandrill\Template\DataGetter\DataGetterInterface;
use Ensie\MandrillMailerBundle\Mandrill\Template\TemplateConfiguration;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MandrillMailSender {

    /** @var TemplateConfiguration */
    protected $templateConfiguration;

    /** @var  ContainerInterface
    maybe a datagetterfactory??*/
    protected $container;

    /** @var MandrillMailerService */
    protected $mailerService;

    /**
     * @param ContainerInterface $container
     * @param TemplateConfiguration $templateConfiguration
     * @param MandrillMailerService $mailerService
     */
    function __construct(ContainerInterface $container, TemplateConfiguration $templateConfiguration, MandrillMailerService $mailerService)
    {
        $this->container = $container;
        $this->templateConfiguration = $templateConfiguration;
        $this->mailerService = $mailerService;
    }

    /**
     * @param EnsieUser $ensieUser
     * @param $category
     * @param $type
     * @param array $extraData
     * @param string $toEmail
     * @return array|bool
     */
    public function send(EnsieUser $ensieUser, $category, $type, $extraData = array(), $toEmail = ''){
        $template = $this->templateConfiguration->get($category, $ensieUser->getLocale(), $type);
        /** @var DataGetterInterface $dataGetter */
        $dataGetter = $this->container->get($template->getDataGetterContainerKey());
        $dataGetter->setUser($ensieUser);
        $dataGetter->addExtraData($extraData);
        if(!empty($toEmail)){
            $this->mailerService->addReceiver($toEmail, $dataGetter->getData());
        } else {
            $this->mailerService->addReceiver($ensieUser->getEmail(), $dataGetter->getData());
        }
        return $this->mailerService->send($template->getName(), $dataGetter->containsData());
    }
}