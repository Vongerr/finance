<?php

/* @var $data array */
?>

<div id="app"></div>

<script>
    let data: <?php echo json_encode($data, JSON_UNESCAPED_UNICODE); ?>;

    // Генерация DOM
    const app = document.getElementById('app')!;
    const table = document.createElement('table');

    // Добавляем классы для CSS
    table.className = 'modern-table';

    // Заголовок
    const thead = document.createElement('thead');
    thead.innerHTML = `
<tr>
    <th>Дата</th>
    <th>Цена</th>
    <th>Комментарий</th>
</tr>`;
    table.appendChild(thead);

    // Тело
    const tbody = document.createElement('tbody');
    data.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
<td>${item.id}</td>
<td><strong>${item.product}</strong></td>
<td>${new Intl.NumberFormat('ru-RU', {style: 'currency', currency: 'RUB'}).format(item.price)}</td>
<td>${item.inStock ? '✅ Есть' : '❌ Нет'}</td>
`;
        tbody.appendChild(tr);
    });
    table.appendChild(tbody);
    app.appendChild(table);

</script>

<style>
    .modern-table {
    width: 100%;
    max-width: 600px;
    border-collapse: separate;
    border-spacing: 0;
    font-family: 'Segoe UI', sans-serif;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
    }

    .modern-table thead tr {
    background: linear-gradient(135deg, #6C63FF, #3F3D9E);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    }

    .modern-table th, .modern-table td {
    padding: 14px 20px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
    }

    /* Эффект зебры */
    .modern-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
    }

    /* Hover эффект */
    .modern-table tbody tr:hover {
    background-color: #e9ecef;
    transition: background 0.2s;
    }
</style>
