<div class="container">
    <h1>Квиток № {{ $number }}</h1>
    <div>
        <h2>Вітаємо {{ $user->name }}!</h2>
        <p>Вас запрошено на подію:</p>
        <h2>{{ $event->name }}</h2>
        <p><strong>Дата та час:</strong> {{ $event->deadline->format('d-m-Y H:i')}} </p>
        <p><strong>Місце проведення:</strong> {{ $event->venue  }}</p>
        <br>
        <p><strong>Тип квитка:</strong> {{ $typeTicket }}</p>
        <p><strong>Вартість квитка:</strong> {{ $finalPrice }} грн.</p>
        <br>
        <p>З нетерпінням чекаємо на Вас!</p>
    </div>    
</div>