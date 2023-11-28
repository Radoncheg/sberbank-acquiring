function getPrice() {
    const selectedIndex = document.getElementById('selectProduct').selectedIndex
    const options = document.getElementById('selectProduct').options
    const prices = document.querySelectorAll('span')

    if (options[selectedIndex].value !== '') {
        prices[0].textContent = options[selectedIndex].dataset.price;
        prices[1].textContent = String((parseFloat(options[selectedIndex].dataset.price) * document.getElementById("productCount").value).toFixed(2))
    }
    else {
        prices[0].textContent = '0.00'
        prices[1].textContent = '0.00'
    }
}