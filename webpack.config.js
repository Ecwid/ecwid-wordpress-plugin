const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
    ...defaultConfig,
    entry: {
        'index': './blocks/src/blocks.js'
    },
    output: {
        path: __dirname + '/blocks/build',
    }
    // plugins: [
    //     new MiniCssExtractPlugin('css/gutenberg/app.css', { allChunks: true }),
    // ],
    // module: {
    //     ...defaultConfig.modulew,
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