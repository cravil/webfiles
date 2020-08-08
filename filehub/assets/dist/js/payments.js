function getURL() {
    var x = document.getElementById("IPNURL").value;
    document.getElementById("typedURL").innerHTML = x;
  }
$("#reveal_pKey").click(function() {
    var text = $("#reveal_pKey").html();
    var newtext = (text === "Show")?"Hide":"Show";
	var pwdType = $("#pKey").attr("type");
	var newType = (pwdType === "password")?"text":"password";
    $("#pKey").attr("type", newType);
    $("#reveal_pKey").html(newtext);
})
$("#reveal_sKey").click(function() {
    var text = $("#reveal_sKey").html();
    var newtext = (text === "Show")?"Hide":"Show";
	var pwdType = $("#sKey").attr("type");
	var newType = (pwdType === "password")?"text":"password";
    $("#sKey").attr("type", newType);
    $("#reveal_sKey").html(newtext);
})
$("#reveal_iKey").click(function() {
    var text = $("#reveal_iKey").html();
    var newtext = (text === "Show")?"Hide":"Show";
	var pwdType = $("#IPNKey").attr("type");
	var newType = (pwdType === "password")?"text":"password";
    $("#IPNKey").attr("type", newType);
    $("#reveal_iKey").html(newtext);
})
$(".methodFormButton").submit(function(e) {
    e.preventDefault();
    $('.dt-drawer').addClass('open');
    $('#sideContent').hide();
    $('#loader').show();
    var actionurl = e.currentTarget.action;
    $.ajax({
        type: "POST",
        url: actionurl,
        data: $(this).serialize(),
        success: function(result) {
            var content = JSON.parse(result);
            $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
            $("#methImg").attr('src', "../assets/dist/img/" + content
                .logo);
            $('#pKey').val(content.publicKey);
            $('#sKey').val(content.secretKey);
            $('#bname').val(content.bname);
            $('#acname').val(content.acname);
            $('#acnumber').val(content.acnumber);
            $('#swcode').val(content.swcode);
            if(content.name == 'Twilio'){
                $('#public_key_title').html('Account SID');
                $('#secret_key_title').html('Auth Token');
                $('#IPN_fields').hide();
                $('#merchID').hide();
                $('#publicKey').show();
                $('#mode').hide();
            }
            if(content.name == 'Payeer'){
                $('#public_key_title').html('Public Key');
                $('#secret_key_title').html('Secret Key');
                $('#merchID').show();
                $('#publicKey').hide();
                $('#merchantID1').val(content.merchantID);
                $('#IPN_fields').hide();
                $('#mode').hide();
            }
            if(content.name == 'Stripe'){
                $('#public_key_title').html('Public Key');
                $('#secret_key_title').html('Secret Key');
                $('#IPN_fields').hide();
                $('#merchID').hide();
                $('#publicKey').show();
                $('#mode').hide();
            }
            if(content.name == 'PayPal'){
                $('#public_key_title').html('Client ID');
                $('#secret_key_title').html('Secret');
                $('#IPN_fields').hide();
                $('#merchID').hide();
                $('#publicKey').show();
                $('#mode').show();
                $("#mode-select").val(content.env);
            }
            if(content.name == 'Skrill'){
                $('#public_key_title').html('Public Key');
                $('#secret_key_title').html('Secret Key');
                $('#IPN_fields').hide();
                $('#merchID').hide();
                $('#publicKey').show();
                $('#mode').hide();
            }
            if(content.name == 'Block.io'){
                $('#public_key_title').html('Public Key');
                $('#secret_key_title').html('Secret Key');
                $('#IPN_fields').hide();
                $('#merchID').hide();
                $('#publicKey').show();
                $('#mode').hide();
            }
            if(content.name == 'CoinPayments'){
                $('#public_key_title').html('Public Key');
                $('#secret_key_title').html('Secret Key');
                $('#IPN_fields').show();
                $('#merchID').hide();
                $('#publicKey').show();
                $('#merchantID').val(content.merchantID);
                $('#IPNKey').val(content.IPNKey);
                $('#IPNURL').val(content.IPNURL);
                $('#typedURL').html(content.IPNURL);
                $('#mode').hide();
            }
            if(content.ref == 'BT'){
                $('#bankForm').show();
                $('#statusTitle').hide();
            }else{
                $('#bankForm').hide();
                $('#statusTitle').show();
            }
            $('#methID').val(content.name);
            $("#method-header").text(content.name + " Settings");
            content.status == 1 ? $("#active").prop("checked", true) : $("#inactive").prop(
                "checked", true);
            setTimeout(function () {
                $('#sideContent').show();
                $('#loader').hide();
            }, 2000); 
        },
        error: function(result) {
            $value = 'error';
            //alert($value);
        }
    });
});
$("#methodForm").submit(function(e) {
    e.preventDefault();
    var actionurl = e.currentTarget.action;
    var formid = e.currentTarget.id;
    $('.error').hide();
    $('.form-control').removeClass('inputTxtError');
    $.ajax({
        url: actionurl,
        type: 'post',
        data: $('#' + formid).serialize(),
        success: function(data) {
            $('#form-modal' + formid).modal('toggle');
            var content = JSON.parse(data);
            $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
            swal(
                content.success == true ? 'Success!' : 'Error!',
                content.msg,
                content.success == true ? 'success' : 'error'
            );
            if (content.success == true) {
                if(content.status == 1){
                    $('#methcol'+content._id).text('Active');
                    $('#methcol'+content._id).addClass('green');
                    $('#methcol'+content._id).removeClass('red');
                }else if (content.status == 0){
                    $('#methcol'+content._id).text('Inactive');
                    $('#methcol'+content._id).addClass('red');
                    $('#methcol'+content._id).removeClass('green');
                } 
            }
            if (content.success == false) {
                $.each(content.errors, function(key, value) {
                    // here you can access all the properties just by typing either value.propertyName or value["propertyName"]
                    // example: value.ri_idx; value.ri_startDate; value.ri_endDate;
                    var msg = '<label class="error" for="' + key + '">' + value +
                        '</label>';
                    $('input[name="' + key + '"], select[name="' + key + '"]').addClass(
                        'inputTxtError').after(msg);
                });
            }
        },
        error: function(data) {
            $('.modal').modal('toggle');
            swal(
                'Error!',
                'There is an issue in updating your data. Please try again later',
                'error'
            );
        }
    });

});