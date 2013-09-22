var ret = false;
var laeret = false;
$( document ).ready(function() {
    // add submit confirm popup for default grader report page
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

    // add submit confirm popup for LAE grader report page
    if ($("#laegrader-form")) {
        $("#laegrader-form").submit(function(e) {
            if (laeret) {
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
                                laeret = true;
                                $("#laegrader-form").submit();
                            }
                        },
                        'No'    : {
                            'class' : 'gray',
                            'action': function(){
                                laeret = false; 
                            }
                        }
                    }
                });
                return false;
            }
        });
    }
});
