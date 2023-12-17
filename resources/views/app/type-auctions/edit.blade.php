@extends('layouts.app', ['page_title' => $type->name])

@section('content')

    <section id="content">
        <form action="{{ route('type.update', $type) }}" method="post">
            @csrf
            @method('patch')

            <div class="row">
                <div class="col-12">

                    <div class="form-group{{ $errors->has('title') ? ' is-invalid' : '' }}">
                        <label for="title">Name</label>
                        <input type="text" class="form-control" id="title" name="title"
                               value="{{ old('title') ?? $type->name }}" required>
                        @if($errors->has('title'))
                            <div class="mt-1 text-danger">
                                {{ $errors->first('title') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('credentials') ? ' is-invalid' : '' }}">
                        <label for="credentials">Credentials</label>
                        <input type="text" class="form-control" id="credentials" name="credentials"
                               value="{{ old('credentials') }}">
                        @if($errors->has('credentials'))
                            <div class="mt-1 text-danger">
                                {{ $errors->first('credentials') }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 d-flex align-items-center">
                        <button class="btn btn-primary">Save</button>

{{--                        <div class="custom-control custom-checkbox ml-3">--}}
{{--                            <input type="checkbox" class="custom-control-input"--}}
{{--                                   id="published" name="is_published"--}}
{{--                                    {{ $auction->is_published ? 'checked' : '' }}>--}}
{{--                            <label class="custom-control-label" for="published">Published</label>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection