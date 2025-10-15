@props(['name', 'image'])

<div
  style="background-image: url({{ $image }});
         background-size: cover; background-position: center;"
  {{ $attributes->merge(['class' => 'ms-2 mb-3', 'id' => 'image-preview']) }}
>
  <label for="image-upload" id="image-label">Choose File</label>
  <input type="file" name="{{ $name }}" id="image-upload" />
</div>
