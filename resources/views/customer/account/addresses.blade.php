@extends('layouts.app', ['title' => 'My Addresses'])

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8" x-data="{ show: false, editId: null, form: {} }">
  <div class="flex items-center justify-between mb-8">
    <h1 class="font-display text-2xl font-bold text-charcoal">My Addresses</h1>
    <button @click="show = true; editId = null; form = {label:'home', name:'{{ auth()->user()->name }}', phone:'{{ auth()->user()->phone }}', house_no:'', street:'', area:'', landmark:'', city:'', state:'', pincode:'', is_default:false}" class="btn btn-primary btn-sm">+ Add Address</button>
  </div>

  @if(session('success'))<div class="mb-6 rounded-xl border border-forest/20 bg-forest/5 px-4 py-3 text-sm text-forest">{{ session('success') }}</div>@endif

  <div class="grid gap-4 sm:grid-cols-2">
    @forelse($addresses as $address)
      <div class="rounded-2xl border border-sage/20 bg-white p-5 shadow-sm relative {{ $address->is_default ? 'border-forest' : '' }}">
        @if($address->is_default)
          <span class="absolute top-3 right-3 rounded-full bg-forest px-2 py-0.5 text-[10px] font-bold text-white">DEFAULT</span>
        @endif
        <div class="text-sm">
          <div class="font-semibold text-charcoal flex items-center gap-2">
            <span class="uppercase text-[10px] font-bold rounded bg-sage/10 px-1.5 py-0.5">{{ $address->label }}</span>
            {{ $address->name }}
          </div>
          <div class="mt-2 text-charcoal/60 leading-relaxed text-xs">
            {{ $address->house_no }}, {{ $address->street ?? '' }}<br>
            {{ $address->area ?? '' }}, {{ $address->city }}, {{ $address->state }} {{ $address->pincode }}
          </div>
          <div class="mt-2 text-xs text-charcoal/40">📞 {{ $address->phone }}</div>
        </div>
        <div class="mt-3 flex gap-2 text-xs">
          @if(!$address->is_default)
            <form action="{{ route('account.addresses.default', $address) }}" method="POST">@csrf
              <button type="submit" class="text-forest font-semibold hover:underline">Set Default</button>
            </form>
          @endif
          <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Delete this address?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-red-500 font-semibold hover:underline">Delete</button>
          </form>
        </div>
      </div>
    @empty
      <div class="sm:col-span-2 rounded-2xl border border-sage/20 bg-white py-12 text-center">
        <div class="text-4xl mb-3 opacity-40">📍</div>
        <p class="text-charcoal/50 text-sm">No addresses saved yet.</p>
      </div>
    @endforelse
  </div>

  <div x-show="show" x-cloak x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="show = false">
    <div class="mx-4 w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl max-h-[90vh] overflow-y-auto"
      x-show="show" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
      <h2 class="font-semibold text-charcoal mb-4" x-text="editId ? 'Edit Address' : 'Add New Address'"></h2>
      <form method="POST" :action="editId ? '{{ url('account/addresses') }}/' + editId : '{{ route('account.addresses.store') }}'">
        @csrf <template x-if="editId"><input type="hidden" name="_method" value="PATCH"></template>

        <div class="grid gap-4 sm:grid-cols-2 text-sm">
          <div class="sm:col-span-2">
            <label class="mb-1 block text-xs font-semibold text-charcoal">Label</label>
            <select x-model="form.label" name="label" class="input">
              <option value="home">Home</option><option value="office">Office</option><option value="other">Other</option>
            </select>
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold text-charcoal">Full Name *</label>
            <input type="text" x-model="form.name" name="name" class="input" required>
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold text-charcoal">Phone *</label>
            <input type="text" x-model="form.phone" name="phone" class="input" required pattern="\d{10}">
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold text-charcoal">House/Flat No. *</label>
            <input type="text" x-model="form.house_no" name="house_no" class="input" required>
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold text-charcoal">Street/Road</label>
            <input type="text" x-model="form.street" name="street" class="input">
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold text-charcoal">Area/Locality</label>
            <input type="text" x-model="form.area" name="area" class="input">
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold text-charcoal">Landmark</label>
            <input type="text" x-model="form.landmark" name="landmark" class="input">
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold text-charcoal">City *</label>
            <input type="text" x-model="form.city" name="city" class="input" required>
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold text-charcoal">State *</label>
            <input type="text" x-model="form.state" name="state" class="input" required>
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold text-charcoal">Pincode *</label>
            <input type="text" x-model="form.pincode" name="pincode" class="input" required pattern="\d{6}">
          </div>
          <div class="sm:col-span-2">
            <label class="flex items-center gap-2 text-xs text-charcoal">
              <input type="checkbox" name="is_default" :checked="form.is_default" @change="form.is_default = $el.checked" class="accent-forest">
              Set as default delivery address
            </label>
          </div>
        </div>
        <div class="mt-4 flex justify-end gap-2">
          <button type="button" @click="show = false" class="btn btn-ghost btn-sm">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Save Address</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
