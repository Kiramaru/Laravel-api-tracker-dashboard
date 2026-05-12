/** Фильтрация полей на странице по значению выбранного элемента списка
 * 
 *  Другие варианты:
 * 
 *      jQuery
 *    - Задание решается JS за 15 строк
 *    - Подключение библиотек увеличивает размер и время загрузки
 *    - Не обосновано сложностью задачи
 * 
 *      Скрытие через CSS классы (.hidden) с последующей стилизацией
 *    - Эквивалентно display: none, но требует дополнительного CSS
 * 
 *      Перерисовка через innerHTML
 *    - Теряет обработчики событий на полях, значения полей
 *    - Некорректно для страниц с формами
 *
 * */

let nums = str => (str?.match(/\d+/g) || []).join('');//Функция, которая возвращает все цифры из строки

let filter = () => {

    let p = document.querySelectorAll('p');

    let target = Array.from(p).find(p => p.querySelector('select[name="type_val"]'));//Находим элемент p, в котором есть select для выбора типа

    if (!target) return;//Если такого элемента нет, прекращаем выполнение функции

    let val = target.querySelector('select[name="type_val"]')?.value;//Значение выбранного типа

    Array.from(p).forEach(p => {
        if (p === target) return; // Пропускаем элемент, в котором находится select

        let input = p.querySelector('input');//Находим input внутри элемента p
        let match = input?.name ? nums(input.name).includes(val) : false;//Проверяем, содержит ли имя input выбранное значение типа (после извлечения цифр)

        p.style.display = match ? '' : 'none';//Если совпало, показываем элемент, иначе скрываем
    });

};

filter();//Первый запуск функции для отображения полей при загрузке страницы

let select = document.querySelector('select[name="type_val"]');//Находим элемент select для выбора типа

if (select) {

    select.addEventListener('change', filter);//При изменении значения в select, вызываем функцию фильтрации

}