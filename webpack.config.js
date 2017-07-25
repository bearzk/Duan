let webpack = require('webpack');
const path = require('path');
let inProduction = (process.env.NODE_ENV === 'production');
let ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {

    entry: {
        app: [
            './resources/assets/js/main.js',
            './resources/assets/sass/main.scss'
        ]
    },
    output: {
        path: path.resolve(__dirname, './public/assets/js'),
        filename: '[name].js'
    },
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/,
                use: ExtractTextPlugin.extract({
                    use: ['css-loader', 'sass-loader'],
                    fallback: 'style-loader'
                })
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader'
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('[name].css'),
        new webpack.LoaderOptionsPlugin({ minimize: inProduction })
    ]
};

if (inProduction) {
    module.exports.plugins.push(
        new webpack.optimize.UglifyJsPlugin()
    );
}
