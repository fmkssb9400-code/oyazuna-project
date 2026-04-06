@foreach($companies as $company)
    <x-company-card :company="$company" />
@endforeach