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

  /** sweetalert2 init */
  $(function () {
    $('.delete-item').on('click', function (e) {
      e.preventDefault();

      const url = $(this).attr('href');

      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: 'DELETE',
            url: url,
            data: {
              _token: '{{ csrf_token() }}'
            },
            success: function(response) {
              if(response.status === 'success') {
                window.location.reload()
              }
            },
            error: function(xhr, status, error) {
              console.log(error);
            }
          })
        }
      });
    })
  })
</script>
