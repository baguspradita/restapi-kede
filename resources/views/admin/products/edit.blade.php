@extends('layouts.admin')

@section('header', 'Edit Product')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Edit Product: {{ $product->name }}</h3>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Back to List</a>
        </div>
        
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" placeholder="e.g. Fresh Apples">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" id="category_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input type="text" name="unit" id="unit" value="{{ old('unit', $product->unit) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="e.g. kg, pcs, pack">
                    @error('unit') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (Rp)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" class="w-full pl-10 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                    </div>
                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="0">
                    @error('stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div class="col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Image URL -->
                <div class="col-span-2">
                    <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">Image URL (Unsplash or any online source)</label>
                    
                    @if(isset($product->images) && is_array($product->images) && count($product->images) > 0)
                        <div class="mb-4">
                           <p class="text-xs text-gray-500 mb-2">Current Image:</p>
                           @php
                               $img = $product->images[0];
                               $isUrl = Str::startsWith($img, ['http://', 'https://']);
                               $src = $isUrl ? $img : Storage::url($img);
                           @endphp
                           <img src="{{ $src }}" alt="Current Image" class="h-32 w-32 object-cover rounded-lg border shadow-sm">
                        </div>
                    @endif

                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            https://
                        </span>
                        <input type="url" name="image_url" id="image_url" 
                            value="{{ old('image_url', (isset($product->images) && is_array($product->images) && count($product->images) > 0 && Str::startsWith($product->images[0], ['http', 'https'])) ? $product->images[0] : '') }}" 
                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" placeholder="images.unsplash.com/photo-...">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Copy the image link and paste it here to replace the current image.</p>
                    @error('image_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Availability -->
                <div class="col-span-2">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_available" name="is_available" type="checkbox" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_available" class="font-medium text-gray-700">Available for sale</label>
                            <p class="text-gray-500">Check this if the product is currently in stock and saleable.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
