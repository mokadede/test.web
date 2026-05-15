@props(['data'])

@php
    $content = is_string($data) ? json_decode($data, true) : $data;
    $blocks = $content['blocks'] ?? [];
@endphp

<div class="editorjs-content max-w-none">
    <style>
        .editorjs-content h1 { font-size: 2.25rem; font-weight: 800; margin-top: 2rem; margin-bottom: 1rem; line-height: 1.2; }
        .editorjs-content h2 { font-size: 1.875rem; font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.75rem; line-height: 1.3; }
        .editorjs-content h3 { font-size: 1.5rem; font-weight: 600; margin-top: 1.25rem; margin-bottom: 0.5rem; }
        .editorjs-content p { margin-bottom: 1.25rem; font-size: 1.125rem; line-height: 1.75; opacity: 0.9; }
        .editorjs-content ul { list-style-type: disc; margin-left: 1.5rem; margin-bottom: 1.25rem; }
        .editorjs-content ol { list-style-type: decimal; margin-left: 1.5rem; margin-bottom: 1.25rem; }
        .editorjs-content li { margin-bottom: 0.5rem; }
        .editorjs-content img { border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); margin: 2rem auto; }
        .editorjs-content figure { margin: 2rem 0; }
        .editorjs-content figcaption { text-align: center; font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem; font-style: italic; }
    </style>
    @foreach ($blocks as $block)
        @switch($block['type'])
            @case('header')
                @php $level = $block['data']['level'] ?? 2; @endphp
                <h{{ $level }}>{!! $block['data']['text'] !!}</h{{ $level }}>
                @break

            @case('paragraph')
                <p>{!! $block['data']['text'] !!}</p>
                @break

            @case('list')
                @if (($block['data']['style'] ?? 'unordered') === 'ordered')
                    <ol class="list-decimal ml-6">
                        @foreach ($block['data']['items'] as $item)
                            <li>{!! $item !!}</li>
                        @endforeach
                    </ol>
                @else
                    <ul class="list-disc ml-6">
                        @foreach ($block['data']['items'] as $item)
                            <li>{!! $item !!}</li>
                        @endforeach
                    </ul>
                @endif
                @break

            @case('image')
                <figure class="my-6">
                    <img src="{{ $block['data']['file']['url'] }}" alt="{{ $block['data']['caption'] ?? '' }}" class="rounded-lg shadow-md mx-auto">
                    @if (!empty($block['data']['caption']))
                        <figcaption class="text-center text-sm text-gray-500 mt-2 italic">{{ $block['data']['caption'] }}</figcaption>
                    @endif
                </figure>
                @break

            @default
                {{-- Handle other block types if needed --}}
        @endswitch
    @endforeach
</div>
