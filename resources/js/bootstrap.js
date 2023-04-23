import _ from 'lodash';
window._ = _;

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { createApp } from "vue";
import Notifications from "./components/common/Notifications.vue";

createApp({
    components: { Notifications },
}).mount("#notifications");
