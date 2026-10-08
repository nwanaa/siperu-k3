<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi - SIPERU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased p-8">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-200">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-slate-900">Notifikasi Anda</h2>
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline text-sm font-medium">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="p-4 border rounded-lg {{ $notification->read_at ? 'bg-white border-slate-200' : 'bg-blue-50 border-blue-200' }} flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm mb-1">
                            Ruangan: {{ $notification->data['room_name'] ?? '-' }} 
                            <span class="ml-2 px-2 py-0.5 rounded text-xs text-white bg-slate-600">
                                {{ strtoupper(str_replace('_', ' ', $notification->data['status'] ?? '')) }}
                            </span>
                        </h4>
                        <p class="text-sm text-slate-600">{{ $notification->data['message'] }}</p>
                        <span class="text-xs text-slate-400 mt-2 block">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    
                    @if(is_null($notification->read_at))
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded">
                                Tandai Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="p-4 text-center text-slate-500 border border-dashed rounded-lg">
                    Belum ada notifikasi.
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>