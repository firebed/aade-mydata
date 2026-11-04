# Error

`Error` is one error of a [Response](response-type). `Response::getErrors()` returns an `Errors` collection of them,
or `null` when there are none.

```php
foreach ($response->getErrors() ?? [] as $error) {
    echo $error->getCode();    // e.g. "228"
    echo $error->getMessage();
}

$first = $response->getErrors()?->first();
```

| Field   | Getter         | Type     | Description   |
|---------|----------------|----------|---------------|
| code    | `getCode()`    | `string` | Error code    |
| message | `getMessage()` | `string` | Error message |

`Errors` is a list: you can `foreach` it, `count()` it, and call `all()`, `first()`, `last()`, `isEmpty()`, `map()`
and `filter()`.

> [!NOTE]
> These are errors myDATA reports for one entity. Connection and HTTP failures throw exceptions instead,
> see [Exceptions](../exceptions).
