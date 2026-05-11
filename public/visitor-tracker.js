(function () {
    
    console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.content);
    function getDevice() {// Определяем устройство

        const ua = navigator.userAgent.toLowerCase();//Информация о браузере и устройстве в нижнем регистре для удобства проверки

        //Для того, чтобы ноутбуки с мобильным процессором не попали в категорию с телефонами

        const isWindows = /windows/i.test(ua);
        const isMac = /mac/i.test(ua) && !/iphone|ipad|ipod/i.test(ua);
        const isLinux = /linux/i.test(ua) && !/android/i.test(ua);


        if (isWindows || isMac || isLinux) {
            return 'desktop';
        }

        const isTablet = /tablet/i.test(ua) ||//Признаки планшета
            /ipad/i.test(ua) ||
            (/android/i.test(ua) && !/mobile/i.test(ua));

        // Признаки мобильного телефона

        const isMobile = /mobile/i.test(ua) && !isTablet;

        if (isTablet) return 'tablet';
        if (isMobile) return 'mobile';
        return 'desktop';
    }

    function getBrowser() {// Определяем браузер

        const ua = navigator.userAgent;

        if (/YaBrowser/i.test(ua)) return 'Yandex';
        if (/Edg/i.test(ua)) return 'Edge';
        if (/Opera|OPR/i.test(ua)) return 'Opera';
        if (/Chrome/i.test(ua)) return 'Chrome';
        if (/Firefox/i.test(ua)) return 'Firefox';
        if (/Safari/i.test(ua)) return 'Safari';
        if (/Trident|MSIE/i.test(ua)) return 'IE';
        
        return 'Other';
    }

    
    /*

    Пришлось перенести на сервер из за того, что браузеры запрещают JavaScript-запросам с одного сайта получать данные с другого сайта

    async function getGeoData() {// Получаем IP и город через бесплатный API

        try {

            const response = await fetch('https://ipapi.co/json/'); //Запрос к бесплатному API для получения геолокации по IP
            const data = await response.json();//Извлекаем IP и город из ответа

            return {

                ip: data.ip,
                city: data.city
            };

        } catch (error) {

            console.log('Geo IP error:', error);
            return { ip: null, city: null };
        }
    }*/

    let hasSent = false;

    async function sendVisitData() {// Отправляем данные на сервер

        if (hasSent) return;

        hasSent = true;

        //const geo = await getGeoData();// Получаем геоданные

        const data = {
           
            //ip: geo.ip,
            //city: geo.city,
            device: getDevice(),
            browser: getBrowser(),
            page_url: window.location.href
        };

        const token = document.querySelector('meta[name="csrf-token"]')?.content;

        fetch('/api/visit/track', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || ''
            },
            body: JSON.stringify(data)

        })
            .then(response => {
            console.log('Status:', response.status);
            return response.text();  // Получаем как текст
        })
            .then(text => {
            console.log('Response:', text);
            try {
                const json = JSON.parse(text);
                console.log('Success:', json);
            } catch (e) {
                console.error('Not JSON, probably HTML error page');
            }
        })
        .catch(err => console.log('Network error:', err));
    }

    if (document.readyState === 'loading') { // Запускаем отправку после загрузки страницы

        document.addEventListener('DOMContentLoaded', sendVisitData); //Ещё грузится, то ждём события DOMContentLoaded

    } else {

        sendVisitData();// Если страница уже загружена, отправляем данные сразу
    }

})();
