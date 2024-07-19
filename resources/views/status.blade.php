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
        <h4 class="mt-4 text-center">Transaction Status </h4>
        <div class="row my-4">
            <div class="mx-auto col-sm-8">
             <div class="card">
                <div class="card-header">
                   Transaction Status 
                </div>
                <div class="card-body">
                    <div id="transactionresponse" class="text-center mb-1"></div>
                    <form action="" id="b2c-form">
                        @csrf
                        <div class="form-group  ">
                            <label for="transactionid">Transaction ID</label>
                            <input id="transactionid" type="text" name="transactionid" class="form-control my-2">
                        </div>
                        <button id="transactionStatus" type="button" class="btn btn-primary mt-2 w-100">Check Transaction</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
    @vite('resources/js/app.js')
</body>

</html>
