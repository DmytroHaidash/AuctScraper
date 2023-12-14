@extends('layouts.app', ['page_title' => 'New auction'])

@section('content')

    <section id="content">
        <form action="{{ route('type.store') }}" method="post">
            @csrf

            <div class="row">
                <div class="col-12">

                    <div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
                        <label for="title">Name</label>
                        <input type="text" class="form-control" id="title" name="title"
                               value="{{ old('title') }}" required>
                        @if($errors->has('title'))
                            <div class="mt-1 text-danger">
                                {{ $errors->first('title') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('period') ? ' is-invalid' : '' }}">
                        <label for="period">Period</label>
                        <input type="number" class="form-control" id="period" name="period" min="1" step="1"
                               value="{{ old('period') }}" required>
                        @if($errors->has('period'))
                            <div class="mt-1 text-danger">
                                {{ $errors->first('period') }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 d-flex align-items-center">
                        <button class="btn btn-primary">Save</button>

{{--                        <div class="custom-control custom-checkbox ml-3">--}}
{{--                            <input type="checkbox" class="custom-control-input"--}}
{{--                                   id="published" name="is_published" checked>--}}
{{--                            <label class="custom-control-label" for="published">Published</label>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection