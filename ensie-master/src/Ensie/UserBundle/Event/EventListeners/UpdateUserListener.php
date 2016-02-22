<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 15-8-14
 * Time: 13:33
 */

namespace Ensie\UserBundle\Event\EventListeners;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Ensie\EnsieBundle\Entity\Definition;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\EnsieUserRepository;
use Ensie\UserBundle\Security\Core\User\EnsieUserManager;
use Symfony\Component\DependencyInjection\Container;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Filesystem\Filesystem;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;


class UpdateUserListener implements EventSubscriber{

    /** @var CacheManager */
    private $liiCacheManager;

    /** @var  UploaderHelper */
    private $uploaderHelper;

    /** @var EnsieUserRepository */
    private $ensieUserRepository;

    /** @var \Symfony\Component\DependencyInjection\Container  */
    private $ensieUserRepositoryContainer;

    /** @var Container  */
    private $ensieUserManagerContainer;

    /**
     * @param Container $container circular reference
     * @param CacheManager $liiCacheManager
     * @param UploaderHelper $uploaderHelper
     */
    function __construct(Container $container, CacheManager $liiCacheManager, UploaderHelper $uploaderHelper)
    {
        $this->ensieUserRepositoryContainer = $container;
        $this->liiCacheManager = $liiCacheManager;
        $this->uploaderHelper = $uploaderHelper;
        $this->ensieUserManagerContainer = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            "prePersist",
            "preUpdate",
            "preRemove"
        );
    }

    public function prePersist(LifecycleEventArgs $event){
        if($ensieUser = $this->getEnsieUser($event)){
            $this->updateFormattedName($ensieUser);
            $this->updateUserSlug($ensieUser);
        }
    }

    public function preUpdate(PreUpdateEventArgs $event){
        if($ensieUser = $this->getEnsieUser($event)){
            if($event->hasChangedField('picture') or $event->hasChangedField('pictureFile')){
                $this->removeCachedImage($ensieUser);
            }
            //Update the formatedName if the user is created by registration or other api's
            $this->updateFormattedName($ensieUser);
            //Update the username if it is a company
            $this->updateUserName($ensieUser);
            //Kind of ugly
            $this->ensieUserManagerContainer->get('ensie.ensie_user_manager')->updateCanonicalFields($ensieUser);
            //Slug is set on the persist and can't be changed the link url will change and that is not possible
            //$this->updateUserSlug($event);
        }
    }

    public function preRemove(LifecycleEventArgs $event){
        if($ensieUser = $this->getEnsieUser($event)){
            $this->removeCachedImage($ensieUser);
        }
    }

    /**
     * @param LifecycleEventArgs $event
     * @return bool|EnsieUser
     */
    private function getEnsieUser(LifecycleEventArgs $event){
        /** @var EnsieUser $ensieUser */
        $ensieUser = $event->getEntity();
        if($ensieUser instanceof EnsieUser){
            return $ensieUser;
        }
        return false;
    }

    //Move to a service?
    private function updateFormattedName(EnsieUser $ensieUser){
        if(!$ensieUser->getFormattedname() or $ensieUser->getFormattedname() == 'empty'){
            $ensieUser->setFormattedname($ensieUser->getFirstName() . ' ' . $ensieUser->getLastName());
        }
    }

    private function updateUserName(EnsieUser $ensieUser){
        if($ensieUser->isCompany()){
            $ensieUser->setUsername($ensieUser->getEmail());
        }
    }

    private function updateUserSlug(EnsieUser $ensieUser){
        $this->ensieUserRepository = $this->ensieUserRepositoryContainer->get('ensie.user_ensie_user_repository');
        $ensieUser->setSlug('');
        $ensieUser->setExtraSlugField(null);
        $this->updateSlug($ensieUser);
    }

    private function updateSlug(EnsieUser $ensieUser){
        $ensieUser->generateSlug();
        if($this->ensieUserRepository->isUsableSlug($ensieUser)){
            $ensieUser->setExtraSlugField($ensieUser->getExtraSlugField() + 1);
            $ensieUser->setSlug('');
            $this->updateSlug($ensieUser);
            return true;
        }
        return false;
    }

    private function removeCachedImage(EnsieUser $ensieUser){
        $path = $this->uploaderHelper->asset($ensieUser, 'profile_image');
        $dirsToDelete[] = parse_url($this->liiCacheManager->resolve($path, 'big'), PHP_URL_PATH);
        $dirsToDelete[] = parse_url($this->liiCacheManager->resolve($path, 'medium'), PHP_URL_PATH);
        $dirsToDelete[] = parse_url($this->liiCacheManager->resolve($path, 'small'), PHP_URL_PATH);
        $dirsToDelete[] = parse_url($this->liiCacheManager->resolve($path, 'mini'), PHP_URL_PATH);
        $dirsToDelete[] = parse_url($this->liiCacheManager->resolve($path, 'micro'), PHP_URL_PATH);
        $fs = new Filesystem();
        foreach($dirsToDelete as $path){
            try{
                $fs->remove($_SERVER['DOCUMENT_ROOT'] . $path);
            }
            catch(\Exception $e){

            }
        }
    }
} 