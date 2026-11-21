@extends('errors.layout')

@section('code', '419')
@section('titre', 'page expirée')
@section('message', 'La page est restée ouverte trop longtemps. Rechargez-la, puis renvoyez votre message.')
@section('action')
  <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center rounded-full border border-line px-6 py-3 font-semibold text-ink transition duration-200 hover:border-primary hover:text-primary">Revenir à la page</a>
@endsection
