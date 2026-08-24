 <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
        <input type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror" id="name" name="name" placeholder="Enter role name" value="{{ old('name', $accessFor->name ?? '') }}">
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium mb-2">Allowed Access Types</label>
        @php($selectedTypes = old('access_type', isset($accessFor) ? array_filter(explode(',', $accessFor->access_type)) : []))
        @foreach($accessTypes as $type)
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <input type="checkbox" name="access_type[]" value="{{ $type }}" @checked(in_array($type, $selectedTypes))>
                <span>{{ strtoupper($type) }}</span>
            </label>
        @endforeach
    </div>
</div>