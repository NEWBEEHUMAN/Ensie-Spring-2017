<?php

namespace Ensie\EnsieBundle\Controller;

use Ensie\EnsieBundle\Entity\CategoryRepository;
use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\EnsieBundle\Form\DefinitionType;
use Ensie\LanguageBundle\Traits\GetCurrentLanguageTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CategoryController extends Controller
{
    use GetCurrentLanguageTrait;

    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @internal param Request $request
     * @internal param string $slug
     * @Template()
     */
    public function categoryOverviewAction(Request $request)
    {
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository('EnsieEnsieBundle:Category');
        return array('categoryList' => $categoryRepository->getAll($this->getCurrentLanguage()));
    }

    /**
     * @param Request $request
     * @param string $slug
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template()
     */
    public function categoryAction(Request $request, $slug = '')
    {
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository('EnsieEnsieBundle:Category');
        $category = $categoryRepository->getCategoryBySlug($slug);
        if(array_key_exists('0', $category)) {

        }
        $subcategories = $category[0]->getSubcategories();
        return array('category' => $category[0], 'subcategories' => $subcategories);
    }

    /**
     * @param Request $request
     * @param string $slug
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template()
     */
    public function subcategoryAction(Request $request, $slug = '')
    {
        /** @var SubcategoryRepository $subcategoryRepository */
        $subcategoryRepository = $this->getDoctrine()->getRepository('EnsieEnsieBundle:Subcategory');
        $subcategory = $subcategoryRepository->getSubcategoryBySlug($slug);
        return array('subcategory' => $subcategory[0]);
    }

}
