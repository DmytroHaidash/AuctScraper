@extends('layouts.app', ['page_title' => 'Types'])

@section('content')
    <section id="content">
        <div class="d-flex align-items-center mb-5">
            <h1 class="h3 mb-0">Auction Types</h1>
            <div class="ml-4">
                <a href="{{ route('type.create') }}" class="btn btn-primary">
                    Create
                </a>
                @if (count(request()->query()) && (count(request()->except('page'))))
                    <a href="{{ route('type.index') }}" class="btn btn-dark ml-4">
                        <i class="i-reload"></i>
                        Clear filters
                    </a>
                @endif
            </div>
        </div>

        <form class="my-4 d-flex">
            <div class="mr-2 flex-grow-1">
                <input type="text" name="q" value="{{ request()->get('q', null) }}" class="form-control"
                       placeholder="Search">
            </div>
            <button class="btn btn-primary">
                <i class="i-search"></i>
                Search
            </button>
        </form>
        <table class="table table-striped">
            <thead>
            <tr class="small">
                <th>#</th>
                <th>Title</th>
                {{--<th>Tags</th>--}}
                <th>Period</th>
                <th>Created</th>
                <th></th>
            </tr>
            </thead>

            @forelse($auctions as $auction)
                <tr>
                    <td>{{ $auction->id }}</td>
                    <td>
                        <a href="{{ route('type.edit', $auction) }}" class="underline">
                            {{ $auction->name }}
                        </a>
                    </td>
                    <td class="text-center">{{ $auction->period }}</td>
                    <td width="150">{{ $auction->created_at->formatLocalized('%d %b %Y, %H:%M') }}</td>
                    <td width="100">
                        <a href="{{ route('type.edit', $auction) }}"
                           class="btn btn-warning btn-squire">
                            <i class="i-pencil"></i>
                        </a>
                        <button class="btn btn-danger btn-squire"
                                onclick="deleteItem('{{ route('type.destroy', $auction) }}')">
                            <i class="i-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Type Auctions not added</td>
                </tr>
            @endforelse
        </table>

        {{ $auctions->appends(request()->except('page'))->links() }}
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