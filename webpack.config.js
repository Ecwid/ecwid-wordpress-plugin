const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
    ...defaultConfig,
    entry: {
        '../js/gutenberg/test.js': './gutenberg-blocks/blocks.js'
    },
    plugins: [
        new MiniCssExtractPlugin('css/gutenberg/app.css', { allChunks: true }),
    ],
    // module: {
    //     ...defaultConfig.module,
    //     rules: [
    //         ...defaultConfig.module.rules,
    //         {
    //             test: /.toml/,
    //             type: 'json',
    //             parser: {
    //                 parse: toml.parse,
    //             },
    //         },
    //     ],
    // },
};