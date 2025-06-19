// webpack.config.js
// @ts-ignore
const defaultConfig = require('@wordpress/scripts/config/webpack.config.js');

const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    ...defaultConfig,
    // entry: {
    //     'store': './block/block.js'
    // },
    output: {
        path: path.resolve(__dirname, '../ecwid-shopping-cart/js/gutenberg-blocks'),
        filename: '[name].js',
    },
    module: {
        rules: [
            {
                test: /\.s?css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false,
                            sourceMap: true,
                        },
                    },
                    'sass-loader'
                ],
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '../css/gutenberg-blocks/[name].css',
        }),
    ],
};
