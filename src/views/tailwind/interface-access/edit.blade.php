@extends('laravelMain::' . $layout_file)
@section('title', 'Edit Access Type')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Edit Access Type</h4>
        @if (Route::has('useraccess.interface-access.list'))
            <a class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition font-medium text-gray-700 dark:text-gray-200" href="{{ route('useraccess.interface-access.list') }}">Back</a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md" role="alert">{{ session('success') }}</div>
    @endif

    @if (Route::has('useraccess.interface-access.update'))
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <form method="POST" action="{{ route('useraccess.interface-access.update', $accessFor) }}">
                @csrf 
                @method('PUT')                
                @include('useraccess::tailwind.interface-access._form')
                <button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>  
            </form>
        </div>
    @else
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" role="alert">You do not have permission to create an interface-access.</div>
    @endif
</div>
@endsection
