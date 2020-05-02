//我们可以将所有配置信息存放到这个文件，
// 本应用中将使用这个文件来存储基于环境的 API URL：
// 在生产环境中，将引用生产环境 URL；
// 在开发环境中，将引用开发环境 URL。
// 随着开发进程的推进，后面还会使用该文件存放更多信息。


/**
 * Defines the API route we are using.
 * */

var api_url = '';

var gaode_maps_js_api_key = 'b1cac56d3a90eebab55af0f46b94437f';

switch (process.env.NODE_ENV) //设置 laravel的不同环境（生产、线上这些）是在.env文件中
//但 process.env.NODE_ENV这个不需要你配置 根据编译资源时运行的是 npm run dev 还是 npm run prod 来区分
{
    case 'development':
        api_url = 'http://roast.test/api/v1';
        break;
    case 'production':
        api_url = 'http://roast.demo.heroku.com/api/v1';
        break;
}

export const ROAST_CONFIG = {
    API_URL: api_url,
    GAODE_MAPS_JS_API_KEY: gaode_maps_js_api_key,
}
