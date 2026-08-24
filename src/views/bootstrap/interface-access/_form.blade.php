<div class="row mt-3">
    <div class="form-group col-lg-4 col-md-4">
        <label for="name" class="text-black">Name</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" value="{{ old('name', $accessFor->name ?? '') }}" >
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="row mt-3">
    <div class="form-group col-lg-4 col-md-4">
        <label class="text-black">Allowed Access Types</label>
        @php($selectedTypes = old('access_type', isset($accessFor) ? array_filter(explode(',', $accessFor->access_type)) : []))
        @foreach ($accessTypes as $type)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="access_type[]" value="{{ $type }}" id="type_{{ $type }}" @checked(in_array($type, $selectedTypes))>
                <label class="form-check-label" for="type_{{ $type }}">{{ strtoupper($type) }}</label>
            </div>
        @endforeach
    </div>
</div>
