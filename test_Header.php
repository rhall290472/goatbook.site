
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Test Goatbook.site email</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  
<style>
.info .info-item+.info-item {
  margin-top: 40px;
}

.info .info-item i {
  color: var(--contrast-color);
  background: var(--accent-color);
  font-size: 20px;
  width: 44px;
  height: 44px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50px;
  transition: all 0.3s ease-in-out;
  margin-right: 15px;
}

.info .info-item h3 {
  padding: 0;
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 5px;
}

.info .info-item p {
  padding: 0;
  margin-bottom: 0;
  font-size: 14px;
}

.info .php-email-form {
  height: 100%;
}

.info .php-email-form .error-message {
  display: none;
  background: #df1529;
  color: #ffffff;
  text-align: left;
  padding: 15px;
  margin-bottom: 24px;
  font-weight: 600;
}

.info .php-email-form .sent-message {
  display: none;
  color: #ffffff;
  background: #059652;
  text-align: center;
  padding: 15px;
  margin-bottom: 24px;
  font-weight: 600;
}

.info .php-email-form .loading {
  display: none;
  background: var(--background-color);
  text-align: center;
  padding: 15px;
  margin-bottom: 24px;
}

.info .php-email-form .loading:before {
  content: "";
  display: inline-block;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  margin: 0 10px -6px 0;
  border: 3px solid var(--accent-color);
  border-top-color: var(--background-color);
  animation: animate-loading 1s linear infinite;
}

.info .php-email-form input[type=text],
.info .php-email-form input[type=email],
.info .php-email-form textarea {
  font-size: 14px;
  padding: 10px 15px;
  box-shadow: none;
  border-radius: 0;
  color: var(--default-color);
  background-color: color-mix(in srgb, var(--background-color), transparent 50%);
  border-color: color-mix(in srgb, var(--default-color), transparent 80%);
}

.info .php-email-form input[type=text]:focus,
.info .php-email-form input[type=email]:focus,
.info .php-email-form textarea:focus {
  border-color: var(--accent-color);
}

.info .php-email-form input[type=text]::placeholder,
.info .php-email-form input[type=email]::placeholder,
.info .php-email-form textarea::placeholder {
  color: color-mix(in srgb, var(--default-color), transparent 70%);
}

.info .php-email-form button[type=submit] {
  color: var(--contrast-color);
  background: var(--accent-color);
  border: 0;
  padding: 10px 36px;
  transition: 0.4s;
  border-radius: 50px;
}

.info .php-email-form button[type=submit]:hover {
  background: color-mix(in srgb, var(--accent-color), transparent 20%);
}

@keyframes animate-loading {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>