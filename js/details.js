function calculateTotalPrice() {
  var roomType = document.getElementById("room-type").value;
  var roomPrice = parseFloat(
    document
      .getElementById("room-type")
      .options[document.getElementById("room-type").selectedIndex].getAttribute(
        "data-price"
      )
  );
  var servicesTotal = 0;
  var checkboxes = document.getElementsByName("services[]");
  var foodDropdown = document.getElementById("food");

  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].checked) {
      var servicePrice = parseFloat(checkboxes[i].getAttribute("data-price"));
      servicesTotal += servicePrice;
    }
  }

  var foodPrice = parseFloat(
    foodDropdown.options[foodDropdown.selectedIndex].getAttribute("data-price")
  );
  servicesTotal += foodPrice;

  var totalPrice = roomPrice + servicesTotal;
  document.getElementById("total-price").innerText =
    "Total Price: Rs. " + totalPrice.toFixed(2);
}

window.onload = function () {
  calculateTotalPrice();
};
