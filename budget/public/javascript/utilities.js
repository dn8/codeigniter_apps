$(function() {
//    $('.tool-tip').tooltip({
//        track: true,
//        delay: 1,
//        showURL: false,
//        opacity: .85,
//        fixPNG: true,
//        top: 15,
//        left: 5
//    });

    $('.money').blur(function() {
        //remove all comma's and dollar signs
        var regex    = new RegExp(/[\$,]/g);
        var re_2_dec = new RegExp(/(\.\d{2})\d+/);
        var amount   = $(this).val();
        var dec      = re_2_dec.exec(amount);

        if(dec) {
            amount = amount.replace(re_2_dec, dec[1]);
        }

        amount = amount.replace(regex, '');

        $(this).val(amount);
    });

    /**
     * trim non-numeric chars
     */
    $(".numeric").keyup(function() {
        var new_val = $(this).val().replace(/[\D]/g, '');
        $(this).val(new_val);
    });

    /**
     * add datepicker to glyphicon click
     */
    $('.date.glyphicon').click(function() {
        $(this).parent().sibling('input.form-control').datepicker();
    });

    /**
     * remove default value
     */
    $('.form-control').focus(function() {
        var check_val = getCheckVal($(this));
        if($(this).val() === check_val) {
            $(this).val('');
        }
        textToPassword($(this));
    });

    /**
     * add default value
     */
    $('.form-control').blur(function() {
        if(!$(this).val()) {
            var check_val = getCheckVal($(this));
            $(this).val(check_val);
            passwordToText($(this));
        }
    });

    $('.form-control[name=password], .form-control[name=confirm_password], .form-control[name=confirm_new_password], .form-control[name=new_password]').attr('type', 'text');

    /**
     * convert a password field to a text field
     * @param {type} element
     * @returns {undefined}
     */
    function passwordToText(element) {
        if(element.attr('type') === 'password') {
            element.attr('type', 'text');
        }
    }

    /**
     * convert a text field to a password field
     * @param {type} element
     * @returns {undefined}
     */
    function textToPassword(element) {
        if(element.attr('name') === 'password'
        || element.attr('name') === 'confirm_password'
        || element.attr('name') === 'confirm_new_password'
        || element.attr('name') === 'new_password') {

            element.attr('type', 'password');
        }
    }

    /**
     * get the value to check against
     *
     * @param {type} element
     * @returns {unresolved}
     */
    function getCheckVal(element) {
        return ucwords(element.attr('id').replace(/_/g, ' '));
    }

    /**
     * upper case all words in a string
     * @param {type} value
     * @returns {unresolved}
     */
    function ucwords(value) {
        var words = value.split(' ');
        for(var i in words) {
            words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
        }

        return words.join(' ');
    }
});