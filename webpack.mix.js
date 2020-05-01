const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')

    .webpackConfig({
        module: {
            rules: [
                {
                    test: /\.jsx?$/,
                    exclude: /node_modules(?!\/foundation-sites)|bower_components/,
                    use: [
                        {
                            loader: 'babel-loader',
                            options: Config.babel(),
                        }
                    ]
                }
            ]
        },// module 部分配置用于构建 Foundation。
        resolve: {
            alias: {
                '@': path.resolve('resources/assets/sass'),
            }
        }//当我们构建应用时，会将 @ 解析为 resources/assets/sass 路径别名。
    })


    .sass('resources/assets/sass/app.scss', 'public/css')
    .version();
