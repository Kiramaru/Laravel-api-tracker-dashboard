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

    let target = Array.from(p).find(p => p.querySelector('select[name="type_val"]'));

    let val = target?.querySelector('select[name="type_val"]')?.value;//Значение типа



    Array.from(p).filter(p => p !== target).forEach(p => {//Все элементы p, кроме того, в котором выбор типа

        let text = p.querySelector('input[type="text"]');//Элемент с полем
        let button = p.querySelector('input[type="button"]');//Элемент с кнопкой

        let match = (text && nums(text.previousSibling?.nodeValue) === val) ||
            (button && nums(button.value) === val);
        //Если кнопка, то проверка совпадения цифрам в тексте внутри тега p, если кнопка, то по значению 

        p.style.display = match ? '' : 'none';
    });

};

filter();//Первый запуск функции для отображения полей при загрузке страницы

let select = document.querySelector('select[name="type_val"]');//Находим элемент select для выбора типа

if (select) {

    select.addEventListener('change', filter);//При изменении значения в select, вызываем функцию фильтрации

}