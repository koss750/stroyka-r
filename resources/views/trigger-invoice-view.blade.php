{{-- resources/views/trigger-invoice-view.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
</head>
<body onload="document.forms['redirectForm'].submit();">
    <form name="redirectForm" action="{{ url('/smeta') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="data" value="{{ session('invoiceData') }}">
    </form>
    <script>
        window.onload = function() {
            document.forms['redirectForm'].submit();
        }
    </script>
</body>
</html>
