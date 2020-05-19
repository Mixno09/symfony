import axios from 'axios';

axios.defaults.validateStatus = () => true;

export default axios;
