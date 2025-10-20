@props(['name', 'image' => null])

<div
  id="{{ $imagePreviewId }}"
  style="
    background-image: url({{ $image }});
    background-size: cover;
    background-position: center;
   "

  {{ $attributes->class([
      'mb-3 image-preview',
      // ajoute "ms-2" seulement si "ms-0" n’est pas présent
      'ms-2' => !str_contains($attributes->get('class') ?? '', 'ms-0')
  ]) }}
>
  <label for="{{ $imageUploadId }}" id="{{ $imageLabelId }}">Choose File</label>
  <input type="file" name="{{ $name }}" id="{{ $imageUploadId }}" />
</div>
