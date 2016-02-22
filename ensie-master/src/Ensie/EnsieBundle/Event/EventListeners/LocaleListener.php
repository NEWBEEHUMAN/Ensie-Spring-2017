<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 25-5-14
 * Time: 16:57
 */

namespace Ensie\EnsieBundle\Event\EventListeners;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener  implements EventSubscriberInterface {

    private $defaultLocale;

    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
//        if (!$request->hasPreviousSession()) {
//            return;
//        }

        // get domain name
        $host = $request->getHttpHost();
        // or $host = $request->getHost();
        $locale = 'nl';
        if (strstr($host, 'ensie.com') !== false) {
            $locale = 'en';
        }
        if (strstr($host, 'ensie.nl') !== false) {
            $locale = 'nl';
        }
        $request->getSession()->set('_locale', $locale);
        $request->setLocale($locale);

//        // try to see if the locale has been set as a _locale routing parameter
//        if ($locale = $request->attributes->get('_locale')) {
//            $request->getSession()->set('_locale', $locale);
//        } else {
//            // if no explicit locale has been set on this request, use one from the session
//            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
//        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
} 