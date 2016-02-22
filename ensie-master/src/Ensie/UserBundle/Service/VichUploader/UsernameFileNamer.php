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
use Vich\UploaderBundle\Naming\NamerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UsernameFileNamer implements NamerInterface
{
    protected $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function name($object, PropertyMapping $mapping)
    {
        /** @var $object EnsieUser */
        $fileExtention = $mapping->getFile($object)->guessExtension();
        if($fileExtention == 'jpeg'){
            $fileExtention = 'jpg';
        }
        if($object->getSlug() != 'empty' and !$object->getSlug()){
            return $object->getSlug() . '.' . $fileExtention;
        }
        return uniqid('pi') . '.' . $fileExtention;//$object->getSlug() . '.' . $fileExtention;
    }

    /**
     * @return EnsieUser
     */
    protected function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }
} 