<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    /**
     * Retourne toutes les conversations où l'utilisateur est impliqué.
     *
     * @param User $user
     * @return Conversation[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.user1 = :user')
            ->orWhere('c.user2 = :user')
            ->setParameter('user', $user)
            ->orderBy('c.lastMessageAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne une conversation entre deux utilisateurs, s’il en existe une.
     *
     * @param User $userA
     * @param User $userB
     * @return Conversation|null
     */
    public function findOneByUsers(User $userA, User $userB): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->where('(c.user1 = :userA AND c.user2 = :userB) OR (c.user1 = :userB AND c.user2 = :userA)')
            ->setParameter('userA', $userA)
            ->setParameter('userB', $userB)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findRecentWithLastMessage(User $user): array
{
    return $this->createQueryBuilder('c')
        ->leftJoin('c.messages', 'm')
        ->addSelect('m')
        ->where('c.user1 = :user OR c.user2 = :user')
        ->setParameter('user', $user)
        ->orderBy('c.lastMessageAt', 'DESC')
        ->getQuery()
        ->getResult();
}
}
