

<img src="{!!$message->embedData(QrCode::format('png')->size(500)->generate('/'), 'QrCode.png', 'image/png')!!}">
