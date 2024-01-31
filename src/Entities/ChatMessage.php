<?php

namespace Luizdudu\Chat\Entities;

use DateTimeImmutable;

readonly class ChatMessage
{
    private string $dateTime;

    public function __construct(
        private string $nickname,
        private string $message,
    ) {
        $this->dateTime = (new DateTimeImmutable())->format('U');
    }

    public function toArray(): array
    {
        return [
            'nickname' => $this->nickname,
            'message' => $this->message,
            'date_time' => $this->dateTime,
        ];
    }
}
