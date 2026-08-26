@extends('layouts.admin', ['title' => $product->exists ? 'Edit Product: '.$product->name : 'Add Product'])

@section('content')
<form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
  @csrf
  @if($product->exists) @method('PATCH') @endif

  <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
    <div class="space-y-6">
      <section class="adm-section space-y-4">
        <h2 class="adm-section-title">Product Details</h2>
        <div>
          <label class="adm-label">Product Name *</label>
          <input type="text" name="name" value="{{ old('name', $product->name) }}" class="adm-input" required>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="adm-label">SKU *</label>
            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="adm-input" {{ $product->exists ? 'readonly disabled' : 'required' }}>
            @if($product->exists)<p class="text-[10px] adm-text-muted mt-1">SKU cannot be changed after creation</p>@endif
          </div>
          <div>
            <label class="adm-label">Category *</label>
            <select name="category_id" class="adm-input" required>
              <option value="">Select category…</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                  {{ $cat->parent ? $cat->parent->name.' → ' : '' }}{{ $cat->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="adm-label">Brand</label>
            <select name="brand_id" class="adm-input">
              <option value="">None</option>
              @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="adm-label">Unit Label</label>
            <input type="text" name="unit_label" value="{{ old('unit_label', $product->unit_label) }}" class="adm-input" placeholder="e.g. 500g, 1 litre">
          </div>
        </div>
        <div>
          <label class="adm-label">Short Description</label>
          <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="adm-input" maxlength="500">
        </div>
        <div>
          <label class="adm-label">Full Description</label>
          <textarea name="description" class="adm-input" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>
      </section>

      <section class="adm-section space-y-4">
        <h2 class="adm-section-title">Organic Details</h2>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="adm-label">Origin</label>
            <input type="text" name="origin" value="{{ old('origin', $product->origin) }}" class="adm-input">
          </div>
          <div>
            <label class="adm-label">Farmer Source</label>
            <input type="text" name="farmer_source" value="{{ old('farmer_source', $product->farmer_source) }}" class="adm-input">
          </div>
          <div>
            <label class="adm-label">Certification</label>
            <input type="text" name="certification" value="{{ old('certification', $product->certification) }}" class="adm-input">
          </div>
          <div class="flex items-center pt-6">
            <label class="flex items-center gap-2 text-sm adm-text-primary">
              <input type="checkbox" name="is_organic" value="1" {{ old('is_organic', $product->is_organic) ? 'checked' : '' }} class="accent-forest">
              Certified Organic Product
            </label>
          </div>
        </div>
        <div>
          <label class="adm-label">Ingredients</label>
          <textarea name="ingredients" class="adm-input" rows="2">{{ old('ingredients', $product->ingredients) }}</textarea>
        </div>
        <div>
          <label class="adm-label">Benefits</label>
          <textarea name="benefits" class="adm-input" rows="2">{{ old('benefits', $product->benefits) }}</textarea>
        </div>
        <div>
          <label class="adm-label">Usage Instructions</label>
          <textarea name="usage_instructions" class="adm-input" rows="2">{{ old('usage_instructions', $product->usage_instructions) }}</textarea>
        </div>
        <div>
          <label class="adm-label">Storage Instructions</label>
          <textarea name="storage_instructions" class="adm-input" rows="2">{{ old('storage_instructions', $product->storage_instructions) }}</textarea>
        </div>
      </section>

      <section class="adm-section space-y-4">
        <div class="flex items-center justify-between">
          <h2 class="adm-section-title mb-0 border-0 pb-0">Variants</h2>
        </div>
        <div id="variants-container" class="space-y-3"
             x-data="{
               variants: {{ Js::from($product->exists ? $product->variants()->with('inventory')->orderBy('sort_order')->get()->map(fn($v) => ['id'=>$v->id,'sku'=>$v->sku,'name'=>$v->name,'price'=>$v->price,'sale_price'=>$v->sale_price,'stock'=>$v->inventory->stock ?? 0,'low_stock_threshold'=>$v->inventory->low_stock_threshold ?? 10,'is_active'=>$v->is_active,'is_default'=>$v->is_default]) : [['sku'=>old('sku', substr(strtoupper(md5(Str::slug(old('name','')))),0,10)), 'name'=>'Default', 'price'=>old('regular_price', 0), 'sale_price'=>null, 'stock'=>0, 'low_stock_threshold'=>10, 'is_active'=>1, 'is_default'=>1]]) }})">
          <template x-for="(v, idx) in variants" :key="idx">
            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 space-y-2">
              <div class="flex items-center justify-between text-xs font-semibold adm-text-muted">
                <span x-text="'Variant ' + (idx+1)"></span>
                <button type="button" @click="variants.splice(idx, 1)" x-show="variants.length > 1" class="text-red-400 hover:text-red-600">Remove</button>
              </div>
              <div class="grid gap-3 sm:grid-cols-3">
                <div>
                  <label class="adm-label text-[10px]">Variant Name</label>
                  <input :name="'variants['+idx+'][name]'" x-model="v.name" class="adm-input text-sm" required>
                </div>
                <div>
                  <label class="adm-label text-[10px]">SKU</label>
                  <input :name="'variants['+idx+'][sku]'" x-model="v.sku" class="adm-input text-sm" required>
                </div>
                <div>
                  <label class="adm-label text-[10px]">Price (₹)</label>
                  <input type="number" step="0.01" :name="'variants['+idx+'][price]'" x-model.number="v.price" class="adm-input text-sm" required>
                </div>
                <div>
                  <label class="adm-label text-[10px]">Sale Price (₹)</label>
                  <input type="number" step="0.01" :name="'variants['+idx+'][sale_price]'" x-model.number="v.sale_price" class="adm-input text-sm">
                </div>
                <div>
                  <label class="adm-label text-[10px]">Stock</label>
                  <input type="number" :name="'variants['+idx+'][stock]'" x-model.number="v.stock" class="adm-input text-sm">
                </div>
                <div>
                  <label class="adm-label text-[10px]">Low Stock Alert</label>
                  <input type="number" :name="'variants['+idx+'][low_stock_threshold]'" x-model.number="v.low_stock_threshold" class="adm-input text-sm">
                </div>
              </div>
              <div class="flex gap-4">
                <label class="flex items-center gap-1.5 text-xs adm-text-primary"><input type="checkbox" :name="'variants['+idx+'][is_active]'" :value="1" :checked="v.is_active" @change="v.is_active = $el.checked" class="accent-forest"> Active</label>
                <label class="flex items-center gap-1.5 text-xs adm-text-primary"><input type="checkbox" :name="'variants['+idx+'][is_default]'" :value="1" :checked="v.is_default" @change="v.is_default = $el.checked" class="accent-forest"> Default</label>
              </div>
            </div>
          </template>
        </div>
        <button type="button" @click="variants.push({sku:'',name:'',price:0,sale_price:null,stock:0,low_stock_threshold:10,is_active:true,is_default:false})" class="adm-btn-ghost text-forest hover:underline">+ Add Another Variant</button>
      </section>

      <section class="adm-section space-y-4">
        <h2 class="adm-section-title">Images</h2>
        @if($product->exists && $product->images->count())
          <div class="grid grid-cols-5 gap-3">
            @foreach($product->images->sortBy('sort_order') as $image)
              <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                <img src="{{ asset('storage/'.$image->path) }}" class="aspect-square w-full object-contain p-2">
                @if($image->is_primary)<span class="absolute top-1 left-1 rounded bg-forest px-1.5 py-0.5 text-[9px] font-bold text-white">Primary</span>@endif
                <form action="{{ route('admin.images.destroy', $image) }}" method="POST" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition">
                  @csrf @method('DELETE')
                  <button type="submit" onclick="return confirm('Delete image?')" class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-bold text-white">Delete</button>
                </form>
              </div>
            @endforeach
          </div>
        @endif
        <div>
          <label class="adm-label">Upload New Images (multi-select allowed)</label>
          <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="adm-input">
        </div>
      </section>

      <section class="adm-section space-y-4">
        <h2 class="adm-section-title">SEO</h2>
        <div><label class="adm-label">Title Tag</label><input type="text" name="seo_title" value="{{ old('seo_title', $product->seo_title) }}" class="adm-input"></div>
        <div><label class="adm-label">Meta Description</label><textarea name="meta_description" class="adm-input" rows="2">{{ old('meta_description', $product->meta_description) }}</textarea></div>
        <div><label class="adm-label">Keywords</label><input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" class="adm-input"></div>
      </section>
    </div>

    <div class="space-y-4 lg:sticky lg:top-24 lg:self-start">
      <section class="adm-section space-y-4">
        <h3 class="adm-section-title">Pricing & Status</h3>
        <div>
          <label class="adm-label">Regular Price (₹) *</label>
          <input type="number" step="0.01" name="regular_price" value="{{ old('regular_price', $product->regular_price ?? $product->defaultVariant?->price) }}" class="adm-input" required>
        </div>
        <div>
          <label class="adm-label">Sale Price (₹)</label>
          <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? $product->defaultVariant?->sale_price) }}" class="adm-input">
        </div>
        <div>
          <label class="adm-label">Cost Price (₹)</label>
          <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" class="adm-input">
        </div>
        <div>
          <label class="adm-label">Status</label>
          <select name="status" class="adm-input">
            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
            <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="out_of_stock" {{ old('status', $product->status) === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
          </select>
        </div>
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-xs adm-text-primary"><input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="accent-forest"> Featured Product</label>
          <label class="flex items-center gap-2 text-xs adm-text-primary"><input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }} class="accent-forest"> Best Seller</label>
          <label class="flex items-center gap-2 text-xs adm-text-primary"><input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }} class="accent-forest"> New Arrival</label>
        </div>
      </section>

      <div class="flex flex-col gap-2">
        <button type="submit" class="adm-btn-primary w-full">{{ $product->exists ? 'Update Product' : 'Create Product' }}</button>
        @if($product->exists)
          <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Move this product to trash?')">
            @csrf @method('DELETE')
            <button type="submit" class="adm-btn-danger w-full">Delete Product</button>
          </form>
        @endif
      </div>
    </div>
  </div>
</form>
@endsection
