<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MPESA STK</title>
    @vite('resources/sass/app.scss')
</head>

<body>
    <div class="container">
        <h4 class="mt-4 text-center">MPESA STK SIMULATOR</h4>
        <div class="row mt-4">
            <div class="mx-auto col-sm-8">
            <div class="card">
                <div class="card-header">
                    Obtain access token
                </div>
                <div class="card-body">
                    <h5 id="access_token"></h5>
                    <button id="getAccessToken" class="btn btn-primary">Request access token</button>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    Register URls
                </div>
                <div class="card-body">
                    <button class="btn btn-primary">Register URls</button>
                </div>
            </div>
             <div class="card my-4">
                <div class="card-header">
                   Simulate Transactions
                </div>
                <div class="card-body">
                    <form action="">
                        @csrf
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" class="form-control">
                        </div>
                         <div class="form-group my-2">
                            <label for="account">Account</label>
                            <input type="text" name="account" class="form-control">
                        </div>
                        <button class="btn btn-primary">Simulate</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
    @vite('resources/js/app.js')
    <script>
        document.getElementById("getAccessToken").addEventListener('click', (e) => {
            e.preventDefault()
            axios.post('/get-access-token',{})
            .then((response) => {
                console.log(response.data)
                document.getElementById("access_token").innerHTML = response.data.access_token
            })
            .catch((error) => {
                console.log(error)
            })
        })
    </script>
</body>

</html>
