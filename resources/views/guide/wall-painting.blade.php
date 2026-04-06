@extends('guide.layout')

@section('title', $page ? $page->title : '外壁塗装の料金相場・費用目安を解説')
@section('description', $page ? $page->meta_description : '外壁塗装の相場がわかる。面積や塗料種類別の適正価格を算定できます。')

@section('content')
@parent
@endsection