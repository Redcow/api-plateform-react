import React from 'react';
import ReactDOM from 'react-dom';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

console.log('Hello Webpack Encore!');

const App = () => {
    return <h1> Bonjour </h1>;
}

const root = document.querySelector('#app');
ReactDOM.render(<App />, root);