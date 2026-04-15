@props(['headers' => [], 'empty' => 'No records found.'])
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto overscroll-x-contain">
        <table class="min-w-[640px] w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    @foreach($headers as $header)
                    <th class="text-left text-xs font-medium text-text-secondary uppercase tracking-wider px-4 py-3 sm:px-6">{{ $header }}</th>
                    @endforeach
                    <th class="text-right text-xs font-medium text-text-secondary uppercase tracking-wider px-4 py-3 sm:px-6">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    @if(isset($pagination))
    <div class="border-t border-gray-100 px-4 py-3 sm:px-6">{{ $pagination }}</div>
    @endif
</div>
