@extends('layouts.app')

@section('title', '高所ロープ専門業者に現調依頼する - オヤズナ | 高所ロープ作業の見積もり・相場データベース【高所の窓ガラス清掃・外壁塗装・外壁補修など】')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6 md:py-8">
    <h1 class="text-2xl md:text-3xl font-bold mb-2">高所ロープ専門業者に現調依頼する</h1>
    <p class="text-sm text-gray-600 mb-6 md:mb-8">まずは基本情報だけご入力ください。作業の詳細は、業者からのご連絡時に個別に確認させていただきます。</p>

    <!-- Wishlist Companies Display -->
    <div id="wishlist-companies-section" class="bg-orange-50 border border-orange-200 p-3 md:p-4 mb-6 md:mb-8" style="display: none;">
        <h3 class="text-base md:text-lg font-semibold text-orange-800 mb-3">ご選択中の専門業者：<span id="company-count">0</span>社</h3>
        <div id="wishlist-companies-list" class="space-y-2">
            <!-- Companies will be populated by JavaScript -->
        </div>
        <div class="text-sm text-orange-700 mt-3">
            <p class="mb-1">ご相談内容は一旦オヤズナにて確認させていただきます。</p>
            <p class="mb-1">内容を精査のうえ、適切な専門業者へ共有いたします。</p>
            <p>その後、業者より直接ご連絡させていただきます。</p>
        </div>
    </div>

    <form action="{{ route('quote.store') }}" method="POST" class="bg-white shadow p-4 md:p-8 space-y-4 md:space-y-6" id="quote-form">
        @csrf

        <!-- Hidden field for wishlist companies -->
        <input type="hidden" name="wishlist_companies" id="wishlist_companies_input" value="">

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 p-4">
                <ul class="list-disc list-inside text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Client Type -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">依頼者区分 <span class="text-red-500">*</span></label>
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" name="client_kind" value="corp" class="mr-2" required {{ old('client_kind') === 'corp' ? 'checked' : '' }}>
                    法人
                </label>
                <label class="flex items-center">
                    <input type="radio" name="client_kind" value="personal" class="mr-2" required {{ old('client_kind') === 'personal' ? 'checked' : '' }}>
                    個人
                </label>
            </div>
        </div>

        <!-- Company Name (if corp) -->
        <div id="company_name_field" class="hidden">
            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">会社名 <span class="text-red-500">*</span></label>
            <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                   class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ご担当者名 <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="例：田中太郎">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">メールアドレス <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">電話番号</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                       class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="prefecture_id" class="block text-sm font-medium text-gray-700 mb-2">都道府県 <span class="text-red-500">*</span></label>
                <select name="prefecture_id" id="prefecture_id" required class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">選択してください</option>
                    @foreach($prefectures as $prefecture)
                        <option value="{{ $prefecture->id }}" {{ old('prefecture_id') == $prefecture->id ? 'selected' : '' }}>
                            {{ $prefecture->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="building_name" class="block text-sm font-medium text-gray-700 mb-2">建物名 <span class="text-green-600">（入力推奨）</span></label>
                <input type="text" name="building_name" id="building_name" value="{{ old('building_name') }}"
                       class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="例：○○ビル、○○マンション、○○商業施設">
            </div>

            <div>
                <label for="floors" class="block text-sm font-medium text-gray-700 mb-2">階数 <span class="text-red-500">*</span></label>
                <input type="number" name="floors" id="floors" min="1" value="{{ old('floors') }}" required
                       class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="例：10">
            </div>
        </div>

        <div>
            <label for="service_category_id" class="block text-sm font-medium text-gray-700 mb-2">作業内容 <span class="text-red-500">*</span></label>
            <select name="service_category_id" id="service_category_id" required class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">選択してください</option>
                @foreach($serviceCategories as $serviceCategory)
                    <option value="{{ $serviceCategory->id }}" {{ old('service_category_id') == $serviceCategory->id ? 'selected' : '' }}>
                        {{ $serviceCategory->label }}
                    </option>
                @endforeach
            </select>
        </div>

        <p class="text-xs text-gray-500">
            ※作業範囲や建物の詳細など、見積もりに必要な情報は送信後に業者から直接ご確認させていただきます。
        </p>

        <div class="text-center">
            <button type="submit" class="bg-orange-600 text-white px-12 py-4 font-bold text-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                無料見積もりを依頼
            </button>
        </div>

        <div class="text-sm text-gray-600 text-center">
            <p>※送信後、各業者から直接連絡が来ます。</p>
            <p>※高所作業の安全性・保険・資格については各業者へ直接ご確認ください。</p>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load wishlist from localStorage
    let wishlist = JSON.parse(localStorage.getItem('companyWishlist') || '[]');

    function displayWishlistCompanies() {
        const section = document.getElementById('wishlist-companies-section');
        const countSpan = document.getElementById('company-count');
        const companiesList = document.getElementById('wishlist-companies-list');
        const hiddenInput = document.getElementById('wishlist_companies_input');

        if (wishlist.length > 0) {
            section.style.display = 'block';
            countSpan.textContent = wishlist.length;

            companiesList.innerHTML = '';
            wishlist.forEach((company, index) => {
                const companyElement = document.createElement('div');
                companyElement.className = 'flex items-center justify-between bg-white px-4 py-3 rounded-lg border';
                companyElement.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                            ${index + 1}
                        </div>
                        <span class="font-medium text-gray-900">${company.name}</span>
                    </div>
                    <button type="button"
                            class="text-gray-400 hover:text-red-500 transition-colors duration-200"
                            onclick="removeFromWishlist(${company.id})">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                companiesList.appendChild(companyElement);
            });

            hiddenInput.value = JSON.stringify(wishlist);
        } else {
            section.style.display = 'none';
            hiddenInput.value = '';
        }
    }

    window.removeFromWishlist = function(companyId) {
        wishlist = wishlist.filter(company => company.id !== companyId);
        localStorage.setItem('companyWishlist', JSON.stringify(wishlist));
        displayWishlistCompanies();
    };

    displayWishlistCompanies();

    document.getElementById('quote-form').addEventListener('submit', function(e) {
        document.getElementById('wishlist_companies_input').value = JSON.stringify(wishlist);
    });

    // Toggle company name field based on client_kind
    const companyNameField = document.getElementById('company_name_field');
    const companyNameInput = document.getElementById('company_name');
    document.querySelectorAll('input[name="client_kind"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.value === 'corp') {
                companyNameField.classList.remove('hidden');
                companyNameInput.setAttribute('required', 'required');
            } else {
                companyNameField.classList.add('hidden');
                companyNameInput.removeAttribute('required');
            }
        });
    });
    // Restore state on validation error reload
    const checkedClientKind = document.querySelector('input[name="client_kind"]:checked');
    if (checkedClientKind && checkedClientKind.value === 'corp') {
        companyNameField.classList.remove('hidden');
        companyNameInput.setAttribute('required', 'required');
    }
});

function removeFromCompare(companyId) {
    $.ajax({
        url: '/compare/remove/' + companyId,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(data) {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endsection
