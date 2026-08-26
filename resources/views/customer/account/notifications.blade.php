@extends('layouts.app', ['title' => 'Notifications — AB Organic Farm'])

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
  <div class="flex items-center justify-between mb-8">
    <h1 class="font-display text-2xl font-bold text-charcoal">Notifications</h1>
    @if($unreadCount > 0)
      <form action="{{ route('account.notifications.mark-all') }}" method="POST">
        @csrf
        <button type="submit" class="text-sm font-semibold text-forest hover:underline">Mark all as read ({{ $unreadCount }})</button>
      </form>
    @endif
  </div>

  @if($notifications->count())
    <div class="divide-y divide-sage/20 rounded-2xl border border-sage/20 bg-white shadow-sm">
      @foreach($notifications as $notification)
        <div class="px-6 py-4 text-sm {{ $notification->read_at ? 'bg-white' : 'bg-forest/5' }}">
          <div class="flex items-start justify-between gap-4">
            <div>
              @if(! $notification->read_at)
                <span class="mr-2 inline-block h-2 w-2 rounded-full bg-forest"></span>
              @endif
              @if(isset($notification->data['title']))
                <div class="font-semibold text-charcoal">{{ $notification->data['title'] }}</div>
              @endif
              <div class="mt-1 text-charcoal/60">{{ $notification->data['message'] ?? json_encode($notification->data) }}</div>
              @if(isset($notification->data['action_url']))
                <a href="{{ $notification->data['action_url'] }}" class="mt-2 inline-block text-xs font-semibold text-forest hover:underline">View →</a>
              @endif
            </div>
            <div class="text-xs text-charcoal/30 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-6">{{ $notifications->links('pagination::tailwind') }}</div>
  @else
    <div class="rounded-2xl border border-sage/20 bg-white py-16 text-center">
      <div class="text-5xl mb-4 opacity-40">🔔</div>
      <p class="text-charcoal/50">No notifications yet.</p>
    </div>
  @endif
</div>
@endsection
