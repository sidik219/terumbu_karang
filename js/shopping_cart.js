var formatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0

    // These options are needed to round to whole numbers if that's what you want.
    //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
    //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
});

if (document.readyState == 'loading') {
    document.addEventListener('DOMContentLoaded', ready)
} else {
    ready()
}

function ready() {
    var removeCartItemButtons = document.getElementsByClassName('btn-danger')
    for (var i = 0; i < removeCartItemButtons.length; i++) {
        var button = removeCartItemButtons[i]
        button.addEventListener('click', removeCartItem)
    }

    var quantityInputs = document.getElementsByClassName('cart-quantity-input')
    for (var i = 0; i < quantityInputs.length; i++) {
        var input = quantityInputs[i]
        input.addEventListener('change', quantityChanged)
    }

    var addToCartButtons = document.getElementsByClassName('shop-item-button')
    for (var i = 0; i < addToCartButtons.length; i++) {
        var button = addToCartButtons[i]
        button.addEventListener('click', addToCartClicked)
    }

    document.getElementsByClassName('btn-back')[0].addEventListener('click', backClicked)
    document.getElementsByClassName('btn-back')[1].addEventListener('click', backClicked)
    document.getElementsByClassName('btn-purchase')[0].addEventListener('click', purchaseClicked)

    if (sessionStorage.getItem("keranjang_serialised")) {
        var keranjang_old = JSON.parse(sessionStorage.getItem("keranjang_serialised"))
        if (keranjang_old.keranjang.length) {
            for (i = 0; i < keranjang_old.keranjang.length; i++) {
                if (keranjang_old.keranjang[i] !== null) {
                    var title = keranjang_old.keranjang[i].nama_tk
                    var price = `Rp. ${keranjang_old.keranjang[i].harga_tk}`
                    var imageSrc = keranjang_old.keranjang[i].image
                    var itemID = keranjang_old.keranjang[i].id_tk
                    var jumlah_tk = keranjang_old.keranjang[i].jumlah_tk
                    keranjang_old.keranjang[i]["ignore"] = 1
                    var ignore = keranjang_old.keranjang[i].ignore
                    addItemToCart(title, price, imageSrc, itemID, ignore, jumlah_tk)
                    updateCartTotal()
                }
            }

        }
    }


    window.addEventListener('beforeunload', function(event) {
        backClicked()
    });

}


function purchaseClicked() {
    var keranjang_deserialised = JSON.parse(sessionStorage.getItem('keranjang_serialised'))
    if (keranjang_deserialised.nominal == 0) {
        alert('Harap Pilih Terumbu Karang sebelum Checkout')
    } else {
        var isipesan = document.getElementById("pesan").value;
        keranjang_deserialised["pesan"] = isipesan

        var keranjang_serialised = JSON.stringify(keranjang_deserialised)
        sessionStorage.setItem('keranjang_serialised', keranjang_serialised)
        document.location.href = 'review_donasi.php';
    }

} //purchase clicked fx


function backClicked() {
    if (document.getElementById("pesan").value) {
        var keranjang_deserialised = JSON.parse(sessionStorage.getItem('keranjang_serialised'))
        var isipesan = document.getElementById("pesan").value;
        keranjang_deserialised["pesan"] = isipesan

        var keranjang_serialised = JSON.stringify(keranjang_deserialised)
        sessionStorage.setItem('keranjang_serialised', keranjang_serialised)
    }

    history.back()
}

function removeCartItem(event) {
    var keranjang_old = JSON.parse(sessionStorage.getItem("keranjang_serialised"))

    var buttonClicked = event.target
    var item_name = $(buttonClicked).parent().parent().find('.cart-item-title').text()

    for (i = 0; i < keranjang_old.keranjang.length; i++) {
        if (keranjang_old.keranjang[i] != null) {
            if (item_name == keranjang_old.keranjang[i].nama_tk) {
                delete keranjang_old.keranjang[i]
            }
        }
    }

    //filter null items
    var filtered_keranjang = keranjang_old.keranjang.filter(item => item !== null)
        //empty array
    keranjang_old.keranjang = []
        //insert filtered array
    keranjang_old.keranjang = filtered_keranjang



    var keranjang_serialised = JSON.stringify(keranjang_old)
    sessionStorage.setItem('keranjang_serialised', keranjang_serialised)

    alert(item_name + ' telah dihapus')
    buttonClicked.parentElement.parentElement.remove()
    updateCartTotal()
}






function quantityChanged(event) {
    var input = event.target
    if (isNaN(input.value) || input.value <= 1) {
        input.value = 1
    }

    var keranjang_old = JSON.parse(sessionStorage.getItem("keranjang_serialised"))
    var item_name = $(input).parent().parent().find('.cart-item-title').text()

    for (i = 0; i < keranjang_old.keranjang.length; i++) {
        if (keranjang_old.keranjang[i] != null) {
            if (item_name == keranjang_old.keranjang[i].nama_tk) {
                keranjang_old.keranjang[i].jumlah_tk = input.value
            }
        }
    }

    var keranjang_serialised = JSON.stringify(keranjang_old)
    sessionStorage.setItem('keranjang_serialised', keranjang_serialised)
    updateCartTotal()
}

function addToCartClicked(event) {
    var button = event.target
    var shopItem = button.parentElement.parentElement.parentElement.parentElement
    var title = shopItem.getElementsByClassName('shop-item-title')[0].innerText
    var price = shopItem.getElementsByClassName('shop-item-price')[0].innerText
    var imageSrc = shopItem.getElementsByClassName('shop-item-image')[0].src
    var itemID = shopItem.getElementsByClassName('shop-item-id')[0].value
    addItemToCart(title, price, imageSrc, itemID)
    updateCartTotal()
}


function addItemToCart(title, price, imageSrc, itemID, ignore, jumlah_tk) {
    var cartRow = document.createElement('div')
    cartRow.classList.add('cart-row')
    var cartItems = document.getElementsByClassName('cart-items')[0]
    var cartItemNames = cartItems.getElementsByClassName('cart-item-title')
    for (var i = 0; i < cartItemNames.length; i++) {
        if (cartItemNames[i].innerText == title) {
            alert('Terumbu karang ini sudah masuk keranjang')
            return
        }
    }
    if (!ignore) {
        ignore = 0
    }
    if (!jumlah_tk) {
        jumlah_tk = 1
    }

    var cartRowContents = `
        <div class="cart-item cart-column">
            <img class="cart-item-image" src="${imageSrc}" width="100" height="100">
            <span class="cart-item-title">${title}</span>
        </div>
        <span class="cart-price-display cart-column">${formatter.format(price.replace('Rp.', ''))}</span>
        <span class="cart-price cart-column d-none">${price}</span>
        <div class="cart-quantity cart-column">
            <input class="cart-quantity-input" min="1" step="1" type="number" max="999" value="${jumlah_tk}">
            <button class="btn btn-danger" type="button">X</button>
            <input type="hidden" class="cart-item-id" value=" ${itemID}">
            <input type="hidden" class="cart-item-ignore" value="${ignore}">
        </div>`
    cartRow.innerHTML = cartRowContents
    cartItems.append(cartRow)
    cartRow.getElementsByClassName('btn-danger')[0].addEventListener('click', removeCartItem)
    cartRow.getElementsByClassName('cart-quantity-input')[0].addEventListener('change', quantityChanged)


    var keranjang_deserialised = JSON.parse(sessionStorage.getItem('keranjang_serialised'))

    var cartItemContainer = document.getElementsByClassName('cart-items')[0]
    var cartRows = cartItemContainer.getElementsByClassName('cart-row')
    var total = 0
    if (!keranjang_deserialised) {
        keranjang_deserialised = {}
        var keranjang = []
        for (var i = 0; i < cartRows.length; i++) {
            var cartRow = cartRows[i]
                // if (cartRow.getElementsByClassName('cart-item-ignore')[0] != undefined) {
            if (cartRow.getElementsByClassName('cart-item-ignore')[0].value != 1) {
                var nama_tk = cartRow.getElementsByClassName('cart-item-title')[0].innerText
                var priceElement = cartRow.getElementsByClassName('cart-price')[0]
                var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0]
                var itemID = cartRow.getElementsByClassName('cart-item-id')[0].value
                var price = parseFloat(priceElement.innerText.replace('Rp.', ''))
                var cart_image = cartRow.getElementsByClassName('cart-item-image')[0].src;
                var quantity = quantityElement.value
                total = total + (price * quantity)

                var jumtk
                if (quantity > 1) {
                    jumtk = parseInt(quantityElement.value)
                } else jumtk = 1
                keranjang.push({
                    nama_tk: nama_tk,
                    id_tk: itemID,
                    image: cart_image,
                    jumlah_tk: parseInt(quantityElement.value),
                    harga_tk: price
                })
            }
            // }
        }

        total = Math.round(total * 100) / 100
        document.getElementsByClassName('cart-total-price')[0].innerText = 'Rp. ' + total

        var isipesan = document.getElementById("pesan").value;
        keranjang_deserialised["pesan"] = isipesan
        keranjang_deserialised["nominal"] = total
        keranjang_deserialised["status"] = "New keranjang"
        keranjang_deserialised["id_lokasi"] = document.getElementById("id-lokasi").value

        keranjang_deserialised["keranjang"] = keranjang
        var keranjang_serialised = JSON.stringify(keranjang_deserialised)
        sessionStorage.setItem('keranjang_serialised', keranjang_serialised)
            // document.location.href = `pilih_jenis_tk.php?id_lokasi=${keranjang_deserialised.id_lokasi}`;

    } else {
        var keranjang_old = keranjang_deserialised.keranjang
        for (var i = 0; i < cartRows.length; i++) {
            var cartRow = cartRows[i]
                // if (cartRow.getElementsByClassName('cart-item-ignore')[0] != undefined) {
            if (cartRow.getElementsByClassName('cart-item-ignore')[0].value != 1) {
                var nama_tk = cartRow.getElementsByClassName('cart-item-title')[0].innerText
                var priceElement = cartRow.getElementsByClassName('cart-price')[0]
                var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0]
                var itemID = cartRow.getElementsByClassName('cart-item-id')[0].value
                var price = parseFloat(priceElement.innerText.replace('Rp.', ''))
                var cart_image = cartRow.getElementsByClassName('cart-item-image')[0].src;
                var quantity = quantityElement.value
                total = total + (price * quantity)


                const listHasId = keranjang_deserialised.keranjang.some(item => item.id_tk == itemID);
                console.log(listHasId)

                if (listHasId) {
                    const oldItem = keranjang_deserialised.keranjang.find(item => item.id_tk == itemID);
                    oldItem.jumlah_tk = parseInt(oldItem.jumlah_tk) + parseInt(quantityElement.value)
                } else {
                    var jumtk
                    if (quantity > 1) {
                        jumtk = parseInt(quantityElement.value)
                    } else jumtk = 1
                    keranjang_old.push({
                        nama_tk: nama_tk,
                        id_tk: itemID,
                        image: cart_image,
                        jumlah_tk: parseInt(quantityElement.value),
                        harga_tk: price
                    })

                }
                // }

            }


        }
        total = Math.round(total * 100) / 100
        document.getElementsByClassName('cart-total-price')[0].innerText = 'Rp. ' + total

        var isipesan = document.getElementById("pesan").value;
        keranjang_deserialised["pesan"] = isipesan
        keranjang_deserialised["nominal"] += total
        keranjang_deserialised["status"] = "OLD keranjang"
        keranjang_deserialised["id_lokasi"] = document.getElementById("id-lokasi").value

        keranjang_deserialised["keranjang"] = keranjang_old
        var keranjang_serialised = JSON.stringify(keranjang_deserialised)
        sessionStorage.setItem('keranjang_serialised', keranjang_serialised)
    }
}

function updateCartTotal() {
    var cartItemContainer = document.getElementsByClassName('cart-items')[0]
    var cartRows = cartItemContainer.getElementsByClassName('cart-row')
    var total = 0
    for (var i = 0; i < cartRows.length; i++) {
        var cartRow = cartRows[i]
        var priceElement = cartRow.getElementsByClassName('cart-price')[0]
        var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0]
        var price = parseFloat(priceElement.innerText.replace('Rp.', ''))
        var quantity = quantityElement.value
        total = total + (price * quantity)
    }
    total = Math.round(total * 100) / 100
    document.getElementsByClassName('cart-total-price')[0].innerText = formatter.format(total)

    var keranjang_old = JSON.parse(sessionStorage.getItem("keranjang_serialised"))
    keranjang_old.nominal = total
    var keranjang_serialised = JSON.stringify(keranjang_old)
    sessionStorage.setItem('keranjang_serialised', keranjang_serialised)
}

$(document).ready(function() {

    $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });

    $('.scrollup').click(function() {
        // $("html, body").animate({
        //     scrollTop: 0
        // }, 600);
        // return false;
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#keranjang").offset().top
        }, 1000);
    });

});
