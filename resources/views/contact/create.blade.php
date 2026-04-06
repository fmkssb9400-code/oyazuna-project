@extends('layouts.app')

@section('title', 'お問い合わせ - オヤズナ')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">
    <h1 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8">お問い合わせ</h1>
    
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">お問い合わせについて</h3>
        <p class="text-blue-700 text-sm">
            サイトに関するご質問、ご意見、その他のお問い合わせは下記フォームよりお送りください。<br>
            通常、営業日内に2-3日でご返信いたします。
        </p>
    </div>
    
    <form action="{{ route('contact.store') }}" method="POST" class="bg-white rounded-lg shadow p-4 md:p-8 space-y-6">
        @csrf
        
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded p-4">
                <ul class="list-disc list-inside text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">お名前 <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="例：田中太郎">
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">メールアドレス <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="example@example.com">
            </div>
        </div>
        
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">電話番号</label>
            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="例：03-1234-5678">
        </div>
        
        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">件名 <span class="text-red-500">*</span></label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="例：サイトについての質問">
        </div>
        
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">お問い合わせ内容 <span class="text-red-500">*</span></label>
            <textarea name="message" id="message" rows="6" required
                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="お問い合わせ内容を詳しくお書きください">{{ old('message') }}</textarea>
        </div>
        
        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-12 py-4 rounded-lg font-bold text-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                お問い合わせを送信
            </button>
        </div>
        
        <div class="text-sm text-gray-600 text-center">
            <p>※送信いただいた内容は、お問い合わせへの回答以外には使用いたしません。</p>
        </div>
    </form>
</div>
@endsection