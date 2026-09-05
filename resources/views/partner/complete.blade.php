@extends('layouts.app')

@section('title', '提携に関するご相談を受付いたしました - オヤズナ')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">
    <div class="bg-white shadow p-6 md:p-8 text-center">
        <div class="mb-6">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">提携に関するご相談を受付いたしました</h1>
        </div>

        <div class="bg-green-50 border border-green-200 p-6 mb-6">
            <p class="text-green-800 text-lg mb-2">
                <strong>お問い合わせいただきありがとうございました！</strong>
            </p>
            <p class="text-green-700">
                内容を確認の上、担当者よりご連絡させていただきます。<br>
                今しばらくお待ちください。
            </p>
        </div>

        <div class="space-y-4 md:space-y-0 md:space-x-4 md:flex md:justify-center">
            <a href="{{ route('home') }}"
               class="inline-block bg-blue-600 text-white px-8 py-3 font-medium hover:bg-blue-700 transition-colors">
                ホームに戻る
            </a>
        </div>
    </div>
</div>
@endsection
