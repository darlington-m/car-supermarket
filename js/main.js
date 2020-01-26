

function addBid(carId, userId, ownerId, price){
    var amount = prompt("Please Enter Amount:", price);
    if (!(amount % 1 === 0) || amount == null || amount == 0) {
        alert("Bid Not Registered. Please Try Again");
        return null;
    }
    if(userId == ownerId) {
        alert("Sorry, you cant bid for you own car");
        return null;
    }

    var urlString = 'add-bid.php?add-bid=action&carID=' + carId + '&userID=' + userId + '&ownerID=' + ownerId + '&bid=' + amount;
    $('#bid-button').text('Placing......');

    $.ajax({
        url: urlString,
        success: function(data){
            $('#bid-button').text('Bid Placed');
        }
    });

}
