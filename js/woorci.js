(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    $(document).ready(function () {
       var woocir_btn = $('button#remove_all_items');
       woocir_btn.on('click submit',function(e){
            e.preventDefault();
            $.blockUI();
            var data = {
                woocir_remove  : 0
            };
            $.post(woorci_ajax_object.woorci_ajax_url,{
                action : 'woorci_remove_all_items',
                data : data ,
                security : woorci_ajax_object.woorci_ajax_security
            },function(response){
                if (response == 1){
                    $.unblockUI();
                    $('div.woocommerce-notices-wrapper').append('<div class="woocommerce-message" role="alert"> Your cart items have been successfully removed, this page will be refreshed in 3s.</div>');
                    setTimeout(function(){
                        window.location.reload();
                    },3000);
                }
            }); 
       });
    });
})(jQuery);