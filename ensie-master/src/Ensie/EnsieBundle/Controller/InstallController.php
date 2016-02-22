<?php

namespace Ensie\EnsieBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\Ensie;
use Ensie\EnsieBundle\Entity\Keyword;
use Ensie\EnsieBundle\Entity\View;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\LanguageBundle\Entity\LanguageRepository;
use Ensie\NotificationBundle\Notification\NotificationService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\EnsieUserRepository;
use Proxies\__CG__\Ensie\NotificationBundle\Entity\Notification;
use Proxies\__CG__\Ensie\NotificationBundle\Entity\NotificationTemplate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class InstallController extends Controller
{
    /**
     * @Route("/notificatie")
     * @Template()
     */
    public function notificatieAction()
    {
        /** @var EnsieUserRepository $userRepository */
        $userRepository = $this->get('ensie.user_ensie_user_repository');
        $badjak = $userRepository->find(201);
        $paul = $userRepository->find(193);
        /** @var NotificationService $notificationService */
        $notificationService = $this->get('ensie.notification_service');
        $notificationService->createNotification($badjak, NotificationTemplate::IDENTIFIER_FAVORITE, array('ensieUser' => $paul), $paul);
        $this->getDoctrine()->getManager()->flush();
        die;
    }

    /**
     * @Route("/install/clear")
     * @Template()
     */
    public function clearCacheAction()
    {
        $fs = new Filesystem();
        $fs->remove($_SERVER['DOCUMENT_ROOT'] . '/../app/cache');
        $fs->mkdir($_SERVER['DOCUMENT_ROOT'] . '/../app/cache');
        $fs->remove($_SERVER['DOCUMENT_ROOT'] . '/../app/logs');
        $fs->mkdir($_SERVER['DOCUMENT_ROOT'] . '/../app/logs');
        echo 'clear done';
        die;
    }

    /**
     * @Route("/install/olddb")
     * @Template()
     */
    public function loadOldDatabaseaAction(){
        set_time_limit ( 100000 );
        /** @var EnsieUserRepository $ensieUserRepository */
        $ensieUserRepository = $this->get('ensie.user_ensie_user_repository');
        /** @var EnsieUser $redactieUser */
        $redactieUser = $ensieUserRepository->find(4);
        $redactieEnsie = $redactieUser->getEnsies()[0];

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        //SELECT * FROM user WHERE last_login > 0 and name_visible != 0; 47 users
        $csvUsers = $this->loadCsv('user.csv');
        $csvDefinitions = $this->loadCsv('definition.csv');
        /** @var LanguageRepository $languageRepository */
        $languageRepository = $this->get('ensie.language_language_repository');
        /** @var Language $language */
        $language = $languageRepository->find(2);

        $newData = array();
        $newData[0]['user'] = $redactieUser;
        $newData[0]['ensie'] = $redactieEnsie;
        foreach($csvUsers as $userArray){
            $user = $this->createUser($em, $userArray, $language);
            $ensie = $this->createEnsie($em, $user, $language);
            $newData[$userArray[0]]['user'] = $user;
            $newData[$userArray[0]]['ensie'] = $ensie;
        }
        foreach($csvDefinitions as $definitionArray){
            if(isset($newData[$definitionArray[9]])){
                $user = $newData[$definitionArray[9]]['user'];
                $ensie = $newData[$definitionArray[9]]['ensie'];
            } else {
                $user = $newData[0]['user'];
                $ensie = $newData[0]['ensie'];
            }
            $this->createDefinition($em, $user, $ensie, $language, $definitionArray);
            $em->flush();
            echo 'flush';
        }
        $em->flush();
        die('Succes');
        //$em->flush();
    }

    private function createDefinition(EntityManager $em, EnsieUser $user, Ensie $ensie, Language $language, $oldDefinitionArray){
        $definition = new Definition();
        $definition->setEnsieUser($user);
        $definition->setEnsie($ensie);
        $definition->setLanguage($language);
        $definition->setWord($oldDefinitionArray[1]);
        $definition->setDefinition($oldDefinitionArray[3]);
        $definition->setDescription($oldDefinitionArray[4]);
        $createdAt = new \DateTime();
        if($oldDefinitionArray[7] != 0){
            $createdAt->setTimestamp($oldDefinitionArray[7]);
        }
        $definition->setCreatedAt($createdAt);
        $definition->setStatus(Definition::ACTIVE_DEFINITION);
        $em->persist($definition);
        $this->createKeywords($em, $definition, $oldDefinitionArray[2]);
        $this->createViews($em, $definition, $oldDefinitionArray[8]);
    }

    private function createViews(EntityManager $em, Definition $definition, $amountOfViews){
        $definition->setStartViewCount($amountOfViews);
    }

    private function createKeywords(EntityManager $em, Definition $definition, $keywordsString){
        $keywords = explode(',', $keywordsString);
        foreach($keywords as $word){
            $word = trim($word);
            if(empty($word)){
            }else{
                $keyword = new Keyword();
                $keyword->setWord(trim($word));
                $keyword->setDefinition($definition);
                $em->persist($keyword);
            }
        }
    }

    private function createEnsie(EntityManager $em, EnsieUser $user, Language $language){
        $ensie = new Ensie();
        $ensie->setTitle('Overig');
        $ensie->setEnsieUser($user);
        $ensie->setLanguage($language);
        $em->persist($ensie);
        return $ensie;
    }
    private function createUser(EntityManager $em, $oldUserArray, Language $language){
        //Create user
        $user = new EnsieUser();
        $user->setUsername($oldUserArray[8]);
        $user->setFormattedName($oldUserArray[4]);
        $user->setEmail($oldUserArray[8]);
        $createdAt = new \DateTime();
        $createdAt->setTimestamp($oldUserArray[9]);
        $user->setCreatedAt($createdAt);
        if(!empty($oldUserArray[10]) and $oldUserArray[11]){
            $user->setLinkedInUrl($oldUserArray[10]);
        }
        if(!empty($oldUserArray[12]) and $oldUserArray[13]){
            $user->setGooglePlusUrl($oldUserArray[12]);
        }
        $user->setReceiveEmails($oldUserArray[15]);
        $password = substr(md5(rand(560000, 465413215)), 3, 8);
        $user->setPlainPassword($password);
        $user->setEnabled(1);
        $user->setEnabledWriter(1);
        $user->addRole('ROLE_ENSIE_USER');
        $user->setLanguage($language);
        $em->persist($user);
        return $user;
    }

    private function loadCsv($filename){

        $finder = new Finder();
        $finder->files()
            ->in($this->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'CSVFiles' . DIRECTORY_SEPARATOR)
            ->name($filename);

        $rows = array();
        foreach ($finder as $csv) {
            if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
                $i = 0;
                while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
                    $i++;
                    $rows[] = $data;
                }
                fclose($handle);
            }
        }
        return $rows;
    }
}
