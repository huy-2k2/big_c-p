<div class="flex flex-col gap-y-1">
    <input name={{ $name ?? "disable_input" }} type="text" id="disabled-input" aria-label="disabled input" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 pointer-events-none" value={{ $value }}>
    @error($name)
        @include('components.text_notice', ['text' => $message])
    @enderror
</div>
