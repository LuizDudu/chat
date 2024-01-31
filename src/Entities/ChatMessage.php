<?php

namespace Luizdudu\Chat\Entities;

use DateTimeImmutable;

readonly class ChatMessage
{
    public string $dateTime;

    public function __construct(
        public string $nickname,
        public string $message,
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
