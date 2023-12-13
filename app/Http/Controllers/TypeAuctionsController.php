<?php

namespace App\Http\Controllers;

use App\Models\TypeAuction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TypeAuctionsController extends Controller
{
    public function index(): View
    {
        $auctions = TypeAuction::query()->paginate(20);
        return view('app.type-auctions.index', compact('auctions'));
    }

    public function create(): View
    {
        return view('app.type-auctions.create');
    }

    public function store(Request $request)
    {

    }

    public function edit(TypeAuction $auction): View
    {
        return  view('app.type-auctions.edit', compact('auction'));
    }

    public function update(Request $request, TypeAuction $auction): RedirectResponse
    {

        return redirect()->route('');
    }

    public function destroy(TypeAuction $auction): RedirectResponse
    {
        $auction->delete();
        return redirect()->route();
    }
}
