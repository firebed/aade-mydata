<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

class Error extends Model
{
    /**
     * @return string|null Μήνυμα Σφάλματος
     */
    public function getMessage(): ?string
    {
        return $this->get('message');
    }

    /**
     * @return string|null Κωδικός Σφάλματος
     */
    public function getCode(): ?string
    {
        return $this->get('code');
    }
}
