<!DOCTYPE html>
<html>
<head>
    <title>Tickets pour la commande {{ $order->order_number }}</title>
</head>
<body>
    <h1>Tickets pour la commande {{ $order->order_number }}</h1>

    @foreach($tickets as $ticket)
        <div class="ticket">
            <h2>Ticket #{{ $ticket->ticket_number }}</h2>
            <p>Événement : {{ $ticket->event_name }}</p>
            <p>Date : {{ $ticket->event_date }}</p>
            <p>Lieu : {{ $ticket->event_location }}</p>
            <!-- Ajoutez d'autres détails du ticket ici -->
        </div>
    @endforeach
</body>
</html>
