function myRequest(obj){
   let demoObj = {
        type: 'get/post',
        params: {
            r: 1,
            a: 2
        },
        data: {},
    }

    let url = "";
    if(obj.url !== undefined && obj.url.indexOf('?') === -1){
        url = obj.url;
    }
    return new Promise((resolve, reject) => {
        if (obj.params !== undefined) {
            url = url + "?" +Qs.stringify(obj.params)
        }
        $.ajax(url, {
            type: obj.type || 'get',
            data: obj.data || {},
            dataType: 'json',
            success: function (response) {
                resolve(response)
            },
            error: function (err) {
                reject(err)
            }
        })
    })
}

function getQuery(name) {
    var reg = new RegExp("(^|&|\\?)" + name + "=([^&]*)(&|$)");
    var r = window.location.href.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}

const _csrf = '';
const _scriptUrl = '';

const common = axios.create({
    transformRequest: [function (data, headers) {
        if (data instanceof FormData) {
            data.append('_csrf', _csrf);
        } else {
            if (data && !data['_csrf']) {
                data['_csrf'] = _csrf;
            }
            data = Qs.stringify(data);
        }
        return data;
    }],
});

window.request = common;

common.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
common.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

common.interceptors.request.use(function (config) {
    return config;
}, function (error) {
    return Promise.reject(error);
});

common.interceptors.response.use(function (response) {
    if (response.data && typeof response.data.code !== 'undefined') {
        if (response.data.code !== 200) {
            if (app) {
                app.$alert(response.data.msg, '错误');
            } else {
                console.log(response.data);
            }
        } else {
            return response;
        }
    } else {
        return Promise.reject(response);
    }
}, function (error) {
    if (app) {
        app.$alert(error.data.msg, '错误');
    } else {
        console.log(error.data);
    }
    return Promise.reject(error);
});

Vue.use({
    install(Vue, options) {
        Vue.prototype.$request = request;
    }
});
