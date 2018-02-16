//Home view
/* eslint-disable no-unused-vars */
import Barba from "barba.js";
import $ from 'jquery';

const Home = Barba.BaseView.extend({
    namespace: "page-home",
    onEnter() {
    },
    onEnterCompleted() {
    },
    onLeave() {
        this.destroy();
    },
    $el: null,
    _construct() {},
    destroy() {},
});
export default Home;
