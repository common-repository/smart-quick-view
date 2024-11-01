export const openPopup = productId => {
    return {
        type: 'popup/open',
        payload: {
            productId
        }
    }
}

export const closePopup = () => {
    return {
        type: 'popup/close'
    }
}

export const ProductDataLoading = ({productId, loadingProductData}) => {
    return {
        type: 'product/data/loading',
        payload: {
            productId,
            loadingProductData
        }
    }
}

export const productDataLoaded = ({id, productData, loadingProductData}) => {
    return {
        type: 'product/data/new',
        payload: {
            id, productData, loadingProductData
        }
    }
}
