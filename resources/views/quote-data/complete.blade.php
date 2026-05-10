@extends('layouts.app')

@section('title', '見積もりデータ投稿完了 - オヤズナ | 高所ロープ作業の見積もり・相場データベース')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8 md:py-12">
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8 text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">投稿完了しました</h1>
            <p class="text-gray-600 mb-6">
                見積もりデータの投稿ありがとうございました。<br>
                皆様からいただいたデータは、見積もり相場データベースとして活用させていただきます。
            </p>
        </div>
        
        <div class="space-y-4">
            <a href="{{ route('home') }}" 
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                トップページに戻る
            </a>
            <br>
            <a href="{{ route('quote-data.create') }}" 
               class="inline-block border border-blue-600 text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                他の見積もりデータも投稿する
            </a>
        </div>
    </div>
</div>
@endsection