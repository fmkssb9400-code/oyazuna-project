@extends('guide.layout')

@section('title', $page ? $page->title : '外壁調査の料金相場・費用目安を解説')
@section('description', $page ? $page->meta_description : '相場がわかりにくい外壁調査。ここで工事に必要な金額が算定できます。')

@section('content')
@parent
@endsection