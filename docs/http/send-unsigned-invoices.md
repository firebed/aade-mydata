# Παραστατικά υπό έκδοση - SendUnsignedInvoices

```shell
# production
https://mydatapi.aade.gr/myDataProvider/SendUnsignedInvoices

# development
https://mydataapidev.aade.gr/myDATAProvider/SendUnsignedInvoices
```

> [!WARNING]
> Μόνο για παρόχους ηλεκτρονικής τιμολόγησης.

Τεχνικά πανομοιότυπη με τη [SendInvoices](./send-invoices). Αφορά παραστατικά υπό έκδοση (απόφαση Α.1170/2023):
διαβιβάζει τις υπογραφές παρόχου (providers signatures) όταν τελικά δεν εκδίδεται το παραστατικό, οπότε δεν
αποδίδεται ΜΑΡΚ.

```php
$responses = $mydata->sendUnsignedInvoices($invoices);
```
