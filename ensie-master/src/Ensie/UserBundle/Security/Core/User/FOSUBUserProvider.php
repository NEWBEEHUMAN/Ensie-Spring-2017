<?php
namespace Ensie\UserBundle\Security\Core\User;

use Ensie\LanguageBundle\Service\LanguageService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Security\Core\Exception\ResourceOwnerNotFoundException;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseClass
{

    /**
     * @var EnsieUserManager
     */
    protected $userManager;

    protected $dispatcher;

    public function __construct(UserManagerInterface $userManager, array $properties, EventDispatcherInterface $dispatcher)
    {
        parent::__construct($userManager, $properties);
        $this->dispatcher = $dispatcher;
    }


    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        //when the user is registrating
        if (null === $user) {
            $user = $this->createBasicUser($response, $username);
            $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, new Request(), new Response()));
            return $user;
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }

    public function createBasicUser(UserResponseInterface $response, $username)
    {
        $user = $this->createUserByResponse($response, $username);
        switch($response->getResourceOwner()->getName())
        {
            case 'linkedin':
                $user = $this->addLinkedInProfileToUser($user, $response->getResponse());
                break;
//            case 'google':
//                $user = $this->addGoogleProfileToUser($user, $response->getResponse());
//                break;
            default:
                throw new ResourceOwnerNotFoundException('ResourceOwner ' . $response->getResourceOwner()->getName() . ' not found.');
        }
        $this->userManager->updateUser($user);
        return $user;
    }

    private function createUserByResponse(UserResponseInterface $response, $username)
    {
        $service = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';
        // create new user here
        /** @var EnsieUser $user */
        //try to find user by email:
        $user = $this->userManager->findUserByEmail($response->getEmail());
        if(empty($user)){
            $user = $this->userManager->createUser();
        }
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        //I have set all requested data with the user's username
        //modify here with relevant data
        $user->setUsername($username);
        $user->setEmail($response->getEmail());
        $user->setPassword($username);
        $user->setEnabled(true);
        //$user->addRole('ROLE_ENSIE_USER');

        $language = $this->userManager->getCurrentLanguage();
        $user->setLanguage($language);

        return $user;
    }

    private function addLinkedInProfileToUser(EnsieUser $user, array $response)
    {
        if (!$user->getEmail()) {
            $user->setEmail($response['emailAddress']);
        }
        if (!$user->getFirstName()) {
            $user->setFirstName($response['firstName']);
        }
        if (!$user->getFormattedName()) {
            $user->setFormattedName($response['formattedName']);
        }
        if (!$user->getHeadline()) {
            $user->setHeadline($response['headline']);
        }
        if (!$user->getLastName()) {
            $user->setLastName($response['lastName']);
        }
        if(isset($response['publicProfileUrl'])) {
            $user->setLinkedInUrl($response['publicProfileUrl']);
        }
        if(isset($response['location']['country']['code'])){
            if($response['location']['country']['code'] == 'nl'){
                $user->setLocale($response['location']['country']['code']);
            } else {
                $user->setLocale('en');
            }
        }

        $user->setTermsAccepted(true);

        if(isset($response['pictureUrls']) and !$user->getPicture()){
            try{
                if(isset($response['pictureUrls']) and isset($response['pictureUrls']['values']) and isset($response['pictureUrls']['values'][0])){
                    $pictureUrl = $response['pictureUrls']['values'][0];
                    $pathInfo = pathInfo($pictureUrl);
                    $tmpPath = sys_get_temp_dir(). DIRECTORY_SEPARATOR . $pathInfo['filename']. '.jpg';
                    copy($pictureUrl, $tmpPath);
                    $uploadedFile = new UploadedFile($tmpPath, $pathInfo['filename'] . '.jpg', null, null, null, true);
                    $user->setPictureFile($uploadedFile);
                }
            }catch(\Exception $e){
                //$user->setPicture($response['pictureUrl']);
            }
        }
        return $user;
    }
}