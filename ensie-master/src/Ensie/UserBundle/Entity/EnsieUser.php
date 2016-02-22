<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-4-14
 * Time: 21:22
 */

namespace Ensie\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\SubscriptionBundle\Entity\Subscription;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="Ensie\UserBundle\Entity\EnsieUserRepository")
 * @ORM\Table(indexes={@ORM\Index(name="user_enabledWriter", columns={"enabled_writer"}), @ORM\Index(name="user_enabled", columns={"enabled"})})
 * @Vich\Uploadable
 */
class EnsieUser extends BaseUser{

    use ORMBehaviors\Timestampable\Timestampable;
    use ORMBehaviors\Sluggable\Sluggable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="formattedname", type="string", length=255)
     */
    private $formattedName = 'empty';

    /**
     * @var integer
     *
     * @ORM\Column(name="extraSlugField", type="integer", nullable=true)
     */
    private $extraSlugField;

    /**
     * @var string
     *
     * @ORM\Column(name="headline", type="string", length=255, nullable=true)
     */
    private $headline = '';

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    private $bio = '';

    /**
     * @var string
     *
     * @Vich\UploadableField(mapping="profile_image", fileNameProperty="picture")
     */
    private $pictureFile;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;


    /**
     * @var string
     *
     * @ORM\Column(name="linkedinurl", type="string", length=255, nullable=true)
     */
    private $linkedInUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="googleplusurl", type="string", length=255, nullable=true)
     */
    private $googlePlusUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=6, nullable=true)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    private $enabledWriter = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    private $firstLogin = 1;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    private $firstWrite = 1;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    private $receiveEmails = 1;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $firstReminder;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $secondReminder;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $thirdReminder;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $extraReminder;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sendStats;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sendNotifications;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Ensie\LanguageBundle\Entity\Language", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $language;

    /** @ORM\Column(name="linkedin_id", type="string", length=255, nullable=true) */
    protected $linkedin_id;

    /** @ORM\Column(name="linkedin_access_token", type="string", length=255, nullable=true) */
    protected $linkedin_access_token;

    /**
     * V 1.1 Update
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyName;

    /**
     * V 1.1 Update
     * @var boolean
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telnumber;

    /**
     * V 1.1 Update
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $websiteUrl;

    /**
     * V 1.1 Update
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255)
     */
    private $isCompany = 0;

    /**
     * V 1.1 Update
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $termsAccepted;

    /**
     * V 1.1 Update
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Ensie\SubscriptionBundle\Entity\Subscription", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $subscription;

    /**
     * @ORM\OneToMany(targetEntity="Ensie\EnsieBundle\Entity\Ensie", mappedBy="ensieUser")
     */
    protected $ensies;

    /**
     * @ORM\OneToMany(targetEntity="Ensie\EnsieBundle\Entity\Definition", mappedBy="ensieUser")
     */
    protected $definitions;

    /**
     * V 1.2 Update
     * @var string
     *
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    protected $contactSetting = self::CONTACT_EVERYONE;

    /**
     * @var string
     *
     * @Vich\UploadableField(mapping="profile_header", fileNameProperty="headerImage")
     */
    private $headerImageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="header_image", type="string", length=255, nullable=true)
     */
    private $headerImage;

    const CONTACT_EVERYONE = 'everyone';
    const CONTACT_WRITERS = 'writers';
    const CONTACT_NONE = 'none';

    /**
     * @var array
     */
    static private $contactSettingOptions = array(
        self::CONTACT_EVERYONE => self::CONTACT_EVERYONE,
        self::CONTACT_WRITERS => self::CONTACT_WRITERS,
        self::CONTACT_NONE => self::CONTACT_NONE,
    );

    /**
     *
     */
    function __construct()
    {
        parent::__construct();
        $this->ensies = new ArrayCollection();
        $this->definitions = new ArrayCollection();
        $this->username = 'username';
    }

    public function __toString()
    {
        return  $this->getFormattedName();
    }

    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);
        return $this;
    }

    /**
     * @return array
     */
    public function getSluggableFields()
    {
        if($this->isCompany()){
            return [ 'companyName' ];
        }
        return [ 'formattedName', 'extraSlugField' ];
    }

    public function getRegenerateSlugOnUpdate(){
        //This can be overridden bij de EventListener
        return false;
    }

    /**
     * @param $linkedin_access_token
     */
    public function setLinkedinAccessToken($linkedin_access_token)
    {
        $this->linkedin_access_token = $linkedin_access_token;
    }

    /**
     * @return mixed
     */
    public function getLinkedinAccessToken()
    {
        return $this->linkedin_access_token;
    }

    /**
     * @param $linkedin_id
     */
    public function setLinkedinId($linkedin_id)
    {
        $this->linkedin_id = $linkedin_id;
    }

    /**
     * @return mixed
     */
    public function getLinkedinId()
    {
        return $this->linkedin_id;
    }

    /**
     * @param ArrayCollection $ensies
     */
    public function setEnsies($ensies)
    {
        $this->ensies = $ensies;
    }

    /**
     * @return ArrayCollection
     */
    public function getEnsies()
    {
        return $this->ensies;
    }

    /**
     * @param mixed $definitions
     */
    public function setDefinitions($definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * @return ArrayCollection
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * @param string $bio
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param integer $extraSlugField
     */
    public function setExtraSlugField($extraSlugField)
    {
        $this->extraSlugField = $extraSlugField;
    }

    /**
     * @return integer
     */
    public function getExtraSlugField()
    {
        return $this->extraSlugField;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $formattedName
     */
    public function setFormattedName($formattedName)
    {
        $this->formattedName = $formattedName;
    }

    /**
     * @return string
     */
    public function getFormattedName()
    {
        return $this->formattedName;
    }

    /**
     * @param string $googlePlusUrl
     */
    public function setGooglePlusUrl($googlePlusUrl)
    {
        $this->googlePlusUrl = $googlePlusUrl;
    }

    /**
     * @return string
     */
    public function getGooglePlusUrl()
    {
        return $this->googlePlusUrl;
    }

    /**
     * @param string $headline
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;
    }

    /**
     * @return string
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @param int $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $linkedInUrl
     */
    public function setLinkedInUrl($linkedInUrl)
    {
        $this->linkedInUrl = $linkedInUrl;
    }

    /**
     * @return string
     */
    public function getLinkedInUrl()
    {
        return $this->linkedInUrl;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $enabledWriter
     */
    public function setEnabledWriter($enabledWriter)
    {
        $this->enabledWriter = $enabledWriter;
    }

    /**
     * @return string
     */
    public function getEnabledWriter()
    {
        return $this->enabledWriter;
    }

    /**
     * @param string $receiveEmails
     */
    public function setReceiveEmails($receiveEmails)
    {
        $this->receiveEmails = $receiveEmails;
    }

    /**
     * @return string
     */
    public function getReceiveEmails()
    {
        return $this->receiveEmails;
    }

    /**
     * @param string $firstWrite
     */
    public function setFirstWrite($firstWrite)
    {
        $this->firstWrite = $firstWrite;
    }

    /**
     * @return string
     */
    public function getFirstWrite()
    {
        return $this->firstWrite;
    }

    /**
     * @param string $firstLogin
     */
    public function setFirstLogin($firstLogin)
    {
        $this->firstLogin = $firstLogin;
    }

    /**
     * @return string
     */
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    /**
     * @param \DateTime $firstReminder
     */
    public function setFirstReminder($firstReminder)
    {
        $this->firstReminder = $firstReminder;
    }

    /**
     * @return \DateTime
     */
    public function getFirstReminder()
    {
        return $this->firstReminder;
    }

    /**
     * @param \DateTime $secondReminder
     */
    public function setSecondReminder($secondReminder)
    {
        $this->secondReminder = $secondReminder;
    }

    /**
     * @return string
     */
    public function getSecondReminder()
    {
        return $this->secondReminder;
    }

    /**
     * @param string $thirdReminder
     */
    public function setThirdReminder($thirdReminder)
    {
        $this->thirdReminder = $thirdReminder;
    }

    /**
     * @return string
     */
    public function getThirdReminder()
    {
        return $this->thirdReminder;
    }

    /**
     * @param string $extraReminder
     */
    public function setExtraReminder($extraReminder)
    {
        $this->extraReminder = $extraReminder;
    }

    /**
     * @return string
     */
    public function getExtraReminder()
    {
        return $this->extraReminder;
    }

    /**
     * @param string $sendNotifications
     */
    public function setSendNotifications($sendNotifications)
    {
        $this->sendNotifications = $sendNotifications;
    }

    /**
     * @return string
     */
    public function getSendNotifications()
    {
        return $this->sendNotifications;
    }

    /**
     * @param string $sendStats
     */
    public function setSendStats($sendStats)
    {
        $this->sendStats = $sendStats;
    }

    /**
     * @return string
     */
    public function getSendStats()
    {
        return $this->sendStats;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $pictureFile
     */
    public function setPictureFile(File $pictureFile)
    {
        $this->pictureFile = $pictureFile;

        if ($pictureFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param boolean $isCompany
     */
    public function setIsCompany($isCompany)
    {
        $this->isCompany = $isCompany;
    }

    /**
     * @return boolean
     */
    public function isCompany()
    {
        return $this->isCompany;
    }

    /**
     * @return boolean
     */
    public function isPayingCompany()
    {
        $subscription = $this->getSubscription();
        if($this->isCompany and isset($subscription) and !empty($subscription) and $subscription->getWordUniqueness()){
            return true;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public function amountOfWordsToWrite()
    {
        $subscription = $this->getSubscription();
        if(isset($subscription) and !empty($subscription) and $subscription->getWordUniqueness() and $subscription->getWordAmount() > 0){
            return $subscription->getWordAmount() - $this->definitions->count();
        } else {
            return '';
        }
    }

    /**
     * @param boolean $telnumber
     */
    public function setTelnumber($telnumber)
    {
        $this->telnumber = $telnumber;
    }

    /**
     * @return boolean
     */
    public function getTelnumber()
    {
        return $this->telnumber;
    }

    /**
     * @param string $websiteUrl
     */
    public function setWebsiteUrl($websiteUrl)
    {
        $this->websiteUrl = $websiteUrl;
    }

    /**
     * @return string
     */
    public function getWebsiteUrl()
    {
        return $this->websiteUrl;
    }

    /**
     * @param Subscription $subscription
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param boolean $termsAccepted
     */
    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = $termsAccepted;
    }

    /**
     * @return boolean
     */
    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    /**
     * @return string
     */
    public function getContactSetting()
    {
        return $this->contactSetting;
    }

    /**
     * @param string $contactSetting
     */
    public function setContactSetting($contactSetting)
    {
        $this->contactSetting = $contactSetting;
    }

    /**
     * @return array
     */
    public static function getContactSettingOptions()
    {
        return self::$contactSettingOptions;
    }

    /**
     * @param array $contactSettingOptions
     */
    public static function setContactSettingOptions($contactSettingOptions)
    {
        self::$contactSettingOptions = $contactSettingOptions;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $headerImageFile
     */
    public function setHeaderImageFile(File $headerImageFile)
    {
        $this->headerImageFile = $headerImageFile;

        if ($headerImageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getHeaderImageFile()
    {
        return $this->headerImageFile;
    }

    /**
     * @param string $headerImage
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = $headerImage;
    }

    /**
     * @return string
     */
    public function getHeaderImage()
    {
        return $this->headerImage;
    }

}