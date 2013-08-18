var ret = false;
$( document ).ready(function() {
    if ($("#gradersubmit")) {
        $("#gradersubmit").closest("form").submit(function(e) {
            if (ret) {
                return true;
            }
            else {
                $.confirm({
                    'title'     : 'Delete Confirmation',
                    'message'   : 'You are about to delete this item. <br />It cannot be restored at a later time! Continue?',
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
