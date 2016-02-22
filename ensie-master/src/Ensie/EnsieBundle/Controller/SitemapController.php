<?php

namespace Ensie\EnsieBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\EnsieBundle\Entity\Ensie;
use Ensie\EnsieBundle\Entity\Keyword;
use Ensie\EnsieBundle\Entity\View;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\LanguageBundle\Entity\LanguageRepository;
use Ensie\LanguageBundle\Traits\GetCurrentLanguageTrait;
use Ensie\PageBundle\Service\MenuItemService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\EnsieUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SitemapController extends Controller
{

    use GetCurrentLanguageTrait;
    /**
     * @Template("EnsieEnsieBundle:Sitemap:sitemap.xml.twig")
     */
    public function generateAction(Request $request)
    {
        $urls = array();
        $hostname = 'https://' . $request->getHost();
        // add some urls homepage
        $urls[] = array('loc' => $this->get('router')->generate('home'), 'changefreq' => 'weekly', 'priority' => '1.0');

        // service
        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->get('ensie.ensie_definition_repository');
        $definitions = $definitionRepository->getList(10000000000, $this->getCurrentLanguage());
        /** @var Definition $definition */
        foreach ($definitions as $definition) {
            $url = $this->get('router')->generate('definition_definition', array('userSlug' => $definition->getEnsieUser()->getSlug(), 'definitionSlug' => $definition->getSlug()));
            $urls[] = array('loc' =>
                $url,
                'priority' => '0.5'
            );
            $url = $this->get('router')->generate('definition_search_word', array('slug' => $definition->getSlug()));
            $urls[] = array('loc' =>
                $url,
                'priority' => '0.5'
            );
        }

        /** @var EnsieUserRepository $ensieUserRepository */
        $ensieUserRepository = $this->get('ensie.user_ensie_user_repository');
        $users = $ensieUserRepository->getNewList(10000000000, $this->getCurrentLanguage());
        /** @var EnsieUser $user */
        foreach ($users as $user) {
            $url = $this->get('router')->generate('user_detail', array('userSlug' => $user->getSlug()));
            $urls[] = array('loc' =>
                $url,
                'priority' => '0.7'
            );
        }

        /** @var MenuItemService $menuItemService */
        $menuItemService = $this->get('page.menu_item_service');
        $allowedPages = $menuItemService->getMenuItems();
        /** @var EnsieUser $user */
        foreach ($allowedPages as $pages) {
            $url = $this->get('router')->generate('page_content', array('pageSlug' => $pages));
            $urls[] = array('loc' =>
                $url,
                'priority' => '0.6'
            );
        }

        return array('urls' => $urls, 'hostname' => $hostname);
    }
}
