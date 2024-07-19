<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daraja</title>
    @vite('resources/sass/app.scss')
</head>

<body>
    <div class="container">
        <h4 class="mt-4 text-center"> STK TRANSACTION</h4>
        <div class="row my-4">
            <div class="mx-auto col-sm-8">
             <div class="card">
                <div class="card-header">
                   STK Simulation
                </div>
                <div class="card-body">
                    <div id="response" class="text-center mb-1"></div>
                    <form action="" id="b2c-form">
                        @csrf
                        <div class="form-group my-2">
                            <label for="phone">Phone</label>
                            <input id="phone" type="number" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input id="amount" type="number" name="amount" class="form-control">
                        </div>
                         
                        <div class="form-group my-2">
                            <label for="account">Account</label>
                            <input id="account" type="text" name="account" class="form-control">
                        </div>
                        <button id="simulatestk" type="button" class="btn btn-primary w-100">STK Simulate</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
    @vite('resources/js/app.js')
</body>

</html>
