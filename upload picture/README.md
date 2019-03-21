# Upload Picture

save uploaded picture and create thumbnails files

## usage
```php
$upload = new uploadPicture(
    $_FILES['file'],
    '/origin/',
    '/thumb/'
);

if ($upload->saveOrigin())
{
    $upload->createThumb();
}
```
