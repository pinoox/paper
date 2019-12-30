const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserJSPlugin = require('terser-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const isRelease = (process.env.NODE_ENV === 'release');

module.exports = {
    entry: {
        main: [
            "./assets/js/main.js",
            "./assets/less/main.less",
        ],
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
                exclude: /fonts/,
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
    mode: 'development',
    plugins: [
        new webpack.ProgressPlugin(),
        new MiniCssExtractPlugin({
            filename: `[name].css${isRelease ? '?[chunkhash]' : ''}`,
            chunkFilename: `[id].css${isRelease ? '?[chunkhash]' : ''}`,
        }),
        /*new Notification(),*/
        new CopyWebpackPlugin([
            {from:'assets/images',to:'images'},
            {
                from: './assets/js/pinoox.js',
                flatten: true,
            },
        ]),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery'
        }),
    ],
    optimization: {
        minimize: isRelease,
        minimizer: [new TerserJSPlugin({}), new OptimizeCSSAssetsPlugin({})],
    },
    resolve: {
        alias: {
            '@img': path.resolve(__dirname, 'assets/images'),
            '@': path.resolve(__dirname, 'assets')
        }
    },

};
