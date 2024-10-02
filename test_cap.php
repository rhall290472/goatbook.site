<!DOCTYPE html>
<html>

<head>
  <script src="https://www.google.com/recaptcha/enterprise.js?render=6LdEplUqAAAAAN3lg1N-52Ym_5UBPHbYfYw28ik9"></script>

  <!-- Replace the variables below. -->
  <script>
    function onSubmit(token) {
      document.getElementById("demo-form").submit();
    }
  </script>

</head>

<body>



  <button class="g-recaptcha"
    data-sitekey="6LdEplUqAAAAAN3lg1N-52Ym_5UBPHbYfYw28ik9"
    data-callback='onSubmit'
    data-action='submit'>
    Submit
  </button>
  
</body>

</html>