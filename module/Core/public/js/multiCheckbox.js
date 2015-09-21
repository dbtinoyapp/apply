var setTableMultiCheckbox = function(target) {

    $(target).find('th').find(':checkbox').bind('click', function() {
        // which column
        var checked = $(this).prop('checked');
        var th = $(this).parents('th:first');
        var tr = th.parents('tr:first');
        var table = tr.parents('table:first');
        var tablebody = table.children('tbody');
        var row = -1;
        tr.children().each(function(i) {
            if (th.get(0) == $(this).get(0)) {
                row = i;
            }
        });
        if (0 <= row) {
            tablebody.children().each(function() {
                var rowElem = $(this);

                rowElem.children().each(function(i) {
                    if (i == row) {
                        $(this).find(':checkbox:first').prop('checked', checked);
                    }
                });
            });
        }
        ;
    })
};

/*
 * find the IDs of all Checkboxes
 * target is an Element in the Header of the table
 * target is used to get the row of the associatet Checkboxes
 */
var getTableMultiCheckbox = function(target) {
    var th = $(target).parents('th:first');
    var tr = th.parents('tr:first');
    var table = tr.parents('table:first');
    var tablebody = table.children('tbody');

    var row = -1;
    tr.children().each(function(i) {
        if ($(this).get(0) == th.get(0)) {
            row = i;
        }
    });

    result = [];
    if (-1 < row) {
        tablebody.children().each(function() {
            var rowElem = $(this);
            var chckBox = rowElem.children().eq(row).find(':checkbox:first');
            if (chckBox.prop('checked')) {
                result.push(chckBox.attr('id'));
            }
        });
    }
    return result;
};


/**
 * this is special for the list
 * @returns {undefined}
 */
var setApplicationListActions = function() {
    $('#applicationTableAction').find('a').click(function(event) {
        var target = event.target;

        $(target).trigger('wait.start');
        var href = $(target).attr('href');
        if (0 < href.length) {
            var result = getTableMultiCheckbox(target);
            if (0 < result.length) {
                var matchActionRef = /^.*?(\w+)$/gi
                var matchAction = matchActionRef.exec(href);
                if (matchAction != null && 0 < matchAction.length) {
                    var command = matchAction[1];

                    $.post(basePath + '/' + lang + '/applications/multi/' + command, {'elements': result}, function(data) {
                        $(target).trigger('wait.stop');
                        console.log('ajax-result', data);
                        $(target).trigger('modalbox', data);

                    });
                }
            }
            else {
                $(target).trigger('wait.stop');
            }
        }
        return;
    });
};

/*
 * ToDo: Headscript
 */
(function($) {
    $('document').ready(function() {

        setTableMultiCheckbox('div.pagination-container');
        $('.ppt-list-action').waitAction('.action-loading');
        $('.table-action').dropdown();
        setApplicationListActions();

        $('div.pagination-container').on('ajax.ready', function() {
            setTableMultiCheckbox($(this));
            $('.table-action').dropdown();
            $('.ppt-list-action').waitAction('.action-loading');
            setApplicationListActions();
        });



        /**
         * for the Modalbox the action for a submit
         */
        $("#modal-submit").bind("click", function(event) {
            var form = $('#apps-modal').find('form');
            var formData = form.serializeArray();
            var action = form.attr('action');
            var $modalbox = $('#apps-modal');
            $modalbox.modal('hide');
            $('div.pagination-container').trigger('wait.start');
            $.post(action, formData, function() {
                var data = new Object;
                $('div.pagination-container').trigger('paginate', [data]);
                $('div.pagination-container').trigger('wait.stop');
            });

        });

        /**
         * Listener for opening the Modalbox,
         * Listener takes following arguments in data:
         * - content
         * - header
         */
        $('body').bind('modalbox', function(event, data) {
            console.log('modalbox', event, data);
            var $modalbox = $('#apps-modal');
            if (typeof data.content != 'undefined') {
                $modalbox.find('.modal-body').empty().append(data.content);
            }
            if (typeof data.header != 'undefined') {
                $modalbox.find('.modal-header h3').empty().append(data.header);
            }

            $modalbox.modal('show');
            return false;
        });

    });
})(jQuery);