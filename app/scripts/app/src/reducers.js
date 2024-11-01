export const reducers = defaults => ({
    'popup/open': ({payload}) => {
        return {
            popupIsOpen: true,
            productId: payload.productId
        }
    },
    'popup/close': () => ({
        popupIsOpen: false,
        productId: 0
    }),

    'product/data/new': ({payload: {id, productData, loadingProductData}}) => {
        const loadingIDS = loadingProductData.filter(loadingId => loadingId !== id);

        return {
            productsData: {
                [id]: productData,
            },
            loadingProductData: loadingIDS
        }
    },
    'product/data/loading': ({payload: {productId, loadingProductData}}) => {
        return {
            loadingProductData: [...loadingProductData, productId]
        }
    },
});