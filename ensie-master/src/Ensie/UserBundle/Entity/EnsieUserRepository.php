<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-4-14
 * Time: 21:41
 */

namespace Ensie\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ensie\EnsieBundle\Entity\Definition;
use Ensie\LanguageBundle\Entity\Language;

class EnsieUserRepository extends EntityRepository{



    public function isUsableSlug(EnsieUser $ensieUser){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ensieuser');
        $qb->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser');
        $qb->where('ensieuser.slug LIKE :userslug');
        //$qb->andWhere('ensieuser.id != :ensieUserId');
        $qb->setParameter('userslug', $ensieUser->getSlug());
        //$qb->setParameter('ensieUserId', $ensieUser->getId());
        $returnEnsieUser = $qb->getQuery()->getOneOrNullResult();

        return ($returnEnsieUser) ? true : false;
    }

    public function getUserBySlug($userSlug)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ensieuser');
        $qb->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser');
        $qb->where('ensieuser.slug = :userslug');
        $qb->andWhere('ensieuser.enabled = 1');
        $qb->andWhere('ensieuser.enabledWriter = 1');
        $qb->setParameter('userslug', $userSlug);
        return $qb->getQuery()->getOneOrNullResult();
    }

    private function getFrontQB(Language $language){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ensieuser, count(definition.id) AS HIDDEN definitionCount');
        $qb->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser');
        $qb->innerJoin('ensieuser.definitions', 'definition', 'WITH');
        $qb->innerJoin('definition.language', 'language', 'WITH', 'language = :language');
        $qb->where('ensieuser.enabled = 1');
        $qb->andWhere('ensieuser.enabledWriter = 1');
        $qb->groupBy('ensieuser.id');
        $qb->setParameter('language', $language);
        //$qb->setParameter('definitionStatus', Definition::ACTIVE_DEFINITION);
        return $qb;
    }

    public function getNewList($maxResult, Language $language)
    {
        $qb = $this->getFrontQB($language);
        $qb->setMaxResults($maxResult);
        $qb->orderBy('ensieuser.createdAt', 'DESC');
        return $qb->getQuery()->getResult();
    }

    public function getBestList($maxResult, Language $language)
    {
        $qb = $this->getFrontQB($language);
        $qb->setMaxResults($maxResult);
        $qb->addOrderBy('definitionCount', 'DESC');
        return $qb->getQuery()->getResult();
    }

    public function autoCompleteSearch($searchWord, Language $language)
    {
        $qb = $this->getFrontQB($language);
        $qb->andWhere('(ensieuser.formattedName LIKE :formattedName or ensieuser.companyName LIKE :formattedName)' );
        $qb->setParameter('formattedName', '%'. $searchWord . '%');
        $qb->orderBy('ensieuser.formattedName', 'asc');
        return $qb->getQuery()->getResult();
    }

    public function getUsersForStatsMail($limit)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ensieuser');
        $qb->addSelect('COUNT(definition.id) as HIDDEN definitionCount');
        $qb->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser');
        $qb->innerJoin('ensieuser.definitions', 'definition');
        $qb->where('(ensieuser.sendStats < :date');
        $qb->orWhere('ensieuser.sendStats IS NULL)');
        $qb->andWhere('ensieuser.receiveEmails = true');
        $qb->andWhere('ensieuser.enabledWriter = true');
        $qb->groupBy('ensieuser.id');
        $qb->having('definitionCount > 0');
        $qb->setMaxResults($limit);
        $qb->setParameter('date', new \DateTime('now'));
        return $qb->getQuery()->getResult();
    }

    public function getUsersForNotificationMail($limit)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ensieuser');
        $qb->addSelect('COUNT(definition.id) as HIDDEN definitionCount');
        $qb->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser');
        $qb->innerJoin('ensieuser.definitions', 'definition');
        $qb->where('(ensieuser.sendNotifications < :date');
        $qb->orWhere('ensieuser.sendNotifications IS NULL)');
        $qb->andWhere('ensieuser.receiveEmails = true');
        $qb->andWhere('ensieuser.enabledWriter = true');
        $qb->andWhere('ensieuser.sendStats IS NOT NULL');
        $qb->andWhere('ensieuser.sendStats > :sendStatsDate');
        $qb->groupBy('ensieuser.id');
        $qb->having('definitionCount > 0');
        $qb->setMaxResults($limit);
        $qb->setParameter('date', new \DateTime('now'));
        $datetime = new \DateTime('now');
        $sendStatsDate = $datetime->add(\DateInterval::createFromDateString('1 day'));
        $qb->setParameter('sendStatsDate', $sendStatsDate);
        return $qb->getQuery()->getResult();
    }

    //Reminder mails
    public function getFirstReminderMail($days)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ensieuser');
        $qb->addSelect('COUNT(definition.id) as HIDDEN definitionCount');
        $qb->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser');
        $qb->leftJoin('ensieuser.definitions', 'definition');
        $qb->where('ensieuser.enabled = 1');
        $qb->andWhere('ensieuser.firstReminder IS NULL');
        $qb->andWhere('ensieuser.secondReminder IS NULL');
        $qb->andWhere('ensieuser.thirdReminder IS NULL');
        $qb->andWhere('ensieuser.receiveEmails = true');
        $qb->andWhere('ensieuser.createdAt < :reminderDate');
        $qb->groupBy('ensieuser.id');
        $qb->having('definitionCount = 0');
        $date = new \DateTime('now');
        $date->modify('-' . $days . ' day');
        $qb->setParameter('reminderDate', $date);
        return $qb->getQuery()->getResult();
    }

    public function getSecondReminderMail($days)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ensieuser');
        $qb->addSelect('COUNT(definition.id) as HIDDEN definitionCount');
        $qb->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser');
        $qb->leftJoin('ensieuser.definitions', 'definition');
        $qb->where('ensieuser.enabled = 1');
        $qb->andWhere('ensieuser.firstReminder IS NOT NULL');
        $qb->andWhere('ensieuser.secondReminder IS NULL');
        $qb->andWhere('ensieuser.thirdReminder IS NULL');
        $qb->andWhere('ensieuser.receiveEmails = true');
        $qb->andWhere('ensieuser.firstReminder < :reminderDate');
        $qb->groupBy('ensieuser.id');
        $qb->having('definitionCount = 0');
        $date = new \DateTime('now');
        $date->modify('-' . $days . ' day');
        $qb->setParameter('reminderDate', $date);
        return $qb->getQuery()->getResult();
    }

    public function getThirdReminderMail($days)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ensieuser');
        $qb->addSelect('COUNT(definition.id) as HIDDEN definitionCount');
        $qb->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser');
        $qb->leftJoin('ensieuser.definitions', 'definition');
        $qb->where('ensieuser.enabled = 1');
        $qb->andWhere('ensieuser.firstReminder IS NOT NULL');
        $qb->andWhere('ensieuser.secondReminder IS NOT NULL');
        $qb->andWhere('ensieuser.thirdReminder IS NULL');
        $qb->andWhere('ensieuser.receiveEmails = true');
        $qb->andWhere('ensieuser.secondReminder < :reminderDate');
        $qb->groupBy('ensieuser.id');
        $qb->having('definitionCount = 0');
        $date = new \DateTime('now');
        $date->modify('-' . $days . ' day');
        $qb->setParameter('reminderDate', $date);
        return $qb->getQuery()->getResult();
    }

    public function getExtraReminderMail($days)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ensieuser');
        $qb->addSelect('COUNT(definition.id) as HIDDEN definitionCount, definition.createdAt as HIDDEN definitionCreate');
        $qb->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser');
        $qb->leftJoin('ensieuser.definitions', 'definition');
        $qb->where('ensieuser.enabled = 1');
        $qb->andWhere('ensieuser.extraReminder IS NULL');
	    $qb->andWhere('ensieuser.receiveEmails = true');
        $qb->groupBy('ensieuser.id');
        $qb->having('definitionCount = 1 OR definitionCount = 2');
        $qb->andHaving('definitionCreate < :reminderDate');
        $date = new \DateTime('now');
        $date->modify('-' . $days . ' day');
        $qb->setParameter('reminderDate', $date);
        return $qb->getQuery()->getResult();
    }


}
