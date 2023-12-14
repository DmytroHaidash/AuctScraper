<?php

namespace App\Http\Controllers;

use App\Models\Scraper;
use App\Models\TypeAuction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScraperController extends Controller
{
    public function index(): View
    {
        $scrapers = Scraper::query()->paginate(20);
        return view('app.scrapers.index', compact('scrapers'));
    }

    public function create(): View
    {
        $auctions = TypeAuction::query()->get();
        return view('app.scrapers.create', compact('auctions'));
    }

    public function store(Request $request): RedirectResponse
    {
        Scraper::query()->create([
            'name' => $request->get('title'),
            'run' => $request->has('run'),
            'url' => $request->get('url'),
            'type_auction_id' => $request->get('type')
        ]);

        return redirect()->route('scraper.index');
    }

    public function edit(Scraper $scraper): View
    {
        $auctions = TypeAuction::query()->get();
        return view('app.scrapers.edit', compact('auctions', 'scraper'));
    }

    public function update(Request $request, Scraper $scraper): RedirectResponse
    {
        $scraper->update([
            'name' => $request->get('title'),
            'run' => $request->has('run'),
            'url' => $request->get('url'),
            'type_auction_id' => $request->get('type')
        ]);
        return redirect()->route('scraper.index');
    }

    public function destroy(Scraper $scraper): RedirectResponse
    {
        $scraper->delete();
        return redirect()->route('scraper.index');
    }
}
