<div class="select">
    <select name="{{ $name }}">
        <option value="" {{ ! old($name) && ! isset($value) ? 'selected' : '' }} disabled>{{ $placeholder or '–' }}</option>
        @foreach($options as $option)
            @if (old($name))
                <option value="{{ $option->id}}" {{ old($name) === $option->name ? 'selected': '' }}>{{ $option->name }}</option>
            @else
                <option value="{{ $option->id }}" {{ isset($value) && $value === $option->id ? 'selected': '' }}>{{ $option->name  }}</option>
            @endif
        @endforeach
    </select>
</div>