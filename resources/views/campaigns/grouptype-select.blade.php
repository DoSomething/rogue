<div class="select">
    <select name="{{ $name }}">
        <option value="" {{ ! old($value) && empty($value) ? 'selected' : '' }} disabled>{{ $value or '–' }}</option>
        @foreach($options as $option)
            @if (old($value))
                <option value="{{ $option->id}}" {{ old($value) === $option->id? 'selected': '' }}>{{ $option->name }}</option>
            @else
                <option value="{{ $option->id }}" {{ empty($value) && $value === $option->id ? 'selected': '' }}>{{ $option->name  }}</option>
            @endif
        @endforeach
    </select>
</div>