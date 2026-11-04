# Πληροφορίες λήπτη - RequestReceiverInfo

```shell
# production
https://mydatapi.aade.gr/myDataProvider/RequestReceiverInfo?vatNumber={vatNumber}

# development
https://mydataapidev.aade.gr/myDATAProvider/RequestReceiverInfo?vatNumber={vatNumber}
```

> [!WARNING]
> Μόνο για παρόχους ηλεκτρονικής τιμολόγησης.

Για μια οντότητα, επιστρέφει τους ΑΦΜ των παρόχων μέσω των οποίων τιμολογεί και τα email στα οποία έχει δηλώσει ότι
λαμβάνει παραστατικά από την ηλεκτρονική τιμολόγηση.

```php
$info = $mydata->requestReceiverInfo('123456789');

$info->getReceptionProviders()?->vatNumbers();  // ['800000001', ...]
$info->getReceptionEmails()?->all();            // ['invoices@example.com', ...]
```

Τα ίδια στοιχεία επιστρέφονται και σε κάθε επιτυχημένη απάντηση της `SendInvoices` μέσω παρόχου:
`$response->getReceptionProviders()`, `$response->getReceptionEmails()`.
