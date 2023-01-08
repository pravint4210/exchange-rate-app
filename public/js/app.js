/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    // Fetching data for first time page load
    const fetchData = $.ajax({
        url: "/get-data",
        method: "POST"
    });

    fetchData.done(function( response ) {
        $('.rates-table-container').html(response);
    });

    fetchData.fail(function( jqXHR, textStatus ) {
        console.log(textStatus);
        console.log(jqXHR);
    });
    
    //Saving and fetching latest data on button click
    $(document).on('click', '.get-latest-rates', function(){
        // Get input values
        const from_currency = $('.base-currency').val();
        const target_currency = $('.target-currency').val();
        const amount = Number($('.er-amount').val());

        // Validate amount
        if(!Number.isInteger(amount) || amount <= 0){
            alert('Amount is not valid');
            return false;
        }
   
        $('.rates-table-container').html("Loading.......");
         // Send ajax request to 
         const request = $.ajax({
             url: "/fetch-rates",
             method: "POST",
             data: { 
                 from_currency : from_currency,
                 target_currency: target_currency,
                 amount: amount
             }
         });

        request.done(function( response ) {
            $('.rates-table-container').html(response);
        });

        request.fail(function( jqXHR, textStatus ) {
            console.log(textStatus);
            console.log(jqXHR);
        });
    });
});

