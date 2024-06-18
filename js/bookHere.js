function updatePrice(roomType) {
    const pricingElement = document.getElementById('pricing');
    let price = 100;

    switch(roomType) {
        case 'premium':
            price = 200;
            break;
        case 'deluxe':
            price = 200;
            break;
        case 'executive':
            price = 250;
            break;
        default:
            price = 100;
    }

    pricingElement.innerHTML = `<p>Starting from $${price} per night</p>`;
}
