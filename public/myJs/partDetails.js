// take value from blade to modal
function doRefill(feederId){        
    $('#feederId').val(feederId); //The id where to pass the value
    $('.refillModal-modal-xl').modal('show'); //The class of the modal to show
};
// send Form data to PostController
$('#refillForm').on('submit',function(event){
    event.preventDefault();
    feederId = $('#feederId').val().trim();
    partNumber = $('#partNumber').val().trim();
    $.ajax({
        url: "/refill",
        type:"POST",
        data:{
            "_token": "{{ csrf_token() }}",
            feederId:feederId,
            partNumber:partNumber           
        },
        success:function(response){
            console.log(response);
        },
    });
});
