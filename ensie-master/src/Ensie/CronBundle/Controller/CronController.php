<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 17-9-14
 * Time: 15:53
 */

namespace Ensie\CronBundle\Controller;


use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\EnsieBundle\Service\DefinitionService;
use Ensie\MandrillMailerBundle\Service\MandrillMailSender;
use Ensie\StatsBundle\Service\EmailStatsService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\EnsieUserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CronController extends Controller
{

    /**
     * @Template()
     */
    public function statsMailAction(){
        set_time_limit(500);

        /** @var MandrillMailSender $mailSender */
        $mailSender = $this->get('mandrill_mailer.mandrill_mail_sender');
        /** @var EnsieUserRepository $userRepository */
        $userRepository = $this->get('ensie.user_ensie_user_repository');
        /** @var DefinitionService $definitionService */
        $definitionService = $this->get('ensie.ensie_definition_service');

        $usersToMail = $userRepository->getUsersForStatsMail(25);

        /** @var LoggerInterface $logger */
        $logger = $this->get('monolog.logger.flow');
        $logger->info('Start stats mail action');
        if(count($usersToMail) > 0){
            /** @var $user EnsieUser */
            foreach($usersToMail as $user){
                $definitionService->updateStatsDefinitionArray($user->getDefinitions());
                $logger->info('Stats email', array($user->getEmail(), $user->getId()));
                $status = $mailSender->send($user, 'stats', 'stats');
                $logger->info('Status:', array($status));
                $dateStats = new \DateTime();
                $dateStats = $dateStats->add(\DateInterval::createFromDateString(EmailStatsService::INTERVAL))->sub(\DateInterval::createFromDateString('1 minute'));
                $user->setSendStats($dateStats);
                $dateNotification = new \DateTime();
                $dateNotification = $dateNotification->add(\DateInterval::createFromDateString(EmailStatsService::INTERVAL))->sub(\DateInterval::createFromDateString('1 minute'));
                $dateNotification->sub(\DateInterval::createFromDateString('1 week')); //Next notification email
                $user->setSendNotifications($dateNotification);
            }
        } else {
            $logger->info('No users to mail');
        }
        $this->getDoctrine()->getManager()->flush();
        return array('data' => count($usersToMail) . ' = ' . time());
    }

    /**
     * @Template()
     */
    public function notificationBartMailAction(){
        set_time_limit(500);
        /** @var MandrillMailSender $mailSender */
        $mailSender = $this->get('mandrill_mailer.mandrill_mail_sender');
        /** @var EnsieUserRepository $userRepository */
        $userRepository = $this->get('ensie.user_ensie_user_repository');
        $usersToMail[] = $userRepository->find(190);
        /** @var LoggerInterface $logger */
        $logger = $this->get('monolog.logger.flow');
        $logger->info('Start notification mail action');
        if(count($usersToMail) > 0){
            /** @var $user EnsieUser */
            foreach($usersToMail as $user){
                $logger->info('Notification email', array($user->getEmail(), $user->getId()));
                $status = $mailSender->send($user, 'stats', 'notification');
                $logger->info('Status:', array($status));
                $date = new \DateTime();
            }
        } else {
            $logger->info('No users to mail');
        }
        $this->getDoctrine()->getManager()->flush();
        return array('data' => count($usersToMail) . ' = ' . time());
    }

    /**
     * @Template()
     */
    public function notificationMailAction(){
        set_time_limit(500);
        /** @var MandrillMailSender $mailSender */
        $mailSender = $this->get('mandrill_mailer.mandrill_mail_sender');
        /** @var EnsieUserRepository $userRepository */
        $userRepository = $this->get('ensie.user_ensie_user_repository');
        $usersToMail = $userRepository->getUsersForNotificationMail(25);
        /** @var LoggerInterface $logger */
        $logger = $this->get('monolog.logger.flow');
        $logger->info('Start notification mail action');
        if(count($usersToMail) > 0){
            /** @var $user EnsieUser */
            foreach($usersToMail as $user){
                $logger->info('Notification email', array($user->getEmail(), $user->getId()));
                $status = $mailSender->send($user, 'stats', 'notification');
                $logger->info('Status:', array($status));
                $date = new \DateTime();
                $date = $date->add(\DateInterval::createFromDateString(EmailStatsService::INTERVAL))->sub(\DateInterval::createFromDateString('1 minute'));
                $user->setSendNotifications($date);
            }
        } else {
            $logger->info('No users to mail');
        }
        $this->getDoctrine()->getManager()->flush();
        return array('data' => count($usersToMail) . ' = ' . time());
    }

    public function reminderMailAction(){
        set_time_limit(500);
        /** @var MandrillMailSender $mailSender */
        $mailSender = $this->get('mandrill_mailer.mandrill_mail_sender');
        /** @var EnsieUserRepository $userRepository */
        $userRepository = $this->get('ensie.user_ensie_user_repository');
        $configuration = $this->container->getParameter('ensie_cron');

        /** @var LoggerInterface $logger */
        $logger = $this->get('monolog.logger.flow');

        $this->sendReminder($logger, $mailSender, $userRepository->getFirstReminderMail($configuration['mailer']['reminder']['first']), 'first_reminder');
        $this->sendReminder($logger, $mailSender, $userRepository->getSecondReminderMail($configuration['mailer']['reminder']['second']), 'second_reminder');
        $this->sendReminder($logger, $mailSender, $userRepository->getThirdReminderMail($configuration['mailer']['reminder']['third']), 'third_reminder');
        $this->sendReminder($logger, $mailSender, $userRepository->getExtraReminderMail($configuration['mailer']['reminder']['extra']), 'extra_reminder');
        die('succes');
    }

/**
* @param LoggerInterface $logger
* @param MandrillMailSender $mailSender
* @param array $usersToMail
* @param $type
 */
    private function sendReminder(LoggerInterface $logger, MandrillMailSender $mailSender, array $usersToMail, $type){
        /** @var EnsieUser $user */
        if(is_array($usersToMail) and !empty($usersToMail)){
            foreach($usersToMail as $user){
                try{
                    $result = $mailSender->send($user, 'reminder', $type);
                    $logger->info('Reminder', array($user->getEmail(), $user->getId(), $type, $result));
                    if(isset($result[0]) and isset($result[0]['status']) and $result[0]['status'] == 'sent'){
                        //Create class for reminder mails?
                        switch($type){
                            case 'first_reminder':
                                $user->setFirstReminder(new \DateTime());
                                break;
                            case 'second_reminder':
                                $user->setSecondReminder(new \DateTime());
                                break;
                            case 'third_reminder':
                                $user->setThirdReminder(new \DateTime());
                                break;
                            case 'extra_reminder':
                                $user->setExtraReminder(new \DateTime());
                                break;
                        }
                    }
                }
                catch(\Exception $e){
                    $logger->error('Reminder exception:', array($e->getMessage()));
                }
            }
            $this->getDoctrine()->getManager()->flush();
        }
    }

}