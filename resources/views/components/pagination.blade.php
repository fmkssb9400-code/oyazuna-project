@if($companies->hasPages())
<div class="flex justify-center">
    {{ $companies->withQueryString()->links() }}
</div>
@endif