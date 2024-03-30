'use strict';

/**
 * Отправка даннах POST /register
 */
function register() {
    const api = 'register';
    request('/' + api, 'POST', getFormData(api));
}

/**
 * Отправка даннах POST /authorize
 */
function authorize() {
    const api = 'authorize';
    request('/' + api, 'POST', getFormData(api));
}

/**
 * Отправка даннах GET /feed c Authorization заголовком
 */
function feed() {
    const token = document.getElementById('access_token').value;
    const api = 'feed';
    request('/' + api, 'GET', null, token);
}

/**
 * Функция отправки
 *
 * @param url
 * @param method
 * @param data
 * @param access_token
 * @returns {Promise<unknown>}
 */
function ajax(url, method, data = {}, access_token = false) {
    return new Promise(function(resolve, reject) {
        let xhr = new XMLHttpRequest();

        xhr.open(method, url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        if (access_token) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                let response = {
                    json: xhr.responseText,
                    code: xhr.status,
                };
                resolve(response);
            }
        };

        xhr.onerror = function() {
            reject(new Error('Network error'));
        };

        xhr.send(JSON.stringify(data));
    });
}

/**
 * Отправка запроса с отображением результата ответа
 *
 * @param url
 * @param method
 * @param data
 * @param token
 */
function request(url, method, data, token = false) {
    loader();
    showApiMethod(url);
    ajax(url, method, data, token)
        .then((response) => {
            showResponseJSON(response.json);
            showResponseCode(response.code);
            loader(false);
        })
        .catch(response => {
            loader(false);
        });
}

/**
 * Формирует объект данных для тела запроса
 *
 * @param formId
 * @returns {{}}
 */
function getFormData(formId) {
    const form = document.getElementById(formId);
    const data = {};

    new FormData(form).forEach((value, key) => {
        data[key] = value;
    })

    return data;
}

/**
 * Отображает тело ответа сервера
 *
 * @param data
 */
function showResponseJSON(data) {
    document.getElementById('response').value = data;
}

/**
 * Отображает код ответа сервера
 *
 * @param code
 */
function showResponseCode(code) {
    document.getElementById('response_code').innerHTML = code;
}

/**
 * Отображает метод запроса к серверу
 *
 * @param method
 */
function showApiMethod(method) {
    document.getElementById('send_method').innerHTML = method;
}

/**
 * Включает/выключает лоадер
 *
 * @param show
 */
function loader(show = true) {
    const block = document.getElementById('controls');
    if (show) {
        block.classList.add('loading');
    } else {
        block.classList.remove('loading');
    }
}