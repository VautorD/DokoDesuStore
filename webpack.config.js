const Encore = require('@symfony/webpack-encore');
const path = require('path');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enablePostCssLoader((options) => {
        options.postcssOptions = {
            path: path.resolve(__dirname, 'postcss.config.js')
        };
    })
    .copyFiles({
        from: './assets/img',
        to: 'img/[path][name].[hash:8].[ext]',
        pattern: /\.(png|jpg|jpeg|svg)$/
    })
    .copyFiles({
        from: './assets/icons',
        to: 'icons/[path][name].[hash:8].[ext]',
        pattern: /\.(png|jpg|jpeg|svg)$/
    })
    .addLoader({
        test: /\.(png|jpg|jpeg|gif|svg|eot|ttf|woff|woff2)$/i,
        loader: 'file-loader',
        options: {
            outputPath: 'assets',
            publicPath: '/build/assets',
        },
    })
;

module.exports = Encore.getWebpackConfig();