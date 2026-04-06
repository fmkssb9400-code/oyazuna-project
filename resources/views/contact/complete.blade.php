@extends('layouts.app')

@section('title', 'お問い合わせ完了 - オヤズナ')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">
    <div class="bg-white rounded-lg shadow p-6 md:p-8 text-center">
        <div class="mb-6">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">お問い合わせを受付いたしました</h1>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
            <p class="text-green-800 text-lg mb-2">
                <strong>ありがとうございました！</strong>
            </p>
            <p class="text-green-700">
                お問い合わせ内容を確認させていただき、営業日内に2-3日でご返信いたします。<br>
                今しばらくお待ちください。
            </p>
        </div>
        
        <div class="text-sm text-gray-600 mb-8">
            <p>※万が一、返信がない場合は、迷惑メールフォルダをご確認いただくか、</p>
            <p>再度お問い合わせいただければと思います。</p>
        </div>
        
        <div class="space-y-4 md:space-y-0 md:space-x-4 md:flex md:justify-center">
            <a href="{{ route('home') }}" 
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                ホームに戻る
            </a>
            <a href="{{ route('companies.index') }}" 
               class="inline-block bg-gray-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                業者一覧を見る
            </a>
        </div>
    </div>
</div>
@endsection