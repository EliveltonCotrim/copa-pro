<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        <link rel="stylesheet" href="{{ route('championship.register', $getState()->slug) }}">
    </div>
</x-dynamic-component>
