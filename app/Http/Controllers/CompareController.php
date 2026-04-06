<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompareController extends Controller
{
    public function add(Request $request, Company $company)
    {
        $compareIds = session('compare_company_ids', []);
        
        if (!in_array($company->id, $compareIds) && count($compareIds) < 5) {
            $compareIds[] = $company->id;
            session(['compare_company_ids' => $compareIds]);
        }

        return response()->json(['success' => true, 'count' => count($compareIds)]);
    }

    public function remove(Request $request, Company $company)
    {
        $compareIds = session('compare_company_ids', []);
        $compareIds = array_values(array_filter($compareIds, function($id) use ($company) {
            return $id !== $company->id;
        }));
        
        session(['compare_company_ids' => $compareIds]);

        return response()->json(['success' => true, 'count' => count($compareIds)]);
    }

    public function index(Request $request)
    {
        $compareIds = session('compare_company_ids', []);
        
        if (empty($compareIds)) {
            return redirect()->route('companies.index')->with('message', '比較する業者を選択してください。');
        }

        $companies = Company::with(['prefectures', 'serviceMethods', 'buildingTypes', 'serviceCategories'])
            ->whereIn('id', $compareIds)
            ->get();

        return view('compare.index', compact('companies'));
    }
}
