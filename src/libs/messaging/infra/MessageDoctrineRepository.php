<?php

namespace App\libs\messaging\infra;

use App\libs\messaging\application\MessageRepository;
use App\libs\messaging\domain\entity\Message;
use Doctrine\ORM\EntityRepository;

class MessageDoctrineRepository extends EntityRepository implements MessageRepository
{

    public function save(Message $message): void
    {
        $this->_em->persist($message);
        $this->_em->flush();
    }

    public function getAllOfUser(string $author): array
    {
        return $this->createQueryBuilder('m')
            ->select('m')
            ->where('author = :author')
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult();
    }

    public function getById(string $id): Message|null
    {
        return $this->find($id);
    }
}