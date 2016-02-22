<?php

namespace Ensie\PageBundle\Controller;

use Ensie\LanguageBundle\Traits\GetCurrentLanguageTrait;
use Ensie\PageBundle\Service\MenuItemService;
use Ensie\SimpleTextBundle\Entity\SimpleTextRepository;
use Intervention\Image\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PageController extends Controller
{

    use GetCurrentLanguageTrait;

    /**
     * @Template()
     */
    public function pageAction($pageSlug)
    {
        /** @var MenuItemService $menuItemService */
        $menuItemService = $this->get('page.menu_item_service');
        $allowedPages = $menuItemService->getMenuItems();
        if(!in_array($pageSlug, array_keys($allowedPages))){
            throw new NotFoundException();
        }
        return array('pageSlug' => $pageSlug, 'pages' => $allowedPages);
    }

    /**
     * @Template()
     */
    public function categoryAction($pageSlug)
    {
        return array('pageSlug' => $pageSlug);
    }

}
