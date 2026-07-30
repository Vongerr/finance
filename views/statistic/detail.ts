interface FinanceItem {
    date: string;
    money: number;
    comment: string;
}

interface DetailData {
    financeList: FinanceItem[];
}

function renderDetailTable(data: DetailData): void {
    console.log(data);

    const app = document.getElementById('app');
    if (!app) return;

    const table = document.createElement('table');
    table.className = 'modern-table';

    const thead = document.createElement('thead');
    thead.innerHTML = `
<tr>
    <th>Дата</th>
    <th>Сумма</th>
    <th>Комментарий</th>
</tr>`;
    table.appendChild(thead);

    const tbody = document.createElement('tbody');
    for (const item of data.financeList) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
<td>${item.date}</td>
<td><strong>${new Intl.NumberFormat('ru-RU', {style: 'currency', currency: 'RUB'}).format(item.money)}</strong></td>
<td>${item.comment || ''}</td>`;
        tbody.appendChild(tr);
    }
    table.appendChild(tbody);
    app.appendChild(table);
}

;(function () {
    const appEl = document.getElementById('app');
    if (appEl) appEl.innerHTML = '';

    const scriptEl = document.getElementById('detail-data') as HTMLScriptElement | null;
    if (scriptEl) {
        const raw: DetailData = JSON.parse(scriptEl.textContent || '{}');
        renderDetailTable(raw);
    }
})();
