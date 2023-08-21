<div x-data="{ open: false }">


    <div {{ $attributes->merge(['class' => 'fixed inset-0 flex items-center justify-center z-50']) }} x-show="open" @click.away="open = false">
        <div class="bg-white p-8 rounded shadow-lg w-1/2">
            {{ $slot }}
        </div>
    </div>


    {{ $trigger }}
</div>
