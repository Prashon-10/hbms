document.getElementById('room-type').addEventListener('change', function () {
    const selectedRoom = this.value;
    let price;

    switch (selectedRoom) {
        case 'deluxe':
            price = 150;
            break;
        case 'suite':
            price = 200;
            break;
        default:
            price = 100; // Standard room
    }

    document.getElementById('pricing').innerHTML = `<p>Starting from $${price} per night</p>`;

    // zoom
    function openModal(imgSrc) {
        const modal = document.getElementById('myModal');
        const modalImg = document.getElementById('modalImg');

        modal.style.display = 'block';
        modalImg.src = imgSrc;
    }

    // Close modal
    function closeModal() {
        document.getElementById('myModal').style.display = 'none';
    }
});