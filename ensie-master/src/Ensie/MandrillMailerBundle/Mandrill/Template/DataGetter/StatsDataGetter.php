<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 9-11-14
 * Time: 11:00
 */

namespace Ensie\MandrillMailerBundle\Mandrill\Template\DataGetter;


use Ensie\StatsBundle\Service\EmailStatsService;
use Ensie\UserBundle\Entity\EnsieUser;


class StatsDataGetter extends  AbstractDataGetter{

    /** @var  EmailStatsService */
    private $emailStatsService;

    /** @var  EnsieUser */
    protected $ensieUser;

    function __construct(EmailStatsService $emailStatsService)
    {
        $this->emailStatsService = $emailStatsService;
    }

    public function setUser(EnsieUser $ensieUser){
        $this->ensieUser = $ensieUser;
    }

    public function gatherData()
    {
        $statsData = $this->emailStatsService->getStats($this->ensieUser);
        $this->containsData = $this->hasData($statsData);
        return array_merge($statsData, array('FORMATTEDNAME' => $this->ensieUser->getFormattedName()));
    }

    public function validate()
    {
        if(!isset($this->ensieUser) or empty($this->ensieUser)){
            throw new \Exception('User is not set. Use the setter ');
        }
    }

    private function hasData($statsData){
        if(is_array($statsData)){
            foreach($statsData as $value){
                if(!empty($value)){
                    return true;
                }
            }
        } elseif(isset($this->data) and !empty($this->data)){
            return true;
        }
        return false;
    }
} 