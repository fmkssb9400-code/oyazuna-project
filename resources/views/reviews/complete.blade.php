@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto py-8 px-4">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Success Icon -->
            <div class="mb-8">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">口コミの投稿が完了しました！</h1>
            <p class="text-lg text-gray-600 mb-8">
                {{ $company->name }}への口コミをありがとうございました。<br>
                投稿いただいた内容は確認後に公開されます。
            </p>

            <!-- Company Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $company->name }}</h3>
                        <p class="text-gray-600">口コミ投稿先</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('companies.show', $company->slug) }}" 
                       class="inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        {{ $company->name }}の詳細を見る
                    </a>
                    <a href="{{ route('companies.index') }}" 
                       class="inline-flex justify-center items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        業者一覧に戻る
                    </a>
                </div>
                <div>
                    <a href="{{ route('reviews.select-company') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        他の業者の口コミも書く
                    </a>
                </div>
            </div>

            <!-- Notice -->
            <div class="mt-12 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">口コミ公開について</p>
                        <p>投稿いただいた口コミは、適切な内容か確認させていただいた後、1-2営業日以内に公開いたします。不適切な内容が含まれる場合は公開されない場合があります。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection