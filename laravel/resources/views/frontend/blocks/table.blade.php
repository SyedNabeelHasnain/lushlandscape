@php
    $headers = array_map('trim', explode(',', $content['headers'] ?? ''));
    $rows    = $content['rows'] ?? [];
    $striped = $content['striped'] ?? true;
@endphp
@if(!empty(array_filter($headers)))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-8">
    <div class="overflow-x-auto border border-stone">
        <table class="w-full text-sm">
            @if(!empty($content['caption']))
            <caption class="text-xs text-text-secondary py-3">{{ $content['caption'] }}</caption>
            @endif
            <thead class="bg-forest text-white">
                <tr>
                    @foreach($headers as $h)
                    <th class="text-left px-5 py-4 font-semibold text-sm">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-stone bg-white">
                @foreach($rows as $ri => $row)
                @php $cells = array_map('trim', explode(',', $row['cells'] ?? '')); @endphp
                <tr class="{{ $striped && $ri % 2 === 1 ? 'bg-cream/50' : '' }} hover:bg-cream transition-colors duration-300">
                    @foreach($cells as $cell)
                    <td class="px-5 py-4 text-text-secondary">{{ $cell }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
