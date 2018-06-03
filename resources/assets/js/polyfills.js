// Promise polyfill
import Promise from 'promise-polyfill';
window.Promise = Promise;

// Object.assign polyfill
require('./polyfills/object-assign');

// IntersectionObserver polyfill
require('intersection-observer');