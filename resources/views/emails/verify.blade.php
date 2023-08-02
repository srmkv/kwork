@component('mail::message')
    # Email Verification

    Спасибо, что указали свой почтовый адрес
   
    подтверждение:

    {{$url}}/api/email/pin/verify?pin={{$pin}}



    Thanks
    {{ config('app.name') }}
@endcomponent
