const webpack = require('webpack');
const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const TerserJSPlugin = require('terser-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const WebpackCleanPlugin = require('webpack-clean-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const isRelease = (process.env.NODE_ENV === 'release');
const Notification = require('./setup/plugins/notification');
const Manifest = require('./setup/plugins/manifest');

const config = {
    mode: !isRelease ? 'development' : 'production',
    entry: {
        main: [
            './src/js/main.js',
            './src/less/main.less'
        ]
    },
    output: {
        filename: `[name].js${isRelease ? '?[chunkhash]' : ''}`,
        path: path.resolve(__dirname, 'dist'),
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader'],
            },
            {
                test: /\.less$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader', 'less-loader'],
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        plugins: ['syntax-dynamic-import'],
                        presets: [
                            [
                                '@babel/preset-env',
                                {
                                    modules: false
                                }
                            ]
                        ]
                    }
                }
            },
            {
                test: /\.(png|jpe?g|gif|svg)$/,
                include: /images/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: 'images/[name].[hash].[ext]',
                        }
                    },
                    {
                        loader: 'image-webpack-loader',
                        options: {
                            webp: {
                                quality: 75,
                            }
                        }
                    }
                ]
            },
            {
                test: /\.(eot|ttf|woff|woff2|svg)$/,
                exclude: /images/,
                loader: 'file-loader',
                options: {
                    name: 'fonts/[name].[ext]',
                },
            },
        ]
    },
    plugins: [
        new webpack.ProgressPlugin(),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
        }),
        new CopyPlugin([
            {
                from: './src/images/*.png',
                to: 'images/logo',
                flatten: true,
            },
            {
                from: './src/js/pinoox.js',
                flatten: true,
            },
        ]),
        new MiniCssExtractPlugin({
            filename: `[name].css${isRelease ? '?[chunkhash]' : ''}`,
        }),
        new VueLoaderPlugin(),
        new Notification(),
    ],
    optimization: {
        minimize: isRelease,
        minimizer: [new TerserJSPlugin({}), new OptimizeCSSAssetsPlugin({})],
        splitChunks: {
            cacheGroups: {
                default: false,
                vendors: false,
                vendor: {
                    name:'vendor',
                    chunks: 'all',
                    test: /[\\/](node_modules|libs)[\\/]/
                }
            }
        }
    },
    resolve: {
        alias: {
            '@img': path.resolve(__dirname, 'src/images'),
            '@': path.resolve(__dirname, 'src')
        },
    },
};

module.exports = config;


if (isRelease) {
    module.exports.plugins.push(new Manifest('manifest.json'));

    module.exports.plugins.push(
        new WebpackCleanPlugin({
            on: "emit",
            path: ['./dist']
        }));
}

