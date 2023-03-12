import _ from 'lodash';
window._ = _;

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Choices from 'choices.js';
let selects = document.querySelectorAll(".form-select");
selects.forEach((select) => {
	new Choices(select, {
        searchEnabled: false,
        itemSelectText: "",
    });
})

