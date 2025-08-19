<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
</head>
<body style="display:flex; align-items:center; justify-content:center; height:100vh; font-family:sans-serif;">
    <div style="text-align:center;">
        <h1 style="color:green; font-size:2rem;">Payment Successful!</h1>
        <p>You will be redirected to the items page shortly...</p>
    </div>

 <script type="module">
    setTimeout(() => {
        window.location.href = "{{ route('cashier.index') }}";
    }, 3000);
</script>

</body>
</html>
