import { createStore } from 'redux';
import { reducers } from './reducers';
import { merge } from 'lodash';

const defaults = {
    loadingProductData: []
};

const defaultState = {
    popupIsOpen: false,
    productId: 0,
    productsData: {/*(int) productId: (object) data*/...SmartQuickView.productsData},
    loadingProductData: defaults.loadingProductData
}

const loadedReducers = reducers(defaults);

const store = createStore((state = defaultState, action) => {
    if (!action.type.startsWith('@@')) {
        return merge({}, state, loadedReducers[action.type](action));
    }

    return state
    
}, window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__());

export default store;