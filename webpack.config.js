const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    ...defaultConfig,
    entry: path.resolve(__dirname, 'js/gutenberg/src/index.js'),
    output: {
        ...defaultConfig.output,
        path: path.resolve(__dirname, 'js/gutenberg/build/'),
        filename: 'index.js',
    },
    plugins: defaultConfig.plugins
        .filter(
            (plugin) =>
                plugin.constructor.name !== 'RtlCssPlugin' &&
                plugin.constructor.name !== 'WebpackRTLPlugin'
        )
        .map((plugin) => {
            if (plugin instanceof MiniCssExtractPlugin) {
                return new MiniCssExtractPlugin({
                    filename: '[name].css',
                });
            }
            return plugin;
        }),
};