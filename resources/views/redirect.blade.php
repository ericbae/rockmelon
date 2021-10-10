<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script
      src="https://code.jquery.com/jquery-3.4.1.min.js"
      integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
      crossorigin="anonymous"></script>
    
    <script type="text/javascript">
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN" : "{{ csrf_token() }}"
        }
      });
    </script>

    <script type="text/javascript">
      $(document).ready(function() {
        $.ajax({
          url : "/auth/redirect-url",
          type : "POST",
          data: {
            url:encodeURI(window.location.href)
          },
          success: function(data) {
            window.location.href = "/auth/login";
          }
        });
      });
    </script>
  </head>
  <body>

  </body>
</html>