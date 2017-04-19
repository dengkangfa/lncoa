@component('mail::message', ['img' => $img])
# Introduction

The body of your message.
{{ $img }}
{{ $name }}
<img src="$img">

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
