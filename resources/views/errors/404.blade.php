@extends('errors.layout')

@section('code', '404')
@section('titre', 'page introuvable')
@section('message', 'Cette page n’existe pas ou a été déplacée. Les services, les projets et le blog sont accessibles depuis l’accueil.')
@section('action')
  <a href="{{ url('/blog') }}" class="inline-flex items-center justify-center rounded-full border border-line px-6 py-3 font-semibold text-ink transition duration-200 hover:border-primary hover:text-primary">Lire le blog</a>
@endsection
