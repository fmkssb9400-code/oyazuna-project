<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaticPage;

class GuideController extends Controller
{
    public function windowCleaningPrice()
    {
        $page = StaticPage::where('page_type', 'window-cleaning-price-guide')
                          ->published()
                          ->first();
                          
        return view('guide.layout', compact('page'));
    }

    public function windowCleaningContractorSelection()
    {
        $page = StaticPage::where('page_type', 'window-cleaning-contractor-guide')
                          ->published()
                          ->first();
                          
        return view('guide.layout', compact('page'));
    }

    public function exteriorWallPaintingPricing()
    {
        $page = StaticPage::where('page_type', 'exterior-wall-painting-price-guide')
                          ->published()
                          ->first();
                          
        return view('guide.layout', compact('page'));
    }

    public function exteriorWallPaintingContractorSelection()
    {
        $page = StaticPage::where('page_type', 'exterior-wall-painting-contractor-guide')
                          ->published()
                          ->first();

        return view('guide.layout', compact('page'));
    }

    public function wallRepairPrice()
    {
        $page = StaticPage::where('page_type', 'wall-repair-price-guide')
                          ->published()
                          ->first();

        return view('guide.layout', compact('page'));
    }

    public function exteriorCleaningPrice()
    {
        $page = StaticPage::where('page_type', 'exterior-cleaning-price-guide')
                          ->published()
                          ->first();

        return view('guide.layout', compact('page'));
    }

    public function exteriorInspectionPrice()
    {
        $page = StaticPage::where('page_type', 'exterior-inspection-price-guide')
                          ->published()
                          ->first();

        return view('guide.layout', compact('page'));
    }

    public function signboardPrice()
    {
        $page = StaticPage::where('page_type', 'signboard-price-guide')
                          ->published()
                          ->first();

        return view('guide.layout', compact('page'));
    }

    public function birdControlPrice()
    {
        $page = StaticPage::where('page_type', 'bird-control-price-guide')
                          ->published()
                          ->first();

        return view('guide.layout', compact('page'));
    }
}
