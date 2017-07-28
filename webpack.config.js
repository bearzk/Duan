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
        path: path.resolve(__dirname, './public/assets/'),
        filename: 'js/[name].[chunkhash].js'
    },
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/,
                use: ExtractTextPlugin.extract({
                    use: ['css-loader', 'sass-loader'],
                    fallback: 'style-loader',
                    publicPath: "../"
                })
            },
            {
                test: /\.png$/,
                loader: 'file-loader',
                options: {
                    name: 'images/[name].[hash].[ext]'
                }
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader'
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('css/style.[chunkhash].css'),
        new webpack.LoaderOptionsPlugin({ minimize: inProduction }),

        function () {
            this.plugin('done', stats => {
                require('fs').writeFileSync(
                    path.join(__dirname, 'public/assets/manifest.json'),
                    JSON.stringify(stats.toJson().assetsByChunkName)
                );
            })

        }
    ]
};

if (inProduction) {
    module.exports.plugins.push(
        new webpack.optimize.UglifyJsPlugin()
    );
}
