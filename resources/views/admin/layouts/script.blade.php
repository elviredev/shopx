<script>
  /** tinymce init */
  tinymce.init({
    selector: 'textarea#editor',
    height: 500,
    plugins: [
      'advlist', 'lists', 'link', 'image', 'charmap', 'preview',
      'code', 'fullscreen', 'table', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
      'bold italic backcolor | alignleft aligncenter ' +
      'alignright alignjustify | bullist numlist outdent indent | ' +
      'removeformat | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
  });
</script>
