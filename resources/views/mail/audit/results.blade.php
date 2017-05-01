@component('mail::message')
# 尊敬的{{$name}}

@component('mail::panel')
{{ $message }}
@endcomponent

@component('mail::table')
| 申请机构       | 申请类型       | 负责人  | 联系方式 |
|:-------------:|:-------------:|:-------------:|:-------------:|
| {{$applicat->mechanism->name}}      | {{$applicat->type->name}}      | {{$applicat->principal}}      | U{{$applicat->mobile}} |
@endcomponent

@component('mail::table')
| 参与人数       | 申请人       |
|:-------------:|:-------------:|
| {{$applicat->number}} | {{$applicat->user->name}} |
@endcomponent

@if (isset($applicat->goods))
  @component('mail::table')
  | 物资申请 |
  |:-------------:|
  | {{$applicat->reason}} |
  @endcomponent
@endif

@component('mail::table')
| 申请缘由 |
|:-------------:|
| {{$applicat->reason}} |
@endcomponent

@component('mail::table')
| 申请具体时间 |
|:-------------:|
| {{$applicat->startTime}} ~ {{$applicat->endTime}} |
@endcomponent

@if(isset($opinions))
@foreach ($opinions as $opinion)
@component('mail::table')
| {{$opinion->user->name.'的意见'}} |
|:-------------:|
| {{$opinion->opinion}} |
@endcomponent
@endforeach
@endif

@component('mail::button', ['url' => $url, 'color' => 'green'])
查看
@endcomponent


Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
如果您无法点击 "{{ $actionText }}" 按钮, 复制并粘贴以下URL
进入您的网络浏览器: [{{ $url }}]({{ $url }})
@endcomponent

@endcomponent
