@extends('layouts.app')

@section('title', '見積もり依頼完了 - オヤズナ')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="text-center">
        <div class="mb-8">
            <div class="mx-auto bg-green-100 rounded-full p-6 w-24 h-24 flex items-center justify-center">
                <svg class="w-12 h-12 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 mb-4">見積もり依頼を送信しました</h1>
        
        @if($quoteRequest)
            <p class="text-lg text-gray-600 mb-8">
                案件番号：<strong class="text-blue-600">{{ $quoteRequest->public_id }}</strong>
            </p>
        @endif
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-blue-800 mb-4">今後の流れ</h2>
            <div class="text-left max-w-md mx-auto space-y-3">
                <div class="flex items-center">
                    <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3">1</div>
                    <div>各業者へ見積もり依頼を送信</div>
                </div>
                <div class="flex items-center">
                    <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3">2</div>
                    <div>業者からお客様へ直接連絡</div>
                </div>
                <div class="flex items-center">
                    <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3">3</div>
                    <div>詳細打ち合わせ・見積もり提示</div>
                </div>
                <div class="flex items-center">
                    <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3">4</div>
                    <div>業者選定・契約</div>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
            <h3 class="font-semibold text-yellow-800 mb-2">重要事項</h3>
            <div class="text-sm text-yellow-700 text-left max-w-2xl mx-auto">
                <ul class="list-disc list-inside space-y-1">
                    <li>高所作業の安全性・保険・資格については各業者へ直接ご確認ください</li>
                    <li>契約はお客様と業者の間で直接行われます</li>
                    <li>作業内容・料金・工期は業者により異なります</li>
                </ul>
            </div>
        </div>
        
        <a href="{{ url('/') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
            トップページに戻る
        </a>
    </div>
</div>
@endsection