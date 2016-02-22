<?php

namespace VendorExtend\ImagineBundle\Controller;

use Liip\ImagineBundle\Controller\ImagineController;
use Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException;
use Liip\ImagineBundle\Imagine\Filter\FilterConfiguration;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\RuntimeException;

class ImagineExtendedController extends ImagineController
{
    /** @var  FilterConfiguration */
    protected $filterConfiguration;

    public function setFilterConfiguration(FilterConfiguration $filterConfiguration)
    {
        $this->filterConfiguration = $filterConfiguration;
    }

    /**
     * This action applies a given filter to a given image, optionally saves the image and outputs it to the browser at the same time.
     *
     * @param Request $request
     * @param string $path
     * @param string $filter
     *
     * @throws \RuntimeException
     * @throws BadRequestHttpException
     *
     * @return RedirectResponse
     */
    public function filterAction(Request $request, $path, $filter)
    {
        try {
            if (!$this->cacheManager->isStored($path, $filter)) {
                ini_set('memory_limit', '512M');
                try {
                    $binary = $this->dataManager->find($filter, $path);
                } catch (NotLoadableException $e) {

                    throw new NotFoundHttpException('Source image could not be found', $e);
                }
                //We resize all images
                $filters = $this->filterConfiguration->all();
                foreach($filters as $key => $singleFilter){
                    if (!$this->cacheManager->isStored($path, $key)){
                        $this->cacheManager->store(
                            $this->filterManager->applyFilter($binary, $key),
                            $path,
                            $key
                        );
                     }
                }
            }

            return new RedirectResponse($this->cacheManager->resolve($path, $filter), 301);
        } catch (RuntimeException $e) {
            throw new \RuntimeException(sprintf('Unable to create image for path "%s" and filter "%s". Message was "%s"', $path, $filter, $e->getMessage()), 0, $e);
        }
    }
}
