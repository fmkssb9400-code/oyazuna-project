@extends('layouts.app')

@section('title', '提携に関するご相談 - オヤズナ | 高所ロープ作業の見積もり・相場データベース【高所の窓ガラス清掃・外壁塗装・外壁補修など】')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">
    <h1 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8">提携に関するご相談</h1>

    <div class="bg-blue-50 border border-blue-200 p-4 mb-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">高所ロープ作業・清掃等のサービスを提供されている企業様へ</h3>
        <p class="text-blue-700 text-sm">
            オヤズナへの掲載・提携にご興味をお持ちの企業様は、下記フォームよりご相談ください。<br>
            担当者より折り返しご連絡いたします。
        </p>
    </div>

    <form action="{{ route('partner.store') }}" method="POST" class="bg-white shadow p-4 md:p-8 space-y-6">
        @csrf

        {{-- ハニーポット：人間には見えない位置に配置し、ボットが埋めた場合は送信を無視する --}}
        <div style="position:absolute; left:-9999px; top:-9999px;" aria-hidden="true">
            <label for="website">Website</label>
            <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 p-4">
                <ul class="list-disc list-inside text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">会社名 <span class="text-red-500">*</span></label>
                <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required
                       class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="例：株式会社オヤズナ">
            </div>

            <div>
                <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">ご担当者名 <span class="text-red-500">*</span></label>
                <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}" required
                       class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="例：田中太郎">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">メールアドレス <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="example@example.com">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">電話番号 <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                       class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="例：03-1234-5678">
            </div>
        </div>

        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">ご相談内容 <span class="text-red-500">*</span></label>
            <textarea name="message" id="message" rows="6" required
                      class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="対応可能なサービス内容、対応エリア、その他ご相談内容をお書きください">{{ old('message') }}</textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-12 py-4 font-bold text-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                提携相談を送信
            </button>
        </div>

        <div class="text-sm text-gray-600 text-center">
            <p>※送信いただいた内容は、提携に関するご連絡以外には使用いたしません。</p>
        </div>
    </form>
</div>
@endsection
