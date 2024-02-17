<?php

namespace Firebed\AadeMyData\Models;

class Error extends Type
{
    /**
     * @return string|null Μήνυμα Σφάλματος
     */
    public function getMessage(): ?string
    {
        return $this->get('message');
    }

    /**
     * @param string $message Μήνυμα Σφάλματος
     */
    public function setMessage(string $message): void
    {
        $this->set('message', $message);
    }

    /**
     * @return string|null Κωδικός Σφάλματος
     */
    public function getCode(): ?string
    {
        return $this->get('code');
    }

    /**
     * @param string $code Κωδικός Σφάλματος
     */
    public function setCode(string $code): void
    {
        $this->set('code', $code);
    }
    
}