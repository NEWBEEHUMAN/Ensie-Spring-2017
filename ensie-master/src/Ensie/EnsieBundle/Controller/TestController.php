<?php

namespace Ensie\EnsieBundle\Controller;


use Ensie\MandrillMailerBundle\Mandrill\Template\DataGetter\UserDataGetter;
use Ensie\MandrillMailerBundle\Mandrill\Template\TemplateConfiguration;
use Ensie\MandrillMailerBundle\Service\MandrillMailerService;
use Ensie\MandrillMailerBundle\Service\MandrillMailSender;
use Ensie\StatsBundle\Service\EmailStatsService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\EnsieUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hip\MandrillBundle\Message;
use Hip\MandrillBundle\Dispatcher;

class TestController extends Controller
{
    public function test500Action()
    {
        throw new \Exception('An error has occurred');
    }

    public function test403Action()
    {
        throw new AccessDeniedException('You cant be here');
    }

    /**
     * @Template()
     */
    public function emailStatsAction(){
        /** @var EmailStatsService $emailStatService */
        $emailStatService = $this->get('ensie.stats.email_stats_service');
        /** @var EnsieUserRepository $ensieUserRepository */
        $ensieUserRepository = $this->get('ensie.user_ensie_user_repository');
        $ensieUser = $ensieUserRepository->find('193');
        $data = $emailStatService->getStats($ensieUser);
        return array('data' => $data);
    }

    /**
     * @Template()
     */
    public function mandrillTestAction(){

        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->get('hip_mandrill.dispatcher');

        $message = new Message();

        $message
            //->setFromEmail('mail@example.com')
            //->setFromName('Customer Care')
            ->addTo('badjak1@gmail.com')
            ->setSubject('Some Subject')
            ->addMergeVar('badjak1@gmail.com', 'NAME', 'Badjak');

        $result = $dispatcher->send($message, 'test-template');

        return new Response('<pre>' . print_r($result, true) . '</pre>');
    }

    /**
     * @Template()
     */
    public function mandrillMailerAction(){
        /** @var EnsieUserRepository $ensieUserRepository */
        $ensieUserRepository = $this->get('ensie.user_ensie_user_repository');
        /** @var EnsieUser $user */
        $user = $ensieUserRepository->find(201);

        /** @var MandrillMailSender $mailSender*/
        $mailSender = $this->get('mandrill_mailer.mandrill_mail_sender');
        $result = $mailSender->send($user, 'reminder', 'third_reminder');
        var_dump($result);
        die;
die;
        return new Response('<pre>' . print_r($this->container->getParameter('mandrill_mailer.templates'), true) . '</pre>');
    }
}
