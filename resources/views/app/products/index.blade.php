@extends('layouts.app', ['page_title' => 'Product'])

@section('content')
    <section id="content">
        <div class="d-flex align-items-center mb-5">
            <h1 class="h3 mb-0">Product</h1>
            <div class="ml-4">
                @if (count(request()->query()) && (count(request()->except('page'))))
                    <a href="{{ route('product.index') }}" class="btn btn-dark ml-4">
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
                <th>Scraper</th>
                <th>Old Price</th>
                <th>New Price</th>
                <th>Last update</th>
{{--                <th></th>--}}
            </tr>
            </thead>

            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
{{--                        <a href="{{ route('scraper.edit', $scraper) }}" class="underline">--}}
                            {{ $product->name }}
{{--                        </a>--}}
                    </td>
                    <td>{{ $product->scraper->name }}</td>
                    <td>{{ $product->old_price}}</td>
                    <td>{{ $product->new_price}}</td>
                    <td>{{ $product->updated_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Products not scraped</td>
                </tr>
            @endforelse
        </table>

        {{ $products->appends(request()->except('page'))->links() }}
    </section>

@endsection

{{--@push('scripts')--}}
{{--    <form method="post" id="delete" style="display: none">--}}
{{--        @csrf--}}
{{--        @method('delete')--}}
{{--    </form>--}}

{{--    <script>--}}
{{--      function deleteItem(route) {--}}
{{--        const form = document.getElementById('delete');--}}
{{--        const conf = confirm('Sure?');--}}

{{--        if (conf) {--}}
{{--          form.action = route;--}}
{{--          form.submit();--}}
{{--        }--}}
{{--      }--}}
{{--    </script>--}}
{{--@endpush--}}