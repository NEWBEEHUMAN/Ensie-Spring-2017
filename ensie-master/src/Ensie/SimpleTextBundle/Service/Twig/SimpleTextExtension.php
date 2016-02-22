<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 16-6-14
 * Time: 12:15
 */

namespace Ensie\SimpleTextBundle\Service\Twig;

use Doctrine\Common\Cache\CacheProvider;
use Ensie\SimpleTextBundle\Entity\SimpleText;
use Ensie\SimpleTextBundle\Entity\SimpleTextRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class SimpleTextExtension
 * @package Ensie\SimpleTextBundle\Service\Twig
 */
class SimpleTextExtension extends \Twig_Extension
{

    const CREATE_TYPE = 'create';
    const EDIT_TYPE = 'edit';
    const CREATE_TYPE_CLASS = 'glyphicon glyphicon-plus-sign';
    const EDIT_TYPE_CLASS = 'glyphicon glyphicon-edit';

    /**
     * @var array
     */
    private $typeClass = array(
        self::CREATE_TYPE => self::CREATE_TYPE_CLASS,
        self::EDIT_TYPE => self::EDIT_TYPE_CLASS,
    );

    /**
     * @var string
     */
    protected $baseRouteName = 'sonata_simpletext';

    /** @var SimpleTextRepository  */
    private $simpleTextRepository;

    /** @var RouterInterface */
    private $router;

    /** @var SecurityContext  */
    private $securityContext;

    /** @var  \Twig_Environment */
    private $environment;

    /** @var CacheProvider  */
    private $cache;

    /** @var string */
    private $locale;

    /**
     * @param CacheProvider $cache
     * @param SimpleTextRepository $simpleTextRepository
     * @param RouterInterface $router
     * @param SecurityContext $securityContext
     */
    function __construct(Session $session, CacheProvider $cache, SimpleTextRepository $simpleTextRepository, RouterInterface $router, SecurityContext $securityContext)
    {
        $this->locale = $session->get('_locale');
        $this->session = $session;
        $this->cache = $cache;
        $this->simpleTextRepository = $simpleTextRepository;
        $this->router = $router;
        $this->securityContext = $securityContext;
    }

    /**
     * @param \Twig_Environment $environment
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('simpleText', array($this, 'getSimpleText')),
            new \Twig_SimpleFunction('simpleTextSonataAdminLink', array($this, 'getSimpleTextSonataAdminLink')),
        );
    }

    /**
     * @param $identifier
     * @param array $translationPlaceHolders
     * @param null $locale
     * @return mixed
     */
    public function getSimpleText($identifier, $translationPlaceHolders = array(), $locale = null)
    {
        if($simpleText = $this->cache->fetch($identifier)){
        } else {
            $this->cache->save($identifier, $identifier);
            $temp = $this->simpleTextRepository->getAllKyByIdentifier();
            foreach($temp as $key => $value){
                $this->cache->save($key,$value);
            }
            return $this->getSimpleText($identifier, $translationPlaceHolders, $locale);
        }
        $result = $identifier;
        if(is_object($simpleText)){
            /** @var SimpleText $simpleText */
            if ($locale == null) {
                    $result = $simpleText->translate($this->locale)->getSimpleText();
            } else {
                    $result = $simpleText->translate($locale)->getSimpleText();
            }
        }
        return $this->replacePlaceHolders($result, $translationPlaceHolders);
    }

    /**
     * @param $simpleText
     * @param $translationPlaceHolders
     * @return mixed
     */
    private function replacePlaceHolders($simpleText, $translationPlaceHolders){
        foreach($translationPlaceHolders as $key => $value){
            $simpleText = str_replace('%'.$key.'%', $value, $simpleText);
        }
        return $simpleText;
    }

    /**
     * @param $identifier
     * @param bool $useEditor
     * @return string
     */
    public function getSimpleTextSonataAdminLink($identifier, $useEditor = false)
    {
        $token = $this->securityContext->getToken();
        if($token and $this->securityContext->isGranted('ROLE_SUPER_ADMIN')){
            $simpleText = $this->cache->fetch($identifier);
            if (is_object($simpleText)) {
                $url = $this->router->generate('admin_ensie_simpletext_simpletext_edit', array('id' => $simpleText->getId()));
                $type = self::EDIT_TYPE;
            }else{
                $url = $this->router->generate('admin_ensie_simpletext_simpletext_create', array('identifier' => $identifier));
                $type = self::CREATE_TYPE;
            }
            return $this->environment->render('EnsieSimpleTextBundle:SimpleText:simpletext.html.twig',
                array(
                    'url' => $url,
                    'identifier' => $identifier,
                    'type' => $type,
                    'class' => $this->typeClass[$type],
                    'useEditor' => $useEditor
                ));
        }
        return '';
    }

    /**
     * @return array
     */
    public function getGlobals()
    {
        return array(
            'translationPlaceHolders' => array(),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'simpletext_extension';
    }
}