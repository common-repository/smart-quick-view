import { useSelector, useDispatch } from 'react-redux'
import { stateGetter } from './helpers'
import { openPopup, closePopup, productDataLoaded, ProductDataLoading } from './actions'
import { useEffect, useLayoutEffect } from 'react';
import Modal from 'react-modal';
import parse from 'html-react-parser';

Modal.setAppElement('#smart-quick-view-popup');
const body = $('body');

body.addClass('smart-quick-view sqv')

const dimensions = {
    image: {
        maxHeight: 600
    }
}

function App() {
    const popupIsOpen = useSelector(stateGetter('popupIsOpen'))
    const productId = useSelector(stateGetter('productId'))
    const productsData = useSelector(stateGetter('productsData'))
    const loadingProductData = useSelector(stateGetter('loadingProductData'))

    const dispatch = useDispatch();

    useEffect(componentDidMountDispatch, [dispatch]);
    useLayoutEffect(() => {
        window.setTimeout(() => {
            if (popupIsOpen) {
                $('.sqv-popup–content .woocommerce-product-gallery').each((index, target) => {
                    const productGallery = $(target);

                    productGallery.wc_product_gallery();

                    window.setTimeout(() => {
                        if ($(document).width() < 767) {
                            // the same height, we need to set a fixed height to make sure the content is scrollable
                            productGallery.closest('.ReactModal__Content').css('height', productGallery.closest('.ReactModal__Content').height());
                            return;
                        }

                        // tablet & desktop
                        const imagesizes = [];

                        productGallery.find('.woocommerce-product-gallery__image').each((index, target) => {
                            if ($(document).width() < 767) {
                                return;
                            }
                            const targetHeight = $(target).height();

                            imagesizes.push(
                                targetHeight <= dimensions.image.maxHeight? targetHeight : dimensions.image.maxHeight
                            );
                        })
                        imagesizes.sort((one, two) => one - two);

                        const highestImageSize = imagesizes.pop();
                        const lowestImagesize = imagesizes.shift();

                        if (highestImageSize > 499) {
                            const height = highestImageSize + productGallery.find('.flex-control-thumbs img').innerHeight();
                            productGallery.closest('.ReactModal__Content').css('height', height);
                        }
                    }, 100)
                });

                const placeholder = $('.sqv-popup–content').find('#sqv-variations-form-placeholder');

                if (placeholder.length) {
                    placeholder.replaceWith(productsData[productId].variationsFormOriginal);
                    $('.sqv-popup–content .variations_form').each(function() {
                        const variationForm = $(this);
                        variationForm.wc_variation_form();
                    });
                }
            }
        }, 10)
    })

    function componentDidMountDispatch()
    {
        $('.product').on('click', '.sqv-quick-view-button', event => {
            const productId = parseInt($(event.target).attr('data-sqv-product-id'));

            if (productId > 0) {
                dispatch(openPopup(productId))
            } else {
                console.error(`Invalid product id: ${productId}`);
            }
        })
    }

    function loadProductData(productId)
    {
        const isNotLoadingDataForThisProduct = !loadingProductData.includes(productId);

        if (isNotLoadingDataForThisProduct) {
            $.ajax({
                method: 'GET',
                url: SmartQuickView.urls.adminAJAX,
                dataType: 'json',
                beforeSend: () => {
                    dispatch(ProductDataLoading({
                        productId, loadingProductData
                    }));
                },
                data: {
                    action: SmartQuickView.actions.getTemplate,
                    productId
                },
                complete: ({status, responseJSON: {data}}) => {
                    if (status !== 200 || !data) {
                        return;
                    }

                    /**
                     * Ok, at this point I realized React wasn't the best tool for this job
                     * but since the project is almost concluded and it currently works fine
                     * it's staying like this.
                     *
                     * But code like the following feels like a h*ck so I'll probably
                     * refactor it to something better in the future, don't know yet. 
                     *
                     * Basically, React is trying to control the <select> element generated by woo,
                     * but we can't do that since woocommerce heavily relies on jQuery.
                     */
                    const productData = data;
                    const originalTemplate = productData.template;
                    const template = $($.parseHTML(`<div>${originalTemplate}</div>`));
                    const variationsForm = template.find('.variations_form');
                    const variationsFormOriginal = variationsForm.clone();

                    variationsForm.replaceWith('<div id="sqv-variations-form-placeholder"></div>');

                    productData.template = template.html();
                    productData.variationsFormOriginal = variationsFormOriginal;

                    dispatch(productDataLoaded({
                        id: data.id, 
                        productData,
                        loadingProductData
                    }))
                }
            })
        }
        // perform an ajax request
        // and then send the loaded contents to redux action
        // we'll ckeck if it's loading so that we only send the request once
        // 
    }

    function getTemplateHTML()
    {
        if (typeof productsData[productId] === 'object') {
            return productsData[productId].template || '.';
        }
    }

    function renderModalContent()
    {
        const template = getTemplateHTML();

        if (template) {
            return template;
        }

        loadProductData(productId);

        return SmartQuickView.template.skeleton;
    }

    function render()
    {
        console.log('settin themc lasses', SmartQuickView.app.classes.contentElement);
        return (
            <div className="sqv-popup ml-1">
                <Modal 
                    isOpen={popupIsOpen}
                    onAfterOpen={({contentEl}) => {
                        body.css('overflow', 'hidden');
                    }}
                    onAfterClose={() => {
                        body.css('overflow', 'initial');
                    }}
                    onRequestClose={() => dispatch(closePopup())}
                    overlayClassName={`fixed left-0 top-0 z-[10000] w-screen h-screen flex items-center justify-center bg-black bg-opacity-[0.7] backdrop-filter ${SmartQuickView.publicPreferences.styles.blurIsEnabled? 'backdrop-blur-[30px] [transform:translateZ(0)]': ''}`}
                    className={`${SmartQuickView.app.classes.contentElement}`}
                >
                    <div className="sqv-popup–content single-product relative h-full">
                        <button className="absolute z-10 top-[12px] right-[12px] p-[4px] bg-gray-300" onClick={() => dispatch(closePopup())}>
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                              <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        {popupIsOpen? parse(renderModalContent()) : ''}
                    </div>
                </Modal>
            </div>
        )
    }

    return render();
}

export default App;
