@props(['label', 'name', 'type' => 'text', 'id' => null, 'hint' => null, 'textarea' => false])
@php
$id = $id ?? 'field-'.str_replace(['[', ']', '.'], '-', $name);
$error = $errors->first($name);
$describedBy = collect([$hint ? $id.'-hint' : null, $error ? $id.'-error' : null])->filter()->implode(' ');
$inputClass = 'block w-full rounded-xl border bg-white px-4 py-3 font-body text-base text-ink placeholder:text-muted transition duration-200 ease-out focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary disabled:opacity-50 '
  . ($error ? 'border-ink' : 'border-line');
@endphp
<div {{ $attributes->only('class')->class('flex flex-col gap-2') }}>
  <label for="{{ $id }}" class="font-body text-sm font-medium text-ink">{{ $label }}</label>

  @if ($textarea)
    <textarea
      id="{{ $id }}" name="{{ $name }}" rows="5"
      @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
      @if ($error) aria-invalid="true" @endif
      {{ $attributes->except('class')->class($inputClass) }}
    >{{ old($name, $slot->isNotEmpty() ? (string) $slot : '') }}</textarea>
  @else
    <input
      id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
      @unless (in_array($type, ['password', 'file'])) value="{{ old($name, $attributes->get('value')) }}" @endunless
      @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
      @if ($error) aria-invalid="true" @endif
      {{ $attributes->except(['class', 'value'])->class($inputClass) }}
    >
  @endif

  @if ($hint && ! $error)
    <p id="{{ $id }}-hint" class="text-sm text-muted">{{ $hint }}</p>
  @endif

  @if ($error)
    <p id="{{ $id }}-error" class="flex items-center gap-2 text-sm font-medium text-ink" role="alert">
      <svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
      </svg>
      {{ $error }}
    </p>
  @endif
</div>
