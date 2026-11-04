<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

/**
 * The myDATA channel a request belongs to: the ERP one (a company with its own credentials)
 * or the e-invoicing provider one (myDataProvider).
 */
enum Channel: string
{
    case Erp = 'ERP';
    case Provider = 'Provider';
}
