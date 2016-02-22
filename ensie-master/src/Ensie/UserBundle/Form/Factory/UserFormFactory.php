<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-1-15
 * Time: 12:48
 */
namespace Ensie\UserBundle\Form\Factory;

use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Form\EditUserCompanyType;
use Ensie\UserBundle\Form\EditUserLinkedInForm;
use Ensie\UserBundle\Form\EditUserLinkedInType;
use Ensie\UserBundle\Form\EditUserType;
use Ensie\UserBundle\Form\EditUserTypeInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class UserFormFactory {

    private $formFactory;

    function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param EnsieUser $ensieUser
     * @return FormInterface
     */
    public function createUserEditForm(EnsieUser $ensieUser){
        return $this->formFactory->create($this->getTypeByUser($ensieUser->isCompany(), $ensieUser->getLinkedinId()), $ensieUser);
    }

    /**
     * @param EnsieUser $ensieUser
     * @return FormInterface
     */
    public function getUserEditTemplatePath(EnsieUser $ensieUser){
        return $this->getTypeByUser($ensieUser->isCompany(), $ensieUser->getLinkedinId())->getTemplatePath();
    }

    /**
     * @param $company
     * @param $linkedinIdUser
     * @return EditUserType
     */
    private function getTypeByUser($company, $linkedinIdUser){
        if($company){
            return new EditUserCompanyType();
        }else{
            if($linkedinIdUser)
                return new EditUserLinkedInType();
            else
                return new EditUserType();
        }
    }
} 