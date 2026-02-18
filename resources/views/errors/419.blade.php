@extends('errors.layout')

@section('title', 'Страница истекла')
@section('code', '419')
@section('message', 'Срок действия страницы истек')
@section('description', 'Для вашей безопасности сессия была завершена из-за долгого отсутствия активности. Пожалуйста, обновите страницу и попробуйте снова.')

<style>
    .btn-home {
        background-color: var(--gold);
        color: var(--chocolate);
    }
    .btn-home:hover {
        background-color: var(--chocolate);
        color: var(--white);
    }
</style>
