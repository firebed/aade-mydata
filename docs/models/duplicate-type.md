# Duplicate

When you send an invoice whose uid myDATA already holds, myDATA rejects it with error 228
("It has already been sent for another invoice (MARK: ..., AUTHENTICATION_CODE: ...)").
`Response::getDuplicate()` reads that error and returns a `Duplicate`. For any other response it returns `null`.

Use it to recover the MARK after a resend, e.g. when the first answer was lost.

```php
foreach ($responses as $response) {
    $duplicate = $response->getDuplicate();

    if ($duplicate === null) {
        continue;
    }

    $duplicate->mark;               // int
    $duplicate->uid;                // string
    $duplicate->authenticationCode; // ?string

    // treat it as the successful response of the first send
    $success = $duplicate->toResponse($response->getIndex());
}
```

| Property / method      | Type       | Description |
|------------------------|------------|-------------|
| `uid`                  | `string`   | Uid of the invoice |
| `mark`                 | `int`      | MARK of the invoice myDATA holds |
| `authenticationCode`   | `?string`  | Authentication code. `null` until myDATA has stored the invoice |
| `isStored()`           | `bool`     | `true` when `authenticationCode` is known |
| `toResponse(?int $index)` | [Response](response-type) | The successful response for that invoice, without `qrUrl` |

> [!NOTE]
> myDATA stores submissions from a queue. Until it has stored the invoice, which can take hours, error 228 has the
> MARK but no authentication code.
