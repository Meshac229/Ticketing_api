<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demande d'accès à l'API</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://img.freepik.com/vecteurs-libre/forme-ronde-geometrique-abstraite-conception-fond-bleu_1017-42785.jpg?t=st=1723662997~exp=1723666597~hmac=41ba3ae9be10a1b4c5fd53bff8bffaafa5e1b95e12e535f7bab96adc6bc9ee61&w=740');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .content-container {
            color: white;
            padding: 2rem;
            border-radius: 8px;
        }
        .text-column {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 1rem;
            border-radius: 8px;
            color: black;
        }
        .first-column {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .first-column img {
        max-width: 100%;
        height: auto;
        margin-bottom: 1rem;
    }
    </style>
</head>
<body>
    <div class="container mt-5 content-container">
        <div class="row">
            <div class="col-md-6 first-column">
                <h3>Bienvenue chez</h3>
                <img src="{{ asset('images/logo-tikerama.png') }}" alt="TIKERAMA LOGO">
                <p>Notre API vous permet d'intégrer facilement nos services dans vos applications.</p>
                <ul>
                    <li>Accès rapide aux données</li>
                    <li>Intégration simple</li>
                    <li>Support technique dédié</li>
                    <li>Documentation complète</li>
                </ul>
                <p>Remplissez le formulaire ci-contre pour commencer votre parcours avec notre API.</p>
            </div>
            <div class="col-md-6 text-column">
                <form action="{{ route('api.request') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="first_name">Prénom:</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Nom:</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="company">Entreprise:</label>
                        <input type="text" id="company" name="company" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="city">Ville:</label>
                        <input type="text" id="city" name="city" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Adresse:</label>
                        <input type="text" id="address" name="address" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer la demande</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
