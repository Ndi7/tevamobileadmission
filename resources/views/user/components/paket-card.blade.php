<div class="bg-[#E8F4FB] p-4 rounded-xl mb-3">
    <div class="font-bold text-base">{{ $title }}</div>

    <div class="mt-2 space-y-1 text-sm">
        @foreach($items as $item)
            <div>• {{ $item }}</div>
        @endforeach
    </div>
</div>