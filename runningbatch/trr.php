<!DOCTYPE html>
<html>
<head>
  <title>Adding Active Class with jQuery</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .active {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>

    <a href="#">Item 1</a>
    <a href="#">Item 2</a>
    <a href="#">Item 3</a>


  <script>
    $(document).ready(function() {
      $("a").click(function() {
        // Remove active class from all links
        $("a").removeClass("active");
        
        // Add active class to the clicked link
        $(this).addClass("active");
      });
    });
  </script>
</body>
</html>

