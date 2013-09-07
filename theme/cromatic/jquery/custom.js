var ret = false;
$( document ).ready(function() {
    if ($("#gradersubmit")) {
        $("#gradersubmit").closest("form").submit(function(e) {
            if (ret) {
                return true;
            }
            else {
                $.confirm({
                    'title'     : 'Update Confirmation',
                    'message'   : 'You are about to update this item. <br />Are you sure! Continue?',
                    'buttons'   : {
                        'Yes'   : {
                            'class' : 'blue',
                            'action': function(){
                                ret = true;
                                $("#gradersubmit").closest("form").submit();
                            }
                        },
                        'No'    : {
                            'class' : 'gray',
                            'action': function(){
                                ret = false; 
                            }
                        }
                    }
                });
                return false;
            }
        });
    }
});
