# upload Picture

save uploaded picture and create thumbnails files

## usage
```
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