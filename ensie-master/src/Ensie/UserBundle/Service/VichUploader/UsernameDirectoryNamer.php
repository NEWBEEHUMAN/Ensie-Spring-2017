<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 2-9-14
 * Time: 21:51
 */

namespace Ensie\UserBundle\Service\VichUploader;


use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class UsernameDirectoryNamer implements DirectoryNamerInterface
{
    protected $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function directoryName($object, PropertyMapping $mapping)
    {
        return $this->getUser()->getSlug();
    }

    /**
     * @return EnsieUser
     */
    protected function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }
} 