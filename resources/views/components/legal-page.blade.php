@props(['title', 'updated'])

<div class="max-w-3xl mx-auto px-4 py-10 md:py-14">
    <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $title }}</h1>
    <p class="text-sm text-gray-500 mb-10">制定日：{{ $updated }}</p>

    <div class="legal-content text-gray-800 leading-relaxed space-y-10">
        {{ $slot }}
    </div>

    <div class="mt-14 pt-8 border-t border-gray-200 flex flex-wrap gap-x-6 gap-y-2 text-sm">
        <a href="{{ route('legal.privacy') }}" class="text-blue-600 hover:underline">プライバシーポリシー</a>
        <a href="{{ route('legal.terms') }}" class="text-blue-600 hover:underline">利用規約</a>
        <a href="{{ route('legal.disclaimer') }}" class="text-blue-600 hover:underline">免責事項</a>
        <a href="{{ route('contact.create') }}" class="text-blue-600 hover:underline">お問い合わせ</a>
    </div>
</div>

<style>
    .legal-content section h2 {
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #2563eb;
    }
    .legal-content section p,
    .legal-content section li {
        font-size: 0.95rem;
    }
    .legal-content section ul,
    .legal-content section ol {
        margin-top: 0.5rem;
        padding-left: 1.5rem;
    }
    .legal-content section ul {
        list-style: disc;
    }
    .legal-content section ol {
        list-style: decimal;
    }
    .legal-content section li + li {
        margin-top: 0.25rem;
    }
    .legal-content section + section {
        margin-top: 0;
    }
    .legal-content table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
        margin-top: 0.75rem;
    }
    .legal-content th,
    .legal-content td {
        border: 1px solid #e5e7eb;
        padding: 0.5rem 0.75rem;
        text-align: left;
        vertical-align: top;
    }
    .legal-content th {
        background-color: #f9fafb;
        white-space: nowrap;
    }
</style>
