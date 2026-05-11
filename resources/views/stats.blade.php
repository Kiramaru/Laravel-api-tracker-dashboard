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
            const response = await fetch('/stats/data', {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            const data = await response.json();
            
            document.getElementById('totalCount').innerText = data.total;
            document.getElementById('uniqueCount').innerText = data.unique_ips;
            
            // График по часам
            const hours = data.hourly.map(h => h.hour);
            const visits = data.hourly.map(h => h.unique_visits);
            
            if (hourlyChart) hourlyChart.destroy();
            hourlyChart = new Chart(document.getElementById('hourlyChart'), {
                type: 'line',
                data: { labels: hours, datasets: [{ label: 'Уникальные посещения', data: visits, borderColor: 'blue' }] }
            });
            
            // Круговая диаграмма по городам
            if (citiesChart) citiesChart.destroy();
            citiesChart = new Chart(document.getElementById('citiesChart'), {
                type: 'pie',
                data: { labels: data.cities.map(c => c.city), datasets: [{ data: data.cities.map(c => c.count) }] }
            });
        }
        
        loadData();
    </script>
</body>
</html>
