<?php

namespace Firebed\AadeMyData\Models;

class ErrorType extends Type
{
    /**
     * @return string Μήνυμα Σφάλματος
     */
    public function getMessage(): string
    {
        return $this->get('message');
    }
    
    /**
     * <h2>Μήνυμα Σφάλματος</h2>
     * 
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        return $this->put('message', $message);
    }

    /**
     * @return string Κωδικός Σφάλματος
     */
    public function getCode(): string
    {
        return $this->get('code');
    }

    /**
     * <h2>Κωδικός Σφάλματος</h2>
     *
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): self
    {
        return $this->put('code', $code);
    }
}