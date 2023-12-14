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
        TypeAuction::query()->create([
            'name' => $request->get('title'),
            'period' => $request->get('period')
        ]);
        return redirect()->route('type.index');
    }

    public function edit(TypeAuction $type): View
    {
        return view('app.type-auctions.edit', compact('type'));
    }

    public function update(Request $request, TypeAuction $type): RedirectResponse
    {
        $type->update([
            'name' => $request->get('title'),
            'period' => $request->get('period')
        ]);
        return redirect()->route('type.index');
    }

    public function destroy(TypeAuction $type): RedirectResponse
    {
        $type->delete();
        return redirect()->route('type.index');
    }
}
