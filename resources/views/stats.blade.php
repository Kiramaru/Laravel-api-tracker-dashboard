<!DOCTYPE html>
<html>
<head>
    <title>Статистика посещений</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Статистика посещений</h1>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Выйти</button>
            </form>
        </div>
        
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-4 rounded shadow text-center">
                <div class="text-gray-500">Всего посещений</div>
                <div class="text-3xl font-bold" id="totalCount">0</div>
            </div>
            <div class="bg-white p-4 rounded shadow text-center">
                <div class="text-gray-500">Уникальных IP</div>
                <div class="text-3xl font-bold" id="uniqueCount">0</div>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl mb-4">Посещения по часам</h2>
                <canvas id="hourlyChart" height="200"></canvas>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl mb-4">Распределение по городам</h2>
                <canvas id="citiesChart" height="200"></canvas>
            </div>
        </div>

        <div class="bg-white p-4 rounded shadow mt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold flex items-center gap-2">
                    <span>🐱</span>
                    <span>Коллекция покемонов</span>
                </h2>
                <span class="text-gray-500">Всего: {{ count($pokemons) }}</span>
            </div>
        
            @if(count($pokemons) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($pokemons as $pokemon)
                        <div class="pokemon-card bg-gray-50 rounded-lg p-3 text-center border hover:shadow-lg">
                            @if($pokemon->image_url)
                                <img src="{{ $pokemon->image_url }}" 
                                     alt="{{ $pokemon->name }}"
                                     class="w-20 h-20 mx-auto">
                            @else
                                <div class="w-20 h-20 mx-auto bg-gray-200 rounded-full flex items-center justify-center">
                                    <span class="text-3xl">🎲</span>
                                </div>
                            @endif
                        
                            <h3 class="font-bold mt-2 capitalize">{{ $pokemon->name }}</h3>
                        
                            <div class="text-xs text-gray-500 mt-1">
                                <div>ID: {{ $pokemon->pokemon_id }}</div>
                                @if($pokemon->height)
                                    <div>Рост: {{ $pokemon->height / 10 }} м</div>
                                @endif
                                @if($pokemon->weight)
                                    <div>Вес: {{ $pokemon->weight / 10 }} кг</div>
                                @endif
                            </div>
                        
                            @if($pokemon->types)
                                <div class="mt-2">
                                    @foreach($pokemon->types as $type)
                                        <span class="inline-block text-xs px-2 py-1 rounded bg-blue-100 text-blue-800 m-0.5">
                                            {{ $type['type']['name'] ?? $type }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>😢 Покемоны пока не пойманы</p>
                    <p class="text-sm mt-2">Запустите команду: <code class="bg-gray-100 px-2 py-1 rounded">pokemon:fetch</code></p>
                </div>
            @endif
        </div>
    </div>

   <script>
    let hourlyChart, citiesChart;
    
    async function loadData() {
        try {
            console.log('Loading stats data...');
            
            const response = await fetch('/stats/data', {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
        
            if (!response.ok) {
                console.error('HTTP error:', response.status);
                return;
            }
        
            const text = await response.text();
            console.log('Raw response:', text);
            
            // Парсим JSON вручную
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('JSON parse error:', e);
                return;
            }
            
            console.log('Parsed data:', data);
            
            // Обновляем счетчики
            document.getElementById('totalCount').innerText = data.total || 0;
            document.getElementById('uniqueCount').innerText = data.unique_ips || 0;
        
            // Обрабатываем данные по часам
            const hourlyData = data.hourly || [];
            const hours = hourlyData.map(h => h.hour || '');
            const visits = hourlyData.map(h => h.unique_visits || 0);
            
            console.log('Hourly data:', hours, visits);
            
            // График по часам
            const hourlyCanvas = document.getElementById('hourlyChart');
            if (hourlyCanvas && hours.length > 0) {
                if (hourlyChart) hourlyChart.destroy();
                hourlyChart = new Chart(hourlyCanvas, {
                    type: 'line',
                    data: { 
                        labels: hours, 
                        datasets: [{ 
                            label: 'Уникальные посещения', 
                            data: visits, 
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3
                        }] 
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true
                    }
                });
                console.log('Hourly chart created');
            }
        
            // Обрабатываем данные по городам
            const citiesData = data.cities || [];
            console.log('Cities data:', citiesData);
            
            const citiesCanvas = document.getElementById('citiesChart');
            if (citiesCanvas && citiesData.length > 0) {
                if (citiesChart) citiesChart.destroy();
                citiesChart = new Chart(citiesCanvas, {
                    type: 'pie',
                    data: { 
                        labels: citiesData.map(c => c.city || 'Unknown'), 
                        datasets: [{ 
                            data: citiesData.map(c => c.count || 0),
                            backgroundColor: ['rgb(59, 130, 246)', 'rgb(34, 197, 94)', 'rgb(249, 115, 22)', 'rgb(168, 85, 247)', 'rgb(236, 72, 153)']
                        }] 
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
                console.log('Cities chart created');
            }
            
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }
    
    // Запускаем после загрузки страницы
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadData);
    } else {
        loadData();
    }
</script>
</body>
</html>
