# M.A.R.I.A - API de Gráficos (admin/maria)

Carpeta con endpoints para que M.A.R.I.A consulte e interprete los datos de los gráficos.

Endpoints:
- `data.php` (GET): devuelve JSON para distintos tipos de datos.
  - params: `type` (sales_between|daily_series|monthly_year|top_products|category_sales|inputs|compare_years|yearly_series|views_top), `period` (day|week|month|year), `date` (YYYY-MM-DD), `year`, `limit`.
- `interpret.php` (POST): recibe JSON { question, type, period, date, year } y devuelve una interpretación en español. Intenta usar OpenRouter si `maria_config.php` tiene `OPENROUTER_API_KEY`.

Cómo usar desde JavaScript (ejemplo):

```js
fetch('admin/maria/data.php?type=top_products&period=month&date=2025-11-01')
  .then(r=>r.json()).then(console.log);

fetch('admin/maria/interpret.php', { method:'POST', body: JSON.stringify({question:'¿Qué destaca?', type:'top_products', period:'month', date:'2025-11-01'}), headers:{'Content-Type':'application/json'} })
  .then(r=>r.json()).then(console.log);
```

Notas:
- Asegúrate de que `admin/includes/session.php` y `$pdo` estén disponibles (esta carpeta fue diseñada para ser usada desde `admin/`).
- `maria_config.php` en la raíz se usa para configuración y para la clave de OpenRouter si quieres respuestas generadas por IA.
