<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //Routing
            new \BeSimple\I18nRoutingBundle\BeSimpleI18nRoutingBundle(),
            //User authentication
            new \HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            //Admin generator
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            //Doctrine
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            //Form
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            //Image
            new Liip\ImagineBundle\LiipImagineBundle(),
            //Mandrill
            new Hip\MandrillBundle\HipMandrillBundle(),
            //Own bundles
            new Ensie\EnsieBundle\EnsieEnsieBundle(),
            new Ensie\LanguageBundle\EnsieLanguageBundle(),
            new Ensie\UserBundle\EnsieUserBundle(),
            new Ensie\NotificationBundle\EnsieNotificationBundle(),
            new Ensie\BadgesBundle\EnsieBadgesBundle(),
            new Ensie\PageBundle\EnsiePageBundle(),
            new Ensie\SimpleTextBundle\EnsieSimpleTextBundle(),
            new Ensie\AssetBundle\EnsieAssetBundle(),
            new Ensie\StatsBundle\EnsieStatsBundle(),
            new Ensie\MailerBundle\EnsieMailerBundle(),
            new Ensie\AdminBundle\EnsieAdminBundle(),
            new Ensie\MandrillMailerBundle\EnsieMandrillMailerBundle(),
            new Ensie\RedirectBundle\EnsieRedirectBundle(),
            new Ensie\CronBundle\EnsieCronBundle(),
            new Ensie\SubscriptionBundle\EnsieSubscriptionBundle(),
            //Vendor Extention
            new VendorExtend\ImagineBundle\VendorExtendImagineBundle(),
            new Ensie\WallOfFameBundle\EnsieWallOfFameBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
