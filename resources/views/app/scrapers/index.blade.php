@extends('layouts.app', ['page_title' => 'Scrapers'])

@section('content')
    <section id="content">
        <div class="d-flex align-items-center mb-5">
            <h1 class="h3 mb-0">Scrapers</h1>
            <div class="ml-4">
                <a href="{{ route('scraper.create') }}" class="btn btn-primary">
                    Create
                </a>
{{--                @if (count(request()->query()) && (count(request()->except('page'))))--}}
{{--                    <a href="{{ route('type.index') }}" class="btn btn-dark ml-4">--}}
{{--                        <i class="i-reload"></i>--}}
{{--                        Clear filters--}}
{{--                    </a>--}}
{{--                @endif--}}
            </div>
        </div>

{{--        <form class="my-4 d-flex">--}}
{{--            <div class="mr-2 flex-grow-1">--}}
{{--                <input type="text" name="q" value="{{ request()->get('q', null) }}" class="form-control"--}}
{{--                       placeholder="Search">--}}
{{--            </div>--}}
{{--            <button class="btn btn-primary">--}}
{{--                <i class="i-search"></i>--}}
{{--                Search--}}
{{--            </button>--}}
{{--        </form>--}}
        <table class="table table-striped">
            <thead>
            <tr class="small">
                <th>#</th>
                <th>Title</th>
                <th>Auction type</th>
                <th>Run</th>
                <th>Created</th>
                <th></th>
            </tr>
            </thead>

            @forelse($scrapers as $scraper)
                <tr>
                    <td>{{ $scraper->id }}</td>
                    <td>
                        <a href="{{ route('scraper.edit', $scraper) }}" class="underline">
                            {{ $scraper->name }}
                        </a>
                    </td>
                    <td >{{ $scraper->auction->name }}</td>
                    <td >{{ $scraper->run ? 'Worked' : 'Stoped' }}</td>
                    <td width="150">{{ $scraper->created_at->formatLocalized('%d %b %Y, %H:%M') }}</td>
                    <td width="100">
                        <a href="{{ route('scraper.edit', $scraper) }}"
                           class="btn btn-warning btn-squire">
                            <i class="i-pencil"></i>
                        </a>
                        <button class="btn btn-danger btn-squire"
                                onclick="deleteItem('{{ route('scraper.destroy', $scraper) }}')">
                            <i class="i-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Scrapers not added</td>
                </tr>
            @endforelse
        </table>

        {{ $scrapers->appends(request()->except('page'))->links() }}
    </section>

@endsection

@push('scripts')
    <form method="post" id="delete" style="display: none">
        @csrf
        @method('delete')
    </form>

    <script>
      function deleteItem(route) {
        const form = document.getElementById('delete');
        const conf = confirm('Sure?');

        if (conf) {
          form.action = route;
          form.submit();
        }
      }
    </script>
@endpush