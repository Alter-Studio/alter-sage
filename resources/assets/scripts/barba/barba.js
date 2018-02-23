/* eslint-disable */
import Barba from "barba.js";

//Transitions
import FadeTransition from "./transitions/fade";

//Views
import Home from "./views/home";

//Init Views
Home.init();

//Edit Wrapper and container class objects
Barba.Pjax.Dom.wrapperId = "main";
Barba.Pjax.Dom.containerClass = "wrapper";
//Ignore link updated
Barba.Pjax.ignoreClassLink = "ab-item"

/**
 * Function to scroll to top of page
 * @param {scrollDuration} number
 */

Barba.scrollTop = function(scrollDuration) {
    const scrollHeight = window.scrollY,
        scrollStep = Math.PI / (scrollDuration / 15),
        cosParameter = scrollHeight / 2;
    var scrollCount = 0,
        scrollMargin,
        scrollInterval = setInterval(function() {
            if (window.scrollY != 0) {
                scrollCount = scrollCount + 1;
                scrollMargin =
                    cosParameter -
                    cosParameter * Math.cos(scrollCount * scrollStep);
                window.scrollTo(0, scrollHeight - scrollMargin);
            } else clearInterval(scrollInterval);
        }, 15);
};

/**
 * Dispatcher event to register last clicked element
 */

/* eslint-disable no-unused-vars */

let lastClickEl;
Barba.Dispatcher.on("linkClicked", el => {
    lastClickEl = el;
});


//Parser function
function parseHTML(html) {
    var parser = new DOMParser();
    return parser.parseFromString(html, 'text/html');
}

//Wordpress admin bar replacement
Barba.Dispatcher.on('newPageReady', function(currentStatus, prevStatus, HTMLElementContainer, newPageRawHTML) {
    if (document.getElementById('wpadminbar')) {
        // Get new page's raw html
        const newDoc = parseHTML(newPageRawHTML); 

        // Get new admin bar
        const adminBar = newDoc.getElementById('wpadminbar');

        //If admin bar exists
        if (adminBar) {
            // Replace admin bar with new admin bar
            document.getElementById('wpadminbar').innerHTML = adminBar.innerHTML;
        }
    }
});

/* eslint-enable no-unused-vars */

//Tag manager dispatcher
// Barba.Dispatcher.on('initStateChange', function() {
//   if (typeof ga !== 'function' || Barba.HistoryManager.history.length <= 1) {
//     return;
//   }
//   gtag('event', 'page_view', { 'send_to': trackingCode, 'page_path':  window.location.pathname });
// });

Barba.Pjax.init();
Barba.Prefetch.init();

/**
 * Next step, you have to tell Barba to use the new Transition
 */

Barba.Pjax.getTransition = function() {
    //Default Transition
    return FadeTransition;
};
