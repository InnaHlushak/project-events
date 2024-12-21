<div style="font-family: DejaVu Sans"> 
<h2>Звіт "Статистика популярності подій"</h2>

<table style="width: 100%; border-collapse: collapse; margin-top: 20px; border: 1px solid #ddd; font-family: DejaVu Sans">
    <thead>
        <tr>
            <th style="padding: 8px; text-align: left;">№</th>
            <th style="padding: 8px; text-align: left;">Подія</th>
            <th style="padding: 8px; text-align: left;">Категорія</th>
            <th style="padding: 8px; text-align: left;">Дата</th>
            <th style="padding: 8px; text-align: left;">Популярність</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($events as $index => $event)
        <tr>
            <td style="padding: 8px;">{{ $index + 1 }}</td>
            <td style="padding: 8px;">{{ $event->name }}</td>
            <td style="padding: 8px;">{{ $event->category->name }}</td>
            <td style="padding: 8px;">{{ $event->deadline->format('d-m-Y H:i')}}</td>
            <td style="padding: 8px;">{{ $event->popularity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</di>