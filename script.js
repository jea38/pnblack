const searchInput = document.querySelector('.search input');

function searchFunction(event) {
    if (event.keyCode === 13 && searchInput.value.length > 0) {
        event.preventDefault(); // Prevent form submission
        submitSearch(event);
    }
}

function submitSearch(event) {
    event.preventDefault(); // Prevent form submission

    if (rewrite_url) {
        window.location.href = encodeURI(base_url + 'search/' + searchInput.value);
    } else {
        window.location.href = encodeURI(base_url + 'index.php?page=search&query=' + searchInput.value);
    }
}

if (document.querySelector('.product #product-form')) {
    let updatePrice = () => {
        let price = parseFloat(document.querySelector('.product-price .price').dataset.price);
        document.querySelectorAll('.product #product-form .option').forEach(e => {
            if (e.value) {
                let optionPrice = e.classList.contains('text') || e.classList.contains('datetime') ? e.dataset.price : 0.00;
                optionPrice = e.classList.contains('select') ? e.options[e.selectedIndex].dataset.price : optionPrice;
                optionPrice = (e.classList.contains('radio') || e.classList.contains('checkbox')) && e.checked ? e.dataset.price : optionPrice;
                price = (e.classList.contains('select') ? e.options[e.selectedIndex].dataset.modifier : e.dataset.modifier) == 'add' ? price + parseFloat(optionPrice) : price - parseFloat(optionPrice);
            }
        });
        document.querySelector('.product-price .price').innerHTML = currency_code + (price > 0.00 ? price.toFixed(2) : 0.00);
    };
    document.querySelectorAll('.product #product-form .option').forEach(ele => ele.onchange = () => updatePrice());
    updatePrice();
}
if (document.querySelector('.products-form')) {
    let products_form_submit = () => {
        document.querySelector('.products-form')
        if (rewrite_url) {
            window.location.href = encodeURI(base_url + 'products/' + document.querySelector('.category select').value + '/' + document.querySelector('.sortby select').value);
        } else {
            window.location.href = encodeURI(base_url + 'index.php?page=products&category=' + document.querySelector('.category select').value + '&sort=' + document.querySelector('.sortby select').value);
        }
    };
    document.querySelector('.sortby select').onchange = () => products_form_submit();
    document.querySelector('.category select').onchange = () => products_form_submit();
}

if (document.querySelector('.cart .ajax-update')) {
    document.querySelectorAll('.cart .ajax-update').forEach(ele => {
        ele.onchange = () => {
            let formEle = document.querySelector('.cart form');
            let formData = new FormData(formEle);
            formData.append('update', 'Update');
            fetch(formEle.action, {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(html => {
                let doc = (new DOMParser()).parseFromString(html, 'text/html');
                document.querySelector('.total').innerHTML = doc.querySelector('.total').innerHTML;
                document.querySelectorAll('.product-total').forEach((e, i) => {
                    e.innerHTML = doc.querySelectorAll('.product-total')[i].innerHTML;
                });
            });
        };
    });
}
const checkoutHandler = () => {
    if (document.querySelector('.checkout .ajax-update')) {
        document.querySelectorAll('.checkout .ajax-update').forEach(ele => {
            ele.onchange = () => {
                let formEle = document.querySelector('.checkout form');
                let formData = new FormData(formEle);
                formData.append('update', 'Update');
                fetch(formEle.action, {
                    method: 'POST',
                    body: formData
                }).then(response => response.text()).then(html => {
                    let doc = (new DOMParser()).parseFromString(html, 'text/html');
                    document.querySelector('.summary').innerHTML = doc.querySelector('.summary').innerHTML;
                    document.querySelector('.total').innerHTML = doc.querySelector('.total').innerHTML;
                    document.querySelector('.discount-code .result').innerHTML = doc.querySelector('.discount-code .result').innerHTML;
                    document.querySelector('.shipping-methods-container').innerHTML = doc.querySelector('.shipping-methods-container').innerHTML;
                    checkoutHandler();
                });
            };
            if (ele.name == 'discount_code') {
                ele.onkeydown = event => {
                    if (event.key == 'Enter') {
                        event.preventDefault();
                        ele.onchange();
                    }
                };
            }
        });
    }
};
checkoutHandler();