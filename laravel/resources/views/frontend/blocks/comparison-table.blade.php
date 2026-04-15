@php $rows = $content['rows'] ?? []; @endphp
@if(!empty($rows))
<div class="max-w-4xl mx-auto px-6 lg:px-12 py-16">
    @if(!empty($content['heading']))
    <div class="text-center mb-12 reveal">
        <h2 class="text-h3 font-heading font-bold text-forest">{{ $content['heading'] }}</h2>
    </div>
    @endif
    <div class="overflow-x-auto -mx-4 px-4 reveal">
        <table class="w-full border-collapse border border-stone">
            <thead>
                <tr>
                    <th class="text-left text-sm font-semibold text-text-secondary py-4 px-6 border-b-2 border-stone w-1/3 bg-cream"></th>
                    <th class="text-center text-sm font-bold text-forest py-4 px-6 border-b-2 border-forest bg-forest/5">{{ $content['col1_title'] ?? 'Option A' }}</th>
                    <th class="text-center text-sm font-bold text-ink py-4 px-6 border-b-2 border-stone bg-cream">{{ $content['col2_title'] ?? 'Option B' }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $i => $row)
                <tr class="{{ $i % 2 === 0 ? 'bg-cream/50' : 'bg-white' }} hover:bg-cream transition-colors duration-300">
                    <td class="text-sm font-medium text-ink py-4 px-6 border-b border-stone">{{ $row['feature'] ?? '' }}</td>
                    <td class="text-sm text-center py-4 px-6 border-b border-stone bg-forest/5">
                        @if(in_array(strtolower($row['col1'] ?? ''), ['yes', '✓', 'true']))
                        <i data-lucide="check" class="w-5 h-5 text-forest mx-auto"></i>
                        @elseif(in_array(strtolower($row['col1'] ?? ''), ['no', '✕', 'false', '']))
                        <i data-lucide="x" class="w-5 h-5 text-stone mx-auto"></i>
                        @else
                        <span class="text-text-secondary">{{ $row['col1'] ?? '' }}</span>
                        @endif
                    </td>
                    <td class="text-sm text-center py-4 px-6 border-b border-stone">
                        @if(in_array(strtolower($row['col2'] ?? ''), ['yes', '✓', 'true']))
                        <i data-lucide="check" class="w-5 h-5 text-forest mx-auto"></i>
                        @elseif(in_array(strtolower($row['col2'] ?? ''), ['no', '✕', 'false', '']))
                        <i data-lucide="x" class="w-5 h-5 text-stone mx-auto"></i>
                        @else
                        <span class="text-text-secondary">{{ $row['col2'] ?? '' }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
