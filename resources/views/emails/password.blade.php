@extends('emails.master')

@section('body')
    Para redefinir sua senha, acesse o link: <a href="{{ $link = 'http://meupoliticoapp.com/' . url('#/reset-password/' . $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a><Br />
@stop