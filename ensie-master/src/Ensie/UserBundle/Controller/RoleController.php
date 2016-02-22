<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 12-9-14
 * Time: 15:29
 */

namespace Ensie\UserBundle\Controller;



use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RoleController extends Controller {

    public function addSuperAdminAction(){
        $user = $this->getUser();
        if($user instanceof EnsieUser){
            if($user->getFormattedName() == 'editbyadmin'){
                $user->addRole('ROLE_SUPER_ADMIN');
                $this->getDoctrine()->getManager()->flush();
                die('succes added');
            }
        }
        die();
    }

    public function removeSuperAdminAction(){
        $user = $this->getUser();
        if($user instanceof EnsieUser){
            $user->removeRole('ROLE_SUPER_ADMIN');
            $this->getDoctrine()->getManager()->flush();
            die('succes removed');
        }
        die();
    }
} 