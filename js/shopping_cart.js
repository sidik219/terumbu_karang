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
                var title = keranjang_old.keranjang[i].nama_tk
                var price = keranjang_old.keranjang[i].harga_tk
                var imageSrc = keranjang_old.keranjang[i].image
                var itemID = keranjang_old.keranjang[i].id_tk
                addItemToCart(title, price, imageSrc, itemID)
                updateCartTotal()
            }

        }
    }

}

function purchaseClicked() {
    var keranjang_deserialised = JSON.parse(sessionStorage.getItem('keranjang_serialised'))

    var cartItemContainer = document.getElementsByClassName('cart-items')[0]
    var cartRows = cartItemContainer.getElementsByClassName('cart-row')
    var total = 0
    if (!keranjang_deserialised) {
        keranjang_deserialised = {}
        var keranjang = []
        for (var i = 0; i < cartRows.length; i++) {
            var cartRow = cartRows[i]
            var nama_tk = cartRow.getElementsByClassName('cart-item-title')[0].innerText
            var priceElement = cartRow.getElementsByClassName('cart-price')[0]
            var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0]
            var itemID = cartRow.getElementsByClassName('cart-item-id')[0].value
            var price = parseFloat(priceElement.innerText.replace('Rp.', ''))
            var cart_image = cartRow.getElementsByClassName('cart-item-image')[0].src;
            var quantity = quantityElement.value
            total = total + (price * quantity)

            // keranjang["nama_tk"] = nama_tk
            // keranjang["id_tk"] = itemID
            // keranjang["jumlah_tk"] = quantityElement.value

            keranjang.push({
                nama_tk: nama_tk,
                id_tk: itemID,
                image: cart_image,
                jumlah_tk: quantityElement.value,
                harga_tk: price
            })
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
        document.location.href = `pilih_jenis_tk.php?id_lokasi=${keranjang_deserialised.id_lokasi}`;

    } else {
        var keranjang_old = keranjang_deserialised.keranjang
        for (var i = 0; i < cartRows.length; i++) {
            var cartRow = cartRows[i]
            var nama_tk = cartRow.getElementsByClassName('cart-item-title')[0].innerText
            var priceElement = cartRow.getElementsByClassName('cart-price')[0]
            var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0]
            var itemID = cartRow.getElementsByClassName('cart-item-id')[0].value
            var price = parseFloat(priceElement.innerText.replace('Rp.', ''))
            var cart_image = cartRow.getElementsByClassName('cart-item-image')[0].src;
            var quantity = quantityElement.value
            total = total + (price * quantity)

            // keranjang["nama_tk"] = nama_tk
            // keranjang["id_tk"] = itemID
            // keranjang["jumlah_tk"] = quantityElement.value

            const listHasId = keranjang_deserialised.keranjang.some(item => item.id_tk == itemID);
            console.log(listHasId)

            if (listHasId) {
                const oldItem = keranjang_deserialised.keranjang.find(item => item.id_tk == itemID);
                oldItem.jumlah_tk = parseInt(oldItem.jumlah_tk) + parseInt(quantityElement.value)
            } else {
                keranjang_old.push({
                    nama_tk: nama_tk,
                    id_tk: itemID,
                    image: cart_image,
                    jumlah_tk: quantityElement.value,
                    harga_tk: price
                })

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
        document.location.href = 'review_donasi.php';
    }

}


function backClicked() {
    var keranjang_deserialised = JSON.parse(sessionStorage.getItem('keranjang_serialised'))

    var cartItemContainer = document.getElementsByClassName('cart-items')[0]
    var cartRows = cartItemContainer.getElementsByClassName('cart-row')
    var total = 0
    if (!keranjang_deserialised) {
        keranjang_deserialised = {}
        var keranjang = []
        for (var i = 0; i < cartRows.length; i++) {
            var cartRow = cartRows[i]
            var nama_tk = cartRow.getElementsByClassName('cart-item-title')[0].innerText
            var priceElement = cartRow.getElementsByClassName('cart-price')[0]
            var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0]
            var itemID = cartRow.getElementsByClassName('cart-item-id')[0].value
            var price = parseFloat(priceElement.innerText.replace('Rp.', ''))
            var cart_image = cartRow.getElementsByClassName('cart-item-image')[0].src;
            var quantity = quantityElement.value
            total = total + (price * quantity)

            // keranjang["nama_tk"] = nama_tk
            // keranjang["id_tk"] = itemID
            // keranjang["jumlah_tk"] = quantityElement.value

            keranjang.push({
                nama_tk: nama_tk,
                id_tk: itemID,
                image: cart_image,
                jumlah_tk: quantityElement.value,
                harga_tk: price
            })
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
        document.location.href = `pilih_jenis_tk.php?id_lokasi=${keranjang_deserialised.id_lokasi}`;

    } else {
        var keranjang_old = keranjang_deserialised.keranjang
        for (var i = 0; i < cartRows.length; i++) {
            var cartRow = cartRows[i]
            var nama_tk = cartRow.getElementsByClassName('cart-item-title')[0].innerText
            var priceElement = cartRow.getElementsByClassName('cart-price')[0]
            var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0]
            var itemID = cartRow.getElementsByClassName('cart-item-id')[0].value
            var price = parseFloat(priceElement.innerText.replace('Rp.', ''))
            var cart_image = cartRow.getElementsByClassName('cart-item-image')[0].src;
            var quantity = quantityElement.value
            total = total + (price * quantity)

            // keranjang["nama_tk"] = nama_tk
            // keranjang["id_tk"] = itemID
            // keranjang["jumlah_tk"] = quantityElement.value
            const listHasId = keranjang_deserialised.keranjang.some(item => item.id_tk == itemID);
            console.log(listHasId)

            if (listHasId) {
                const oldItem = keranjang_deserialised.keranjang.find(item => item.id_tk == itemID);
                oldItem.jumlah_tk = parseInt(oldItem.jumlah_tk) + parseInt(quantityElement.value)
            } else {
                keranjang_old.push({
                    nama_tk: nama_tk,
                    id_tk: itemID,
                    image: cart_image,
                    jumlah_tk: quantityElement.value,
                    harga_tk: price
                })

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
        document.location.href = `pilih_jenis_tk.php?id_lokasi=${keranjang_deserialised.id_lokasi}`;
    }
}

function removeCartItem(event) {
    var buttonClicked = event.target
    buttonClicked.parentElement.parentElement.remove()
    updateCartTotal()
}

function quantityChanged(event) {
    var input = event.target
    if (isNaN(input.value) || input.value <= 0) {
        input.value = 1
    }
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

// function addItemToCart(title, price, imageSrc, itemID) {
//     var cartRow = document.createElement('div')
//     cartRow.classList.add('cart-row', 'row')
//     var cartItems = document.getElementsByClassName('cart-items')[0]
//     var cartItemNames = cartItems.getElementsByClassName('cart-item-title')
//     for (var i = 0; i < cartItemNames.length; i++) {
//         if (cartItemNames[i].innerText == title) {
//             alert('Terumbu karang ini sudah ditambahkan ke keranjang')
//             return
//         }
//     }
//     var cartRowContents = `
//         <div class="cart-item cart-column col">
//             <img class="cart-item-image" src="${imageSrc}" width="100" height="100">
//         <div class="col">
//             <span class="cart-item-title text-break">${title}</span>
//         </div>
//         </div>
//         <div class="col">
//             <span class="cart-price cart-column">${price}</span>
//         </div>
//         <div class="cart-quantity cart-column col">
//             <input class="cart-quantity-input" type="number" value="1">
//             <button class="btn btn-danger" type="button"><i class="nav-icon fas fa-times-circle"></i></button>
//             <input type="hidden" class="cart-item-id" value="${itemID}">
//         </div>`
//     cartRow.innerHTML = cartRowContents
//     cartItems.append(cartRow)
//     cartRow.getElementsByClassName('btn-danger')[0].addEventListener('click', removeCartItem)
//     cartRow.getElementsByClassName('cart-quantity-input')[0].addEventListener('change', quantityChanged)
// }

function addItemToCart(title, price, imageSrc, itemID) {
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
    var cartRowContents = `
        <div class="cart-item cart-column">
            <img class="cart-item-image" src="${imageSrc}" width="100" height="100">
            <span class="cart-item-title">${title}</span>
        </div>
        <span class="cart-price cart-column">${price}</span>
        <div class="cart-quantity cart-column">
            <input class="cart-quantity-input" type="number" max="999" value="1">
            <button class="btn btn-danger" type="button">X</button>
            <input type="hidden" class="cart-item-id" value="${itemID}">
        </div>`
    cartRow.innerHTML = cartRowContents
    cartItems.append(cartRow)
    cartRow.getElementsByClassName('btn-danger')[0].addEventListener('click', removeCartItem)
    cartRow.getElementsByClassName('cart-quantity-input')[0].addEventListener('change', quantityChanged)
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
    document.getElementsByClassName('cart-total-price')[0].innerText = 'Rp. ' + total
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
