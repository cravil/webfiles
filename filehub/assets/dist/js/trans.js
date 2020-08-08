$('.confirmAction').click(function(e){
    var id = $(this).attr('id').replace(/confirmButton/, '');
    $('#transID').val(id);
    $('.confirm-form').attr('action', e.currentTarget.value);
})
$('.confirm-form').submit(function(e){
e.preventDefault();
var actionurl = e.currentTarget.action
$(this).find(':input[type=submit]').prop('disabled', true);
$(this).find(':input[type=submit]').html('Processing …');
$.ajax({
    type: "POST",
    url: actionurl,
    data: $(this).serialize(),
    success: function(result) {
        $('#confirmationModal').modal('toggle');
        var content = JSON.parse(result);
        $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
        $('#buttonSubmit').prop('disabled', false);
        $('#buttonSubmit').html('Save Changes');
        swal(
            content.success == true ? 'Success!' : 'Error!',
            content.msg,
            content.success == true ? 'success' : 'error'
        );
        
        if(content.success == true)
        {
            var id = actionurl.substring(actionurl.lastIndexOf('/') + 1);
            if(document.title == 'Withdrawals') {
                $('#confirmButton' + id).remove();
            } else if(document.title == 'Deposits') {
                $('#row' + id).remove();
            }
        }
    },
    error: function(result) {
        $value = 'error';
        //alert($value);
        $('#buttonSubmit').prop('disabled', false);
        $('#buttonSubmit').html('Save Changes');
        }
    })
})
$('.reinvest').click(function(e){
    e.preventDefault();
    var val = e.currentTarget.value;
    var msg = '<p>Please confirm that you want to reinvest by entering your password below</p><div class="form-group"><input class="form-control" name="password" id="password" type="password"/></div>';
    var button = '<input name="code" value="'+val+'" hidden/><button id="withdrawalID" type="submit" class="btn btn-primary btn-sm">Proceed and re-invest</button>';
    $('#modalBody').html(msg);
    $('#reinvestPlans').show();
    $('#model-8').html('Reinvest Funds');
    $('#continue').html(button);
    $('#modalForm').attr('action', './reinvest');
})
$('.withdraw').click(function(e){
    e.preventDefault();
    var id = e.currentTarget.id;
    var val = e.currentTarget.value;
    var msg = '<h1 class="text-center">Withdraw '+val+'</h1><p class="text-center">Deposit ID:'+id+'</p>';
    var button = '<input name="code" value="'+id+'" hidden/><button id="withdrawalID" type="submit" value="'+id+'" class="btn btn-primary btn-sm">Proceed and withdraw</button>';
    $('#modalBody').html(msg);
    $('#reinvestPlans').hide();
    $('#model-8').html('Withdraw Funds');
    $('#continue').html(button);
    $('#modalForm').attr('action', './withdrawDeposit');
})
$('#modalForm').on('submit', function(e){
    e.preventDefault();
    var url = e.currentTarget.action;
    $(this).find(':input[type=submit]').prop('disabled', true);
    $(this).find(':input[type=submit]').html('Processing …');
    $.ajax({
        url: url,
        method:"POST",
        data:new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success:function(data)
        {
            $('#modal').modal('toggle');
            var content = JSON.parse(data);
            $('#modalForm').find(':input[type=submit]').prop('disabled', false);
            $('#modalForm').find(':input[type=submit]').html('Save Changes');
            if(content.success == true)
            {
                var code =document.querySelector('[name="code"]').value;
                $('#' + code).remove();
                $('#reinvest' + code).remove();
                $('#col' + code).html('Withdrawn');
            }
            swal(
                content.success == true ? 'Success!' : 'Error!',
                content.msg,
                content.success == true ? 'success' : 'error'
            );
        },
            error: function(data) {
                $('#modalForm').find(':input[type=submit]').prop('disabled', false);
                $('#modalForm').find(':input[type=submit]').html('Save Changes');
                swal(
                    'Error!',
                    'There is an issue in processing your transaction. Please try again later',
                    'error'
                );
            }
        })
    })