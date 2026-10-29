
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BBPS Bill Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 650px">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">BBPS Bill Fetch</h4>
        </div>

        <div class="card-body">
            <form id="fetchForm">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Biller ID</label>
                    <input class="form-control" name="biller_id" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Customer Mobile</label>
                    <input class="form-control" name="mobile" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Customer Parameter Name</label>
                    <input
                        class="form-control"
                        name="param_name"
                        placeholder="Use the exact name supplied by the biller"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Customer Parameter Value</label>
                    <input class="form-control" name="param_value" required>
                </div>

                <button class="btn btn-primary" type="submit">Fetch Bill</button>
            </form>

            <pre id="result" class="mt-4 text-wrap"></pre>
        </div>
    </div>
</div>

<script>
document.getElementById('fetchForm').addEventListener('submit',
    async function (event) {
        event.preventDefault();

        const form = event.target;
        const result = document.getElementById('result');
        const data = new FormData(form);

        result.textContent = 'Submitting bill fetch request...';

        try {
            const response = await fetch('{{ route("bbps.fetch") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': data.get('_token'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    biller_id: data.get('biller_id'),
                    mobile: data.get('mobile'),
                    customer_params: [{
                        name: data.get('param_name'),
                        value: data.get('param_value')
                    }]
                })
            });

            const json = await response.json();
            result.textContent = JSON.stringify(json, null, 2);
        } catch (error) {
            result.textContent = 'Request failed. Please try again.';
        }
    }
);
</script>
</body>
</html>