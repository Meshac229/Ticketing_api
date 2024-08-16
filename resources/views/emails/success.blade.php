<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Success Page</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Demande d'API envoyée avec succès</div>
                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif
                        <p>Merci pour votre demande d'accès à l'API TIKERAMA.</p>
                        <p>Nous avons bien reçu votre demande.</p>
                        <p>Veuillez vérifier votre email pour obtenir votre clé API et les instructions d'utilisation.</p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>



