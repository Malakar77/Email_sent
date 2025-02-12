document.addEventListener('DOMContentLoaded', (e)=>{

    const getParamConst = getParam('us');

    fetch('base/base.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            'value' : getParamConst,
        }),
    }).then(response => response.json())
        .then(data => {

            document.querySelector('button').dataset.id = data;

        }).catch(error => {

    });
})

const but = document.querySelector('button');

but.addEventListener('click', (e)=>{
    fetch('src/unsub.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            'value' : but.dataset.id,
        }),
    }).then(response => response.json())
        .then(data => {

            if (data.success){
                document.querySelector('.text').innerText = "Вы успешно отписались от рассылки!";
                document.querySelector('button').remove();
            }

        }).catch(error => {
            console.error('error');
    });
})


function getParam(key)
{
    try {
        // Получаем текущий URL
        const url = window.location.href;
        // Проверяем, содержит ли URL символ "?"
        if (!url.includes('?')) {
            return null; // Если параметров нет, возвращаем null
        }
        // Извлекаем строку запроса
        const queryString = url.split('?')[1];
        // Проверяем, есть ли после "?" параметры
        if (!queryString) {
            return null; // Если параметры отсутствуют, возвращаем null
        }
        // Создаем объект URLSearchParams
        const params = new URLSearchParams(queryString);
        // Возвращаем значение параметра по ключу
        return params.get(key);
    } catch (error) {
        console.error('Ошибка при разборе параметров URL:', error);
        return null; // Возвращаем null в случае любой ошибки
    }
}
