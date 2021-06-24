<?php

declare(strict_types=1);

namespace Ikeraslt\CommissionCalculator\User;

class UserStorage
{
    private array $users = [];

    public function getUser(int $userId, string $type): User
    {
        if (!$this->exists($userId)) {
            $this->addUser($userId, $type);
        }

        return $this->users[$userId];
    }

    private function exists(int $userId): bool
    {
        return isset($this->users[$userId]);
    }

    private function addUser(int $userId, string $type)
    {
        $user = new User();
        $user->setId($userId);
        $user->setType($type);

        $this->users[$userId] = $user;
    }
}
